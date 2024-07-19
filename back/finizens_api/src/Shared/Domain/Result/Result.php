<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Result;

/**
 * @template T
 * @template K
 */
final class Result
{
    /** @var T */
    private $error;

    /** @var K */
    private $data;

    /**
     * @param T $error
     * @param K $data
     */
    private function __construct($error, $data)
    {
        $this->error = $error;
        $this->data = $data;
    }

    /** @return T */
    public function getError()
    {
        return $this->error;
    }

    /** @return K */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param T $error
     * @return Result<T, K>
     */
    public static function error($error): Result
    {
        return new self($error, null);
    }

    /**
     * @param K $data
     * @return Result<T, K>
     */
    public static function success($data): Result
    {
        return new self(null, $data);
    }

    public function isError(): bool
    {
        return $this->error !== null;
    }

    public function isSuccess(): bool
    {
        return $this->data !== null;
    }
}
