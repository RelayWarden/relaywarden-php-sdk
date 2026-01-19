<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Templates resource for managing email templates.
 */
class Templates extends Resource
{
    /**
     * List all templates for the current project.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/templates', $filters);
    }

    /**
     * Get a specific template by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/templates/{$id}");
    }

    /**
     * Create a new template.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post('/templates', $data);
    }

    /**
     * Update a template.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->client->patch("/templates/{$id}", $data);
    }

    /**
     * Delete a template.
     */
    public function delete(string $id): void
    {
        $this->client->delete("/templates/{$id}");
    }

    /**
     * List all versions of a template.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function listVersions(string $id, array $filters = []): array
    {
        return $this->client->get("/templates/{$id}/versions", $filters);
    }

    /**
     * Create a new version of a template.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createVersion(string $id, array $data): array
    {
        return $this->client->post("/templates/{$id}/versions", $data);
    }

    /**
     * Render a template with provided data.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function render(string $id, array $data): array
    {
        return $this->client->post("/templates/{$id}/render", $data);
    }

    /**
     * Send a test email using the template.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function testSend(string $id, array $data): array
    {
        return $this->client->post("/templates/{$id}/test-send", $data);
    }
}
