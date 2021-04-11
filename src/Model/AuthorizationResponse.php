<?php
declare(strict_types=1);

namespace App\Model;


class AuthorizationResponse
{
    private string $url;
    private string $state;

    public function __construct(string $url, string $state)
    {
        $this->url = $url;
        $this->state = $state;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getState(): string
    {
        return $this->state;
    }

}