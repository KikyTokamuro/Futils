<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * pipe - Function composition (f1 -> f2 -> ... -> fn)
 *
 * @param  mixed $fns
 * @return Closure
 */
function pipe(callable ...$fns): \Closure
{
    return function($payload = null, ...$rest) use ($fns) {
        $left = fn($payload) => head($fns)(...array_merge([$payload], $rest));
        
        return array_reduce($rest === [] ? $fns : array_merge([$left], tail($fns)), 
            fn($payload, callable $f) => $f($payload), $payload);
    };
}