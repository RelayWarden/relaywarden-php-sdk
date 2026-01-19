<?php

declare(strict_types=1);

namespace RelayWarden\Tests\Resources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RelayWarden\Client;
use RelayWarden\Resources\Messages;

class MessagesTest extends TestCase
{
    private Client $client;

    private Messages $messages;

    protected function setUp(): void
    {
        $this->client = new Client('https://api.relaywarden.eu/api/v1', 'test-token');
        $this->client->setProjectId('project-123');
        $this->messages = $this->client->messages();
    }

    public function test_send(): void
    {
        $mock = new MockHandler([
            new Response(202, [], json_encode([
                'data' => [
                    'message_id' => 'msg-123',
                    'status' => 'accepted',
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

        $result = $this->messages->send([
            'from' => ['email' => 'noreply@example.com'],
            'to' => [['email' => 'user@example.com']],
            'subject' => 'Test',
            'html' => '<h1>Test</h1>',
        ]);

        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('msg-123', $result['data']['message_id']);
    }

    public function test_list(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'data' => [
                    ['id' => 'msg-1', 'subject' => 'Test 1'],
                ],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 25,
                    'total' => 1,
                ],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzleClient);

        $result = $this->messages->list();
        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);
    }

    public function test_get(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'data' => [
                    'id' => 'msg-123',
                    'subject' => 'Test',
                    'status' => 'delivered',
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

        $result = $this->messages->get('msg-123');
        $this->assertEquals('msg-123', $result['data']['id']);
    }
}
