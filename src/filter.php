<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * filter - Filters elements of an array using a callback function f.
 *
 * @param  mixed $f
 * @return Closure
 */
function filter(callable $f): \Closure
{
    return fn(array $arr, int $mode = 0) => 
        array_filter($arr, $f, $mode);
}