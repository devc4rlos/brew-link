<?php

namespace Tests\Integration\Infrastructure;

use Aws\DynamoDb\DynamoDbClient;
use Tests\TestCase;

class DynamoDbConnectionTest extends TestCase
{
    public function test_it_can_connect_to_localstack_and_see_the_brewed_table()
    {
        $config = config('aws');
        $expectedTableName = config('database.connections.dynamodb.table');

        $client = new DynamoDbClient([
            'region'   => $config['region'],
            'version'  => $config['version'],
            'endpoint' => $config['endpoint'],
            'credentials' => $config['credentials'],
        ]);

        $result = $client->describeTable([
            'TableName' => $expectedTableName
        ]);

        $this->assertEquals('ACTIVE', $result['Table']['TableStatus']);
        $this->assertEquals($expectedTableName, $result['Table']['TableName']);

        $keySchema = $result['Table']['KeySchema'][0];
        $this->assertEquals('short_code', $keySchema['AttributeName']);
    }
}