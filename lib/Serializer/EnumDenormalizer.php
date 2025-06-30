<?php

declare(strict_types=1);

namespace lib\Serializer;

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
        $reflection = new \ReflectionClass($type);
        return $reflection->isEnum();
    }

    public function denormalize($data, $class, $format = null, array $context = []): mixed
    {
        return $class::tryFrom($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*' => true,
//            'json' => true,
        ];
    }
}
