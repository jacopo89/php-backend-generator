<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Model\Enum\EnumInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EnumNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{

    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new $type($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, EnumInterface::class, true);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        return $object->getValue();
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof EnumInterface;
    }
}