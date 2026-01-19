<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

/**
 * Webhooks resource for managing webhook endpoints.
 */
class Webhooks extends Resource
{
    /**
     * List all webhook endpoints for the current project.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function listEndpoints(array $filters = []): array
    {
        return $this->client->get('/webhooks/endpoints', $filters);
    }

    /**
     * Create a new webhook endpoint.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createEndpoint(array $data): array
    {
        return $this->client->post('/webhooks/endpoints', $data);
    }

    /**
     * Update a webhook endpoint.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function updateEndpoint(string $id, array $data): array
    {
        return $this->client->patch("/webhooks/endpoints/{$id}", $data);
    }

    /**
     * Delete a webhook endpoint.
     */
    public function deleteEndpoint(string $id): void
    {
        $this->client->delete("/webhooks/endpoints/{$id}");
    }

    /**
     * List all delivery attempts for a webhook endpoint.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function listDeliveries(string $endpointId, array $filters = []): array
    {
        return $this->client->get("/webhooks/endpoints/{$endpointId}/deliveries", $filters);
    }

    /**
     * Send a test webhook to verify the endpoint is working.
     *
     * @return array<string, mixed>
     */
    public function testEndpoint(string $id): array
    {
        return $this->client->post("/webhooks/endpoints/{$id}/test");
    }

    /**
     * Replay a failed webhook delivery.
     *
     * @return array<string, mixed>
     */
    public function replayDelivery(string $deliveryId): array
    {
        return $this->client->post("/webhooks/deliveries/{$deliveryId}/replay");
    }
}
