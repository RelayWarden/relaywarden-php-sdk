<?php

declare(strict_types=1);

namespace RelayWarden\Tests\Resources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RelayWarden\Client;
use RelayWarden\Resources\Identity;

class IdentityTest extends TestCase
{
    private Client $client;

    private Identity $identity;

    protected function setUp(): void
    {
        $this->client = new Client('https://api.relaywarden.eu/api/v1', 'test-token');
        $this->identity = $this->client->identity();
    }

    public function test_me(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'data' => [
                    'id' => 'user-123',
                    'type' => 'user',
                    'email' => 'test@example.com',
                ],
                'meta' => ['request_id' => 'req-123'],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzleClient);

        $result = $this->identity->me();
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('user-123', $result['data']['id']);
    }

    public function test_teams(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'data' => [
                    ['id' => 'team-1', 'name' => 'Team 1'],
                ],
                'meta' => ['request_id' => 'req-123'],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzleClient);

        $result = $this->identity->teams();
        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);
    }
}
