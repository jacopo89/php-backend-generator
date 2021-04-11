<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class UnitLetterOutOfRangeException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Room Letter Unavailable", $code, $previous);
    }
}