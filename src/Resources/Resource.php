<?php

declare(strict_types=1);

namespace RelayWarden\Resources;

use RelayWarden\Client;

/**
 * Base resource class.
 */
abstract class Resource
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
