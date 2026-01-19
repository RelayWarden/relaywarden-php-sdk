<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Audit Logs resource for viewing audit history.
 */
class AuditLogs extends Resource
{
    /**
     * List audit logs for the current team.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/audit-logs', $filters);
    }

    /**
     * Get a specific audit log entry by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/audit-logs/{$id}");
    }
}
