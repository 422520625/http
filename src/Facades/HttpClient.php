<?php

namespace Trigold\Http\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $url = '', array $data = [])
 * @method static mixed post(string $url, array $data = [])
 * @method static mixed put(string $url, array $data = [])
 * @method static mixed delete(string $url, array $data = [])
 * method static string upload(string $url, array $data = [])
 */
class HttpClient extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'http.client';
    }
}
