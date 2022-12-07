<?php

namespace Trigold\Http\Client;

use GuzzleHttp\Psr7\Request;
use Trigold\Http\Contracts\HttpClient;
use const sdk\cdiscount\tokenUrl;

class Curl implements HttpClient
{
    protected array $headers = [];
    protected string $baseUri;
    protected int $timeout;
    protected array $options;
    private array $headerNames;

    public function __construct(string $baseUri = '', int $timeout = 2, array $options = [])
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
        $this->options = $options;
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];
    }

    public function get(string $uri, array $data): array
    {
        return $this->request('GET', $uri, $data);
    }

    public function post(string $uri, array $data): array
    {
        return $this->request('POST', $uri, $data);
    }

    public function put(string $uri, array $data): array
    {
        return $this->request('PUT', $uri, $data);
    }

    public function delete(string $uri, array $data): array
    {
        return $this->request('DELETE', $uri, $data);
    }

    protected function request(string $method, $uri = '', array $options = []): array
    {

        if (!empty($options['headers'])) {
            $headers = $options['headers'];
            $this->assertHeader($headers);
            $this->headers = array_merge($this->headers, $headers);
        }

        $body = $options['body'] ?? null;

        unset($options['headers'], $options['body']);


        $http_build_query = http_build_query($options);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_URL, $this->baseUri.$uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeader());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_COOKIEFILE, '');

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $http_build_query);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $http_build_query);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $http_build_query);
                break;
        }
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        return [
            'header'   => substr($response, 0, $headerSize) ?? '',
            'body'     => substr($response, $headerSize) ?? '',
            'httpCode' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
    }

    protected function assertHeader($header): void
    {
        if (!is_string($header)) {
            throw new \InvalidArgumentException(sprintf(
                'Header name must be a string but %s provided.',
                is_object($header) ? get_class($header) : gettype($header)
            ));
        }

        if (!preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/', $header)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"%s" is not valid header name',
                    $header
                )
            );
        }
    }

    protected function buildHeader(): array
    {
        $headers = [];
        foreach ($this->headers as $name => $header) {
            $headers[] = "$name:$header";
        }
        return $headers;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function withHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }
}