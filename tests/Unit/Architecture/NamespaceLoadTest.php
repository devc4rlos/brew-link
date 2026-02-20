<?php

namespace Tests\Unit\Architecture;

use BrewLink\Domain\Entities\BrewLinkTestEntity;
use PHPUnit\Framework\TestCase;

class NamespaceLoadTest extends TestCase
{
    public function test_it_should_load_classes_from_the_new_brewlink_domain_namespace()
    {
        $entity = new BrewLinkTestEntity();

        $this->assertTrue($entity->isValid());
    }

    public function test_it_should_verify_if_hexagonal_directories_exist()
    {
        $basePath = __DIR__ . '/../../../src/BrewLink';

        $this->assertDirectoryExists("$basePath/Domain", 'The Domain layer was not found.');
        $this->assertDirectoryExists("$basePath/Application", 'The Application layer was not found.');
        $this->assertDirectoryExists("$basePath/Infrastructure", 'The Infrastructure layer was not found.');
    }
}