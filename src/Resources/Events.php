<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Events resource for viewing event history.
 */
class Events extends Resource
{
    /**
     * List all events for the current team.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/events', $filters);
    }

    /**
     * Get a specific event by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/events/{$id}");
    }
}
