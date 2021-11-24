<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * any - Determines whether any element of the array satisfies the predicate.
 *
 * @param  mixed $predicate
 * @return Closure
 */
function any(callable $predicate): \Closure
{
    return fn(array $arr) => 
        array_reduce($arr, fn(bool $prev, $x) => $prev ?: $predicate($x), false);
}