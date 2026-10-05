<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils\Monad;

/**
 * EitherMonad - Represents a value of one of two possible types (Left or Right).
 * Right is the "success" case, Left is the "failure" case.
 */
class EitherMonad implements MonadInterface
{
    private bool $isRight;
    private mixed $value;

    private function __construct(bool $isRight, mixed $value)
    {
        $this->isRight = $isRight;
        $this->value = $value;
    }

    /**
     * right - Create a Right (success) instance.
     */
    public static function right(mixed $value): self
    {
        return new self(true, $value);
    }

    /**
     * left - Create a Left (failure) instance.
     */
    public static function left(mixed $value): self
    {
        return new self(false, $value);
    }

    /**
     * unit - Wrap a value in a Right.
     */
    public static function unit(mixed $value): self
    {
        return self::right($value);
    }

    /**
     * bind - Chain operations. Left short-circuits.
     */
    public function bind(callable $f, ...$args): self
    {
        if (!$this->isRight) {
            return $this;
        }

        $result = $f($this->value, ...$args);

        if ($result instanceof self) {
            return $result;
        }

        return self::right($result);
    }

    /**
     * extract - Extract the value.
     */
    public function extract(): mixed
    {
        return $this->value;
    }

    /**
     * isRight - Check if this is a Right.
     */
    public function isRight(): bool
    {
        return $this->isRight;
    }

    /**
     * isLeft - Check if this is a Left.
     */
    public function isLeft(): bool
    {
        return !$this->isRight;
    }

    /**
     * fold - Pattern match on Either.
     */
    public function fold(callable $ifLeft, callable $ifRight): mixed
    {
        return $this->isRight
            ? $ifRight($this->value)
            : $ifLeft($this->value);
    }

    /**
     * getOrElse - Get the value or a default if Left.
     */
    public function getOrElse(mixed $default): mixed
    {
        return $this->isRight ? $this->value : $default;
    }

    /**
     * map - Map over the Right value.
     */
    public function map(callable $f): self
    {
        if (!$this->isRight) {
            return $this;
        }
        return self::right($f($this->value));
    }

    /**
     * leftMap - Map over the Left value.
     */
    public function leftMap(callable $f): self
    {
        if ($this->isRight) {
            return $this;
        }
        return self::left($f($this->value));
    }
}
