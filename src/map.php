<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * map - Applying f to each element of array
 *
 * @param  mixed $f
 * @return Closure
 */
function map(callable $f): \Closure
{
    return fn(array $arr, array ...$rest) =>
        array_map($f, $arr, $rest);
}