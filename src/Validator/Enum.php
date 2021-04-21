<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Enum extends Constraint
{
    public string $message = 'Invalid value {{ value }} for enum';
}