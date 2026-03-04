<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use BrewLink\Domain\Contracts\ClockInterface;
use BrewLink\Infrastructure\Exceptions\Snowflake\ClockMovedBackwardsException;
use BrewLink\Infrastructure\Exceptions\Snowflake\InvalidWorkerIdException;
use BrewLink\Infrastructure\Exceptions\Snowflake\TimestampLimitExceededException;
use BrewLink\Infrastructure\Services\SnowflakeIdGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SnowflakeIdGeneratorTest extends TestCase
{
    protected ClockInterface&MockObject $clock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clock = $this->createMock(ClockInterface::class);
    }

    public function test_should_throw_exception_when_worker_id_exceeds_255(): void
    {
        $workerId = 256;

        $this->expectException(InvalidWorkerIdException::class);

        new SnowflakeIdGenerator($workerId, $this->clock);
    }

    public function test_should_throw_exception_when_clock_moves_backwards(): void
    {
        $startTime = 1767225600500;
        $timeBackwards = 1767225600400;

        $this->clock->expects($this->exactly(2))
            ->method('getTimestamp')
            ->willReturnOnConsecutiveCalls($startTime, $timeBackwards);

        $generator = new SnowflakeIdGenerator(1, $this->clock);

        $generator->generate();

        $this->expectException(ClockMovedBackwardsException::class);

        $generator->generate();
    }

    public function test_should_throw_exception_when_timestamp_exceeds_supported_limit(): void
    {
        $clockMock = $this->createMock(ClockInterface::class);

        $overflowTimestamp = 10563318622208;

        $clockMock->expects($this->once())
            ->method('getTimestamp')
            ->willReturn($overflowTimestamp);

        $generator = new SnowflakeIdGenerator(1, $clockMock);

        $this->expectException(TimestampLimitExceededException::class);
        $this->expectExceptionMessage('Time limit exceeded');

        $generator->generate();
    }

    public function test_should_wait_for_next_millisecond_when_sequence_overflows(): void
    {
        $clockMock = $this->createMock(ClockInterface::class);

        $initialTimestamp = 1767225601000;
        $clockCalls = 0;

        $clockMock->method('getTimestamp')
            ->willReturnCallback(function () use (&$clockCalls, &$initialTimestamp) {
                $clockCalls++;

                if ($clockCalls <= 4097) {
                    return $initialTimestamp;
                }

                if ($clockCalls === 4098) {
                    return $initialTimestamp;
                }

                return $initialTimestamp + 1;
            });

        $generator = new SnowflakeIdGenerator(1, $clockMock);

        for ($i = 0; $i < 4096; $i++) {
            $generator->generate();
        }

        $generator->generate();

        $this->assertEquals(4099, $clockCalls);
    }

    public function test_should_generate_expected_id_with_known_values(): void
    {
        $clockMock = $this->createMock(ClockInterface::class);

        $fixedTimestamp = 1767225600005;

        $clockMock->expects($this->once())
            ->method('getTimestamp')
            ->willReturn($fixedTimestamp);

        $generator = new SnowflakeIdGenerator(1, $clockMock);

        $expectedId = 5246976;

        $generatedId = $generator->generate();

        $this->assertSame(
            $expectedId,
            $generatedId,
            'Generated ID does not match expected bit calculation.',
        );
    }
}
