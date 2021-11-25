<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * tail - Get tail of array.
 *
 * @param  mixed $arr
 * @return array
 */
function tail(array $arr): array
{
    return array_slice($arr, 1);
}