<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Repository;

interface ResourceRepositoryInterface
{
    public static function getResourceName(): string;
    public function deleteFileByAttribute(int $id, string $attribute, int $fileId): void;
}