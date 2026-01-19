<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Identity resource for user/team information.
 */
class Identity extends Resource
{
    /**
     * Get information about the currently authenticated user or service account.
     *
     * @return array<string, mixed>
     */
    public function me(): array
    {
        return $this->client->get('/me');
    }

    /**
     * List all teams the authenticated user belongs to.
     *
     * @return array<string, mixed>
     */
    public function teams(): array
    {
        return $this->client->get('/teams');
    }
}
