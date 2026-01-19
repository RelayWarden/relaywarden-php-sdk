<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Projects resource for managing projects.
 */
class Projects extends Resource
{
    /**
     * List all projects for the current team.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/projects', $filters);
    }

    /**
     * Get a specific project by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/projects/{$id}");
    }

    /**
     * Create a new project.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post('/projects', $data);
    }

    /**
     * Update a project.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->client->patch("/projects/{$id}", $data);
    }

    /**
     * Delete a project.
     */
    public function delete(string $id): void
    {
        $this->client->delete("/projects/{$id}");
    }
}
