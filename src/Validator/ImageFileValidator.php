<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Validator;


use BackendGenerator\Bundle\BackendGeneratorBundle\Entity\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ImageFileValidator extends ConstraintValidator
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ImageFile)
            throw new UnexpectedTypeException($constraint, ImageFile::class);

        if (null === $value)
            return;

        if (!$value instanceof File)
            throw new UnexpectedValueException($value, File::class);

        if (!$value->isImage()) {
            unlink(sprintf('%s/public%s', $this->projectPath, $value->getPath()));
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}