<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PdfFile extends Constraint
{
    public string $message = 'This file is not a PDF';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}