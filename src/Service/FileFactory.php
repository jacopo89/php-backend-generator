<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\Service;

use BackendGenerator\Bundle\Entity\File;
use BackendGenerator\Bundle\Model\FileToUpload;
use BackendGenerator\Bundle\Repository\FileRepository;
use BackendGenerator\Bundle\Service\File\Upload\FileUploadService;
use BackendGenerator\Bundle\Service\File\Upload\ImageUploadService;

class FileFactory
{
    private FileUploadService $fileUploadService;
    private FileRepository $fileRepository;

    public function __construct(FileUploadService $fileUploadService, FileRepository $fileRepository)
    {
        $this->fileUploadService = $fileUploadService;
        $this->fileRepository = $fileRepository;
    }

    public function create(FileToUpload $fileToUpload, string $title = null, string $description = null): File
    {
        $existingFile = $this->fileRepository->findOneByMd5($fileToUpload->getMd5());
        if($existingFile instanceof File)
            return $existingFile;

        $url = $this->fileUploadService->upload($fileToUpload);

        return File::createFromFileModel($fileToUpload, $url->getRelative(), $url->getAbsolute(), $title, $description);
    }

}