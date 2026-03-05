<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Services;

use BrewLink\Domain\Contracts\ClockInterface;
use BrewLink\Domain\Contracts\IdGeneratorInterface;
use BrewLink\Infrastructure\Exceptions\Snowflake\ClockMovedBackwardsException;
use BrewLink\Infrastructure\Exceptions\Snowflake\InvalidWorkerIdException;
use BrewLink\Infrastructure\Exceptions\Snowflake\TimestampLimitExceededException;

class SnowflakeIdGenerator implements IdGeneratorInterface
{
    private const EPOCH = 1767225600000;

    private const WORKER_BITS = 8;

    private const SEQUENCE_BITS = 12;

    private const MAX_WORKER_ID = (1 << self::WORKER_BITS) - 1;

    private const MAX_SEQUENCE = (1 << self::SEQUENCE_BITS) - 1;

    private const WORKER_SHIFT = self::SEQUENCE_BITS;

    private const TIMESTAMP_SHIFT = self::SEQUENCE_BITS + self::WORKER_BITS;

    private const TIMESTAMP_BITS = 43;

    private const MAX_TIMESTAMP_DELTA = (1 << self::TIMESTAMP_BITS) - 1;

    private int $workerId;

    private int $lastTimestamp = -1;

    private int $sequence = 0;

    private ClockInterface $clock;

    public function __construct(int $workerId, ClockInterface $clock)
    {
        if ($workerId < 0 || $workerId > self::MAX_WORKER_ID) {
            throw new InvalidWorkerIdException(self::MAX_WORKER_ID);
        }

        $this->workerId = $workerId;
        $this->clock = $clock;
    }

    public function generate(): int
    {
        $currentTimestamp = $this->clock->getTimestamp();

        if ($currentTimestamp < $this->lastTimestamp) {
            throw new ClockMovedBackwardsException;
        }

        $timestampDelta = $currentTimestamp - self::EPOCH;

        if ($timestampDelta > self::MAX_TIMESTAMP_DELTA) {
            throw new TimestampLimitExceededException(self::MAX_TIMESTAMP_DELTA);
        }

        if ($currentTimestamp === $this->lastTimestamp) {
            $this->sequence = ($this->sequence + 1) & self::MAX_SEQUENCE;

            if ($this->sequence === 0) {
                $currentTimestamp = $this->waitNextMillis($this->lastTimestamp);
                $timestampDelta = $currentTimestamp - self::EPOCH;
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $currentTimestamp;

        return ($timestampDelta << self::TIMESTAMP_SHIFT)
            | ($this->workerId << self::WORKER_SHIFT)
            | $this->sequence;
    }

    private function waitNextMillis(int $lastTimestamp): int
    {
        $timestamp = $this->clock->getTimestamp();

        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->clock->getTimestamp();
        }

        return $timestamp;
    }
}
