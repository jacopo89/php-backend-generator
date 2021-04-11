<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\File;
use App\Model\UploadedBase64File;
use App\Repository\FileRepository;
use App\Service\Base64Uploader;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;

class FileNormalizer implements ContextAwareDenormalizerInterface, CacheableSupportsMethodInterface
{

    private ObjectNormalizer $normalizer;
    private Base64Uploader $uploader;
    private FileRepository $repository;

    public function __construct(ObjectNormalizer $normalizer, Base64Uploader $uploader, FileRepository $repository)
    {
        $this->normalizer = $normalizer;
        $this->uploader = $uploader;
        $this->repository = $repository;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }


    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return is_a($type, File::class, true);
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (isset($data["id"])) {
            $file  = $this->repository->find($data["id"]);
        } else {
            $base64file = $this->normalizer->denormalize($data, UploadedBase64File::class, $format, $context);
            $file = $this->uploader->fromBase64File($base64file);
        }

        return $file;
    }
}