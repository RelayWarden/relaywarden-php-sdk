<?php

declare(strict_types=1);

namespace RelayWarden;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use RelayWarden\Exceptions\ApiException;
use RelayWarden\Exceptions\AuthenticationException;
use RelayWarden\Exceptions\RateLimitException;
use RelayWarden\Exceptions\ValidationException;
use RelayWarden\Resources\AuditLogs;
use RelayWarden\Resources\Compliance;
use RelayWarden\Resources\Domains;
use RelayWarden\Resources\Events;
use RelayWarden\Resources\Identity;
use RelayWarden\Resources\Messages;
use RelayWarden\Resources\Projects;
use RelayWarden\Resources\Senders;
use RelayWarden\Resources\ServiceAccounts;
use RelayWarden\Resources\Suppressions;
use RelayWarden\Resources\Templates;
use RelayWarden\Resources\Usage;
use RelayWarden\Resources\Webhooks;

/**
 * Main client for interacting with the RelayWarden API.
 */
class Client
{
    private GuzzleClient $httpClient;

    private string $baseUrl;

    private string $token;

    private ?string $projectId = null;

    private ?string $teamId = null;

    private int $maxRetries = 3;

    private int $timeout = 30;

    private ?Identity $identity = null;

    private ?Projects $projects = null;

    private ?ServiceAccounts $serviceAccounts = null;

    private ?Domains $domains = null;

    private ?Senders $senders = null;

    private ?Templates $templates = null;

    private ?Messages $messages = null;

    private ?Events $events = null;

    private ?Webhooks $webhooks = null;

    private ?Suppressions $suppressions = null;

    private ?Usage $usage = null;

    private ?AuditLogs $auditLogs = null;

    private ?Compliance $compliance = null;

