<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Usage resource for viewing usage statistics and limits.
 */
class Usage extends Resource
{
    /**
     * Get daily usage statistics for the current team.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function getDaily(array $filters = []): array
    {
        return $this->client->get('/usage/daily', $filters);
    }

    /**
     * Get current usage limits and remaining quota.
     *
     * @return array<string, mixed>
     */
    public function getLimits(): array
    {
        return $this->client->get('/limits');
    }

    /**
     * Get system health and diagnostic information.
     *
     * @return array<string, mixed>
     */
    public function getDiagnostics(): array
    {
        return $this->client->get('/diagnostics');
    }
}
