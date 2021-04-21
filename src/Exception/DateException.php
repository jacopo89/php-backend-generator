<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Exception;

class DateException extends \Exception
{
    public static function invalidDates(string $message): self
    {
        return new self(sprintf("Invalid dates. %s", $message));
    }

}