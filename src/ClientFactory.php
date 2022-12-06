<?php

namespace Trigold\Http;

use Exception;
use Trigold\Http\Contracts\HttpClient;

class ClientFactory
{
    const GUZZLE = 'guzzle';

    /**
     * @param  array   $config
     * @param  string  $driver
     *
     * @return HttpClient
     * @throws Exception
     */
    public static function create( array $config = [],string $driver = 'guzzle'): HttpClient
    {
        $driver = ucfirst($driver);
        $class = "\\Trigold\\Http\\Client\\{$driver}";
        if (!class_exists($class)) {
            throw new \Exception("{$class} not found");
        }
        return new $class($config['base_uri'] ?? '', $config['timeout'] ?? 2, $config['options'] ?? []);
    }
}