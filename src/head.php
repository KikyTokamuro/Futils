<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * head - Get head of array.
 *
 * @param  mixed $arr
 * @return void
 */
function head(array $arr)
{
    if (count($arr) == 0) {
        return null;
    }

    return $arr[0];
}