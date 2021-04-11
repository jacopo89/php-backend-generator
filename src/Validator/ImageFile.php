<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ImageFile extends Constraint
{
    public string $message = 'This file is not an image';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}