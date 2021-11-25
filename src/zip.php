<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * zip - Zips together its two arguments into a array of arrays.
 *
 * @param  mixed $arr
 * @return Closure
 */
function zip(array $arr): \Closure
{
    return function(array $arr2) use ($arr): array {
        $result = [];
        $len = count($arr2);

        for ($i = 0; $i < count($arr); $i++) {
            if ($i == $len) {
                break;
            }

            array_push($result, [$arr[$i], $arr2[$i]]);
        }

        return $result;
    };
}