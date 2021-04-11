<?php
declare(strict_types=1);

namespace App\Exception;


class EnumException extends \Exception
{
    public static function invalidValue(string $class, int $value): self
    {
        return new self ("Invalid enum value $value for $class");
    }

    public static function invalidStringValue(string $class, string $value): self
    {
        return new self ("Invalid enum value $value for $class");
    }

    public static function invalidXeroStatus(string $class, string $status): self
    {
        return new self ("Invalid status value $status for $class");
    }
}