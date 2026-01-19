<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Suppressions resource for managing recipient suppressions.
 */
class Suppressions extends Resource
{
    /**
     * List all suppressions for the current team.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/suppressions', $filters);
    }

    /**
     * Add a recipient to the suppression list.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post('/suppressions', $data);
    }

    /**
     * Remove a recipient from the suppression list.
     */
    public function delete(string $id): void
    {
        $this->client->delete("/suppressions/{$id}");
    }

    /**
     * Import multiple suppressions in bulk.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function import(array $data): array
    {
        return $this->client->post('/suppressions/import', $data);
    }

    /**
     * Export all suppressions as a CSV file.
     *
     * @return string CSV content
     */
    public function export(): string
    {
        $response = $this->client->get('/suppressions/export');
        // The export endpoint returns CSV, not JSON
        // We need to handle this differently - for now return as string
        return $response;
    }
}
