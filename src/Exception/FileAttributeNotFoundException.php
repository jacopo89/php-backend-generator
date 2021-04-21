<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Exception;

class FileAttributeNotFoundException extends \Exception
{
    public static function invalidAttribute(string $attribute, string $class): self
    {
        return new self('Attribute ' . $attribute . ' not found in class '.$class);
    }
}