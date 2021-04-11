<?php
declare(strict_types=1);

namespace App\Model;

class Url
{
    const AUTH = '/authentication_token';
    const REFRESH_TOKEN = '/token/refresh';
    const RESOURCE_LISTING = '/api/resources/listing/{resource}';

    const AUTH_PATTERN = '^' . self::AUTH;
    const REFRESH_TOKEN_PATTERN = '^' . self::REFRESH_TOKEN;
}