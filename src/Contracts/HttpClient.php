<?php

namespace Trigold\Http\Contracts;

interface HttpClient
{
    const GUZZLE = 'guzzle';

    const CURL = 'curl';

    public function __construct(string $baseUri = '', int $timeout = 2);
    public function get(string $uri, array $data);
    public function post(string $uri, array $data);
    public function put(string $uri, array $data);
    public function delete(string $uri, array $data);
}