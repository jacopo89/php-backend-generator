<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Listener;

use BackendGenerator\Bundle\BackendGeneratorBundle\Entity\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gaufrette\Filesystem;

class FileListener
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $pmsFilesystem)
    {
        $this->filesystem = $pmsFilesystem;
    }

    /**
     * @param File $file
     * @param LifecycleEventArgs $event
     *
     * Remove file from filesystem when deleting the related File record in the database
     */
    public function postRemove(File $file, LifecycleEventArgs $event): void
    {
        $this->filesystem->delete($file->getPath());
    }
}