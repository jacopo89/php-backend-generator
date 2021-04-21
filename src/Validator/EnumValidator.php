<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\Validator;


use BackendGenerator\Bundle\Model\Enum\EnumInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EnumValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Enum)
            throw new UnexpectedTypeException($constraint, Enum::class);

        if (null === $value)
            return;

        if (!$value instanceof EnumInterface)
            throw new UnexpectedValueException($value, EnumInterface::class);

        if (!$value->isValid()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', (string)$value->getValue())
                ->addViolation();
        }
    }
}