    public function __construct(string $baseUrl, string $token, array $options = [])
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->token = $token;
        $this->maxRetries = $options['max_retries'] ?? 3;
        $this->timeout = $options['timeout'] ?? 30;

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function setProjectId(?string $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public function setTeamId(?string $teamId): void
    {
        $this->teamId = $teamId;
    }

    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    public function identity(): Identity
    {
        if ($this->identity === null) {
            $this->identity = new Identity($this);
        }

        return $this->identity;
    }

    public function projects(): Projects
    {
        if ($this->projects === null) {
            $this->projects = new Projects($this);
        }

        return $this->projects;
    }

    public function serviceAccounts(): ServiceAccounts
    {
        if ($this->serviceAccounts === null) {
            $this->serviceAccounts = new ServiceAccounts($this);
        }

        return $this->serviceAccounts;
    }

    public function domains(): Domains
    {
        if ($this->domains === null) {
            $this->domains = new Domains($this);
        }

        return $this->domains;
    }

    public function senders(): Senders
    {
        if ($this->senders === null) {
            $this->senders = new Senders($this);
        }

        return $this->senders;
    }

    public function templates(): Templates
    {
        if ($this->templates === null) {
            $this->templates = new Templates($this);
        }

        return $this->templates;
    }

    public function messages(): Messages
    {
        if ($this->messages === null) {
            $this->messages = new Messages($this);
        }

        return $this->messages;
    }

    public function events(): Events
    {
        if ($this->events === null) {
            $this->events = new Events($this);
        }

        return $this->events;
    }

    public function webhooks(): Webhooks
    {
        if ($this->webhooks === null) {
            $this->webhooks = new Webhooks($this);
        }

        return $this->webhooks;
    }

    public function suppressions(): Suppressions
    {
        if ($this->suppressions === null) {
            $this->suppressions = new Suppressions($this);
        }

        return $this->suppressions;
    }

    public function usage(): Usage
    {
        if ($this->usage === null) {
            $this->usage = new Usage($this);
        }

        return $this->usage;
    }

    public function auditLogs(): AuditLogs
    {
        if ($this->auditLogs === null) {
            $this->auditLogs = new AuditLogs($this);
        }

        return $this->auditLogs;
    }

    public function compliance(): Compliance
    {
        if ($this->compliance === null) {
            $this->compliance = new Compliance($this);
        }

        return $this->compliance;
    }

    /**
     * Make a GET request.
     *
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, ['query' => $query]);
    }

    /**
     * Make a POST request.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function post(string $path, array $data = [], array $headers = []): array
    {
        return $this->request('POST', $path, ['json' => $data, 'headers' => $headers]);
    }

    /**
     * Make a PATCH request.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function patch(string $path, array $data = []): array
    {
        return $this->request('PATCH', $path, ['json' => $data]);
    }

    /**
     * Make a DELETE request.
     */
    public function delete(string $path): void
    {
        $this->request('DELETE', $path);
    }

    /**
     * Make an HTTP request with retry logic.
     *
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>|null
     */
    public function request(string $method, string $path, array $options = []): ?array
    {
        $options['headers'] = array_merge($this->getDefaultHeaders(), $options['headers'] ?? []);

        $attempt = 0;
        $lastException = null;

        while ($attempt <= $this->maxRetries) {
            try {
                $response = $this->httpClient->request($method, $path, $options);
                $statusCode = $response->getStatusCode();

                if ($statusCode === 204) {
                    return null;
                }

                $body = json_decode((string) $response->getBody(), true);

                if ($statusCode >= 200 && $statusCode < 300) {
                    return $body;
                }

                $this->handleErrorResponse($response, $body);
            } catch (RateLimitException $e) {
                $lastException = $e;
                if ($attempt < $this->maxRetries) {
                    sleep($e->getRetryAfter());
                    $attempt++;
                    continue;
                }
                throw $e;
            } catch (RequestException $e) {
                $lastException = $e;
                if ($attempt < $this->maxRetries && $this->isRetryableError($e)) {
                    $attempt++;
                    usleep(100000 * $attempt); // Exponential backoff
                    continue;
                }
                $this->handleRequestException($e);
            } catch (GuzzleException $e) {
                $lastException = $e;
                if ($attempt < $this->maxRetries) {
                    $attempt++;
                    usleep(100000 * $attempt);
                    continue;
                }
                throw new ApiException('Network error: '.$e->getMessage(), 0, $e);
            }
        }

        if ($lastException !== null) {
            throw $lastException;
        }

        throw new ApiException('Request failed after '.$this->maxRetries.' retries');
    }

    /**
     * Get default headers including project/team IDs.
     *
     * @return array<string, string>
     */
    private function getDefaultHeaders(): array
    {
        $headers = [];

        if ($this->projectId !== null) {
            $headers['X-Project-Id'] = $this->projectId;
        }

        if ($this->teamId !== null) {
            $headers['X-Team-Id'] = $this->teamId;
        }

        return $headers;
    }

    /**
     * Handle error responses from the API.
     *
     * @param  array<string, mixed>|null  $body
     */
    private function handleErrorResponse(ResponseInterface $response, ?array $body): void
    {
        $statusCode = $response->getStatusCode();
        $requestId = $body['meta']['request_id'] ?? '';

        $error = $body['error'] ?? [];
        $errorCode = $error['code'] ?? '';
        $errorMessage = $error['message'] ?? 'An error occurred';
        $errorDetails = $error['details'] ?? [];

        match ($statusCode) {
            401 => throw new AuthenticationException($errorMessage, $statusCode, null, $requestId, $errorCode),
            422 => throw new ValidationException($errorMessage, $errorDetails, $requestId, $statusCode),
            429 => throw new RateLimitException(
                $errorMessage,
                $statusCode,
                null,
                $requestId,
                (int) ($response->getHeaderLine('Retry-After') ?: 60)
            ),
            default => throw new ApiException($errorMessage, $statusCode, null, $requestId, $errorCode, $errorDetails),
        };
    }

    /**
     * Handle Guzzle request exceptions.
     */
    private function handleRequestException(RequestException $e): void
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            if ($response !== null) {
                $body = json_decode((string) $response->getBody(), true);
                $this->handleErrorResponse($response, $body);

                return;
            }
        }

        throw new ApiException('Request failed: '.$e->getMessage(), 0, $e);
    }

    /**
     * Check if an error is retryable.
     */
    private function isRetryableError(RequestException $e): bool
    {
        if (! $e->hasResponse()) {
            return true; // Network errors are retryable
        }

        $response = $e->getResponse();
        if ($response === null) {
            return true;
        }

        $statusCode = $response->getStatusCode();

        return $statusCode >= 500 || $statusCode === 429;
    }
}
