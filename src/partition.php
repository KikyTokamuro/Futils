<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * partition - Equivalent to [(filter f, arr), (reject f, arr)].
 *
 * @param  mixed $f
 * @return Closure
 */
function partition(callable $f): \Closure
{
    return fn(array $arr) => 
        [filter($f)($arr), reject($f)($arr)];
}