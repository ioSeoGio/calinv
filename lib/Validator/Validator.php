<?php

declare(strict_types=1);

namespace lib\Validator;

use lib\Exception\UserException\ApiBadRequestException;
use lib\Exception\UserException\ApiBadResponseException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class Validator
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function validate(
        mixed $value,
        Constraint|array|null $constraints = null,
        string|GroupSequence|array|null $groups = null,
    ): void {
        $violations = $this->validator->validate($value, $constraints, $groups);
        if (count($violations) > 0) {
            throw new ApiBadResponseException($violations);
        }
    }

    public function isValid(object $object): bool
    {
        $violations = $this->validator->validate($object);

        return count($violations) === 0;
    }
}
