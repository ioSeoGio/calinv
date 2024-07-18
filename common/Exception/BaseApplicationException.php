<?php

declare(strict_types=1);


class BaseApplicationException extends \Exception
{
    public function __construct(
        string $message,
        \Throwable $previous = null,
        int $code = 400,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
