<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * take - Get n first elements from array.
 *
 * @param  mixed $count
 * @return Closure
 */
function take(int $count): \Closure
{
    return fn(array $arr) => 
        array_slice($arr, 0, $count);
}