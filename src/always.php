<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * always - Creates a function that always returns a given value
 *
 * @param  mixed $value
 * @return Closure
 */
function always($value): \Closure
{  
    return fn() => $value;
}