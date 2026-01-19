<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Domains resource for managing sending domains.
 */
class Domains extends Resource
{
    /**
     * List all sending domains for the current project.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/domains', $filters);
    }

    /**
     * Get a specific domain by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/domains/{$id}");
    }

    /**
     * Create a new sending domain.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post('/domains', $data);
    }

    /**
     * Update a domain.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->client->patch("/domains/{$id}", $data);
    }

    /**
     * Delete a domain.
     */
    public function delete(string $id): void
    {
        $this->client->delete("/domains/{$id}");
    }

    /**
     * Get DNS records required for domain verification.
     *
     * @return array<string, mixed>
     */
    public function getDnsRecords(string $id): array
    {
        return $this->client->get("/domains/{$id}/dns-records");
    }

    /**
     * Get current status of domain verification checks.
     *
     * @return array<string, mixed>
     */
    public function getChecks(string $id): array
    {
        return $this->client->get("/domains/{$id}/checks");
    }

    /**
     * Initiate domain verification.
     *
     * @return array<string, mixed>
     */
    public function verify(string $id): array
    {
        return $this->client->post("/domains/{$id}/verify");
    }

    /**
     * Rotate DKIM signing keys for a domain.
     *
     * @return array<string, mixed>
     */
    public function rotateDkim(string $id): array
    {
        return $this->client->post("/domains/{$id}/dkim/rotate");
    }

    /**
     * Enable a domain for production use.
     *
     * @return array<string, mixed>
     */
    public function enableProduction(string $id): array
    {
        return $this->client->post("/domains/{$id}/enable-production");
    }
}
