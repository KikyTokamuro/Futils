<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils\Monad;

/**
 * TryMonad - Represents a computation that may either result in an exception (Failure)
 * or return a successfully computed value (Success).
 */
class TryMonad implements MonadInterface
{
    private bool $isSuccess;
    private mixed $value;
    private ?\Throwable $exception;

    private function __construct(bool $isSuccess, mixed $value, ?\Throwable $exception = null)
    {
        $this->isSuccess = $isSuccess;
        $this->value = $value;
        $this->exception = $exception;
    }

    /**
     * success - Create a Success instance.
     */
    public static function success(mixed $value): self
    {
        return new self(true, $value);
    }

    /**
     * failure - Create a Failure instance.
     */
    public static function failure(\Throwable $exception): self
    {
        return new self(false, null, $exception);
    }

    /**
     * of - Execute a callable and wrap the result.
     */
    public static function of(callable $f, ...$args): self
    {
        try {
            return self::success($f(...$args));
        } catch (\Throwable $e) {
            return self::failure($e);
        }
    }

    /**
     * unit - Wrap a value in a Success.
     */
    public static function unit(mixed $value): self
    {
        return self::success($value);
    }

    /**
     * bind - Chain operations. Failure short-circuits.
     */
    public function bind(callable $f, ...$args): self
    {
        if (!$this->isSuccess) {
            return $this;
        }

        try {
            $result = $f($this->value, ...$args);

            if ($result instanceof self) {
                return $result;
            }

            return self::success($result);
        } catch (\Throwable $e) {
            return self::failure($e);
        }
    }

    /**
     * extract - Extract the value.
     */
    public function extract(): mixed
    {
        return $this->value;
    }

    /**
     * isSuccess - Check if this is a Success.
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * isFailure - Check if this is a Failure.
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess;
    }

    /**
     * getException - Get the exception if Failure.
     */
    public function getException(): ?\Throwable
    {
        return $this->exception;
    }

    /**
     * fold - Pattern match on Try.
     */
    public function fold(callable $ifFailure, callable $ifSuccess): mixed
    {
        return $this->isSuccess
            ? $ifSuccess($this->value)
            : $ifFailure($this->exception);
    }

    /**
     * getOrElse - Get the value or a default if Failure.
     */
    public function getOrElse(mixed $default): mixed
    {
        return $this->isSuccess ? $this->value : $default;
    }

    /**
     * map - Map over the Success value.
     */
    public function map(callable $f): self
    {
        if (!$this->isSuccess) {
            return $this;
        }

        try {
            return self::success($f($this->value));
        } catch (\Throwable $e) {
            return self::failure($e);
        }
    }

    /**
     * recover - Recover from a Failure.
     */
    public function recover(callable $f): self
    {
        if ($this->isSuccess) {
            return $this;
        }

        try {
            return self::success($f($this->exception));
        } catch (\Throwable $e) {
            return self::failure($e);
        }
    }

    /**
     * toEither - Convert to EitherMonad.
     */
    public function toEither(): EitherMonad
    {
        if ($this->isSuccess) {
            return EitherMonad::right($this->value);
        }
        return EitherMonad::left($this->exception);
    }
}
