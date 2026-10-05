<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils\Monad;

/**
 * ListMonad - Abstracts away the concept of a list of items.
 */
class ListMonad implements MonadInterface
{
    protected array|\Traversable $value;

    /**
     * __construct
     *
     * @param  array|\Traversable $value
     */
    public function __construct(array|\Traversable $value)
    {
        $this->value = $value;
    }

    /**
     * unit
     *
     * @param  mixed $value
     * @return ListMonad
     */
    public static function unit(mixed $value): self
    {
        return new ListMonad($value);
    }

    /**
     * bind
     *
     * @param  callable $f
     * @param  mixed ...$args
     * @return ListMonad
     */
    public function bind(callable $f, ...$args): self
    {
        $result = [];

        foreach ($this->value as $value) {
            if (
                $value instanceof IdentityMonad ||
                $value instanceof MaybeMonad    ||
                $value instanceof ListMonad
            ) {
                $result[] = $value->bind($f, ...$args);
            } else {
                $result[] = $f($value, ...$args);
            }
        }

        return new ListMonad($result);
    }

    /**
     * extract
     *
     * @return array
     */
    public function extract(): array
    {
        $result = [];

        foreach ($this->value as $value) {
            if (
                $value instanceof IdentityMonad ||
                $value instanceof MaybeMonad    ||
                $value instanceof ListMonad
            ) {
                $result[] = $value->extract();
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }
}
