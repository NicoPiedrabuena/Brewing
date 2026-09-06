<?php

namespace App\Database\Connectors;

use Illuminate\Database\Connectors\PostgresConnector;
use Illuminate\Support\Str;

class NeonPostgresConnector extends PostgresConnector
{
    /**
     * Add Neon's endpoint ID for older libpq clients bundled with Windows PHP.
     */
    public function connect(array $config)
    {
        $host = $config['host'] ?? null;
        $password = $config['password'] ?? null;

        if (
            PHP_OS_FAMILY === 'Windows'
            && is_string($host)
            && is_string($password)
            && Str::startsWith($host, 'ep-')
            && ! Str::startsWith($password, 'endpoint=')
        ) {
            $endpoint = Str::before(Str::before($host, '-pooler.'), '.');

            $config['password'] = "endpoint={$endpoint};{$password}";
        }

        return parent::connect($config);
    }
}
