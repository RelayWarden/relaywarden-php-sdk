<?php

declare(strict_types=1);

namespace RelayWarden\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RelayWarden\Client;
use RelayWarden\Exceptions\AuthenticationException;
use RelayWarden\Exceptions\RateLimitException;
use RelayWarden\Exceptions\ValidationException;

class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client('https://api.relaywarden.eu/api/v1', 'test-token');
    }

    public function test_client_initialization(): void
    {
        $client = new Client('https://api.relaywarden.eu/api/v1', 'test-token');
        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_set_project_id(): void
    {
        $this->client->setProjectId('project-123');
        $this->assertEquals('project-123', $this->client->getProjectId());
    }

    public function test_set_team_id(): void
    {
        $this->client->setTeamId('team-123');
        $this->assertEquals('team-123', $this->client->getTeamId());
    }

    public function test_resource_accessors(): void
    {
        $this->assertInstanceOf(\RelayWarden\Resources\Identity::class, $this->client->identity());
        $this->assertInstanceOf(\RelayWarden\Resources\Projects::class, $this->client->projects());
        $this->assertInstanceOf(\RelayWarden\Resources\Messages::class, $this->client->messages());
        $this->assertInstanceOf(\RelayWarden\Resources\Templates::class, $this->client->templates());
    }

    public function test_get_request(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'data' => ['id' => '123', 'name' => 'Test'],
                'meta' => ['request_id' => 'req-123'],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzleClient);

        $result = $this->client->get('/test');
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('123', $result['data']['id']);
    }

    public function test_post_request(): void
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'data' => ['id' => '123'],
                'meta' => ['request_id' => 'req-123'],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzleClient);

        $result = $this->client->post('/test', ['name' => 'Test']);
        $this->assertArrayHasKey('data', $result);
    }

    public function test_delete_request(): void
    {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzleClient);

        $result = $this->client->delete('/test');
        $this->assertNull($result);
    }

    public function test_authentication_exception(): void
    {
        $this->expectException(AuthenticationException::class);

        $mock = new MockHandler([
            new Response(401, [], json_encode([
                'error' => [
                    'code' => 'unauthorized',
                    'message' => 'Unauthenticated',
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

        $this->client->get('/test');
    }

    public function test_validation_exception(): void
    {
        $this->expectException(ValidationException::class);

        $mock = new MockHandler([
            new Response(422, [], json_encode([
                'error' => [
                    'code' => 'validation_error',
                    'message' => 'Validation failed',
                    'details' => [
                        ['field' => 'email', 'message' => 'Invalid email'],
                    ],
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

        $this->client->post('/test', []);
    }

    public function test_rate_limit_exception(): void
    {
        $this->expectException(RateLimitException::class);

        // Create a new client with max_retries = 0 to prevent retries
        $client = new Client('https://api.relaywarden.eu/api/v1', 'test-token', ['max_retries' => 0]);

        $mock = new MockHandler([
            new Response(429, ['Retry-After' => '60'], json_encode([
                'error' => [
                    'code' => 'rate_limit_exceeded',
                    'message' => 'Rate limit exceeded',
                ],
                'meta' => ['request_id' => 'req-123'],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($client, $guzzleClient);

        try {
            $client->get('/test');
        } catch (RateLimitException $e) {
            $this->assertEquals(60, $e->getRetryAfter());
            throw $e;
        }
    }
}
