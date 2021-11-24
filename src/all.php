<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * all - Determines whether all elements of the array satisfies the predicate.
 *
 * @param  mixed $predicate
 * @return Closure
 */
function all(callable $predicate): \Closure
{
    return fn(array $arr) =>
        array_reduce($arr, fn(bool $prev, $x) => $prev && $predicate($x), true);
}