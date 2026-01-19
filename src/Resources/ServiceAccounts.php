<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Service Accounts resource for managing service accounts and tokens.
 */
class ServiceAccounts extends Resource
{
    /**
     * List all service accounts for the current team.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/service-accounts', $filters);
    }

    /**
     * Create a new service account.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post('/service-accounts', $data);
    }

    /**
     * Delete a service account.
     */
    public function delete(string $id): void
    {
        $this->client->delete("/service-accounts/{$id}");
    }

    /**
     * Create a new API token for a service account.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createToken(string $serviceAccountId, array $data): array
    {
        return $this->client->post("/service-accounts/{$serviceAccountId}/tokens", $data);
    }

    /**
     * Delete an API token.
     */
    public function deleteToken(string $tokenId): void
    {
        $this->client->delete("/tokens/{$tokenId}");
    }
}
