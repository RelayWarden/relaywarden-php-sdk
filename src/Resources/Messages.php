<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Messages resource for sending and managing email messages.
 */
class Messages extends Resource
{
    /**
     * Send an email message.
     *
     * @param  array<string, mixed>  $data
     * @param  string|null  $idempotencyKey
     * @return array<string, mixed>
     */
    public function send(array $data, ?string $idempotencyKey = null): array
    {
        $headers = [];
        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }

        return $this->client->post('/messages', $data, $headers);
    }

    /**
     * List all messages for the current project.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function list(array $filters = []): array
    {
        return $this->client->get('/messages', $filters);
    }

    /**
     * Get a specific message by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->client->get("/messages/{$id}");
    }

    /**
     * Get the complete timeline of events for a message.
     *
     * @return array<string, mixed>
     */
    public function getTimeline(string $id): array
    {
        return $this->client->get("/messages/{$id}/timeline");
    }

    /**
     * Cancel a message that hasn't been sent yet.
     *
     * @return array<string, mixed>
     */
    public function cancel(string $id): array
    {
        return $this->client->post("/messages/{$id}/cancel");
    }

    /**
     * Resend a previously sent message.
     *
     * @return array<string, mixed>
     */
    public function resend(string $id): array
    {
        return $this->client->post("/messages/{$id}/resend");
    }
}
