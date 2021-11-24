<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * drop - Drops the first n elements off the front of the array.
 *
 * @param  mixed $count
 * @return Closure
 */
function drop(int $count): \Closure
{
    return fn(array $arr) => 
        array_slice($arr, $count);
}