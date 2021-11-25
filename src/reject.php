<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * reject - Like filter, but the new array is composed of all the items which fail the function.
 *
 * @param  mixed $f
 * @return Closure
 */
function reject(callable $f): \Closure
{
    return fn(array $arr, int $mode = 0) => 
        array_filter($arr, fn($x) => !$f($x), $mode);
}