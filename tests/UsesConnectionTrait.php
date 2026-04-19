<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests;

use GuzzleHttp\Client;
use RuntimeException;
use SergiyNezbritskiy\NovaPoshta\Connection;

trait UsesConnectionTrait
{
    private Connection $client;

    public function getConnection(): Connection
    {
        if (empty($this->client)) {
            $apiKey = getenv('API_KEY');
            if (empty($apiKey)) {
                throw new RuntimeException('API_KEY not provided. Please pass it through phpunit.xml or env variables');
            }
            $this->client = new Connection($apiKey, new Client());
        }
        return $this->client;
    }
}
