<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

final class ValidationException extends RuntimeException
{
    /**
     * @var array<string,string>
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * @param string               $message
     * @param array<string,string> $errors
     * @param int                  $code
     * @param Throwable|null       $previous
     */
    public function __construct(
        string $message,
        array $errors = [],
        int $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return array<string,string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
