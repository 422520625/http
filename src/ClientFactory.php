<?php

namespace Trigold\Http;

use Exception;
use Trigold\Http\Contracts\HttpClient;

class ClientFactory
{
    /**
     * @throws Exception
     */
    public static function create(string $driver = 'guzzle', array $config = []): HttpClient
    {
        $driver = ucfirst($driver);
        $class = "\\Trigold\\Http\\Client\\{$driver}";
        if (!class_exists($class)) {
            throw new \Exception("{$class} not found");
        }
        return new $class($config['base_uri'] ?? '', $config['timeout'] ?? 2, $config['options'] ?? []);
    }
}