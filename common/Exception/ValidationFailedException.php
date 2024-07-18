<?php

declare(strict_types=1);


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationFailedException extends ApplicationException
{
    public function __construct(private readonly ConstraintViolationListInterface $violations)
    {
        parent::__construct($this->getJson(), code: 422);
    }

    public function getContext(array $context = []): array
    {
        $errors = array_map(
            static fn (ConstraintViolationInterface $violation) => [
                'message' => $violation->getMessage(),
                'propertyPath' => $violation->getPropertyPath(),
            ],
            iterator_to_array($this->violations),
        );

        return ['errors' => $errors];
    }

    public function getJson(): string
    {
        return (string) json_encode($this->getContext());
    }
}
