<?php

declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class EnumDenormalizer implements DenormalizerInterface
{
    /**
     * @param mixed $data
     * @param mixed $type
     * @param mixed|null $format
     *
     * @throws \ReflectionException
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return (new \ReflectionClass($type))->isEnum();
    }

    public function denormalize($data, $type, $format = null, array $context = []): mixed
    {
        return $type::tryFrom($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}
