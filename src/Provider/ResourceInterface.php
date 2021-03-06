<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Provider;


interface ResourceInterface
{
    public static function getResourceName(): string;

    public static function getResourceTitle(): string;

    public static function getDefaultOptionText() :string;
}