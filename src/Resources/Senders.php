<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Senders resource for managing sender addresses.
 */
class Senders extends Resource
{
    /**
     * List all sender addresses for the current project.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/senders', $filters);
    }

    /**
     * Get a specific sender by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/senders/{$id}");
    }

    /**
     * Create a new sender address.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post('/senders', $data);
    }

    /**
     * Delete a sender address.
     */
    public function delete(string $id): void
    {
        $this->client->delete("/senders/{$id}");
    }

    /**
     * Initiate sender verification.
     *
     * @return array<string, mixed>
     */
    public function verify(string $id): array
    {
        return $this->client->post("/senders/{$id}/verify");
    }
}
