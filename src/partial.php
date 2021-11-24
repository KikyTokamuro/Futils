<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * partial - Create partial function.
 *
 * @param  mixed $f
 * @param  mixed $args
 * @return void
 */
function partial(callable $f, ...$args)
{
    $arity = (new \ReflectionFunction($f))
        ->getNumberOfRequiredParameters();

    return $args[$arity - 1] ?? false
        ? $f(...$args)
        : fn(...$rest) => partial($f, ...array_merge($args, $rest));
}