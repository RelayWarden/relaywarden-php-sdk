<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Compliance resource for managing data retention and compliance settings.
 */
class Compliance extends Resource
{
    /**
     * Get data retention settings for the current team.
     *
     * @return array<string, mixed>
     */
    public function getRetention(): array
    {
        return $this->client->get('/compliance/retention');
    }

    /**
     * Update data retention settings.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function updateRetention(array $data): array
    {
        return $this->client->patch('/compliance/retention', $data);
    }

    /**
     * Get available export formats and configuration.
     *
     * @return array<string, mixed>
     */
    public function getExportConfig(): array
    {
        return $this->client->get('/compliance/exports/config');
    }
}
