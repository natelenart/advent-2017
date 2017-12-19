<?php

$input = 394;

$buffer = [0];
$pos = 0;

function calc($cnt, $pos, $offset)
{
    $pos += $offset;

    return $pos % $cnt;
}

$iter = 1;
do {
    $pos = calc(count($buffer), $pos, $input)+1;
    $rem = array_splice($buffer, $pos);
    $buffer = array_merge($buffer, [$iter], $rem);
    $iter++;
} while ($iter <= 2017);

$key = array_search(2017, $buffer);
echo 'Part A: ' . $buffer[$key+1] . PHP_EOL;

// ---------

$input = 394;

$buffer = [ 0, null ];
$pos = 0;

$iter = 1;
do {
    $pos = calc($iter, $pos, $input)+1;
    if ($pos == 1) {
        $buffer[$pos] = $iter;
    }
    $iter++;
    if ($iter % 10000 == 0) {
        echo ' > ' . number_format($iter) . PHP_EOL;
    }
} while ($iter <= 50000000);

echo 'Part B: ' . $buffer[1] . PHP_EOL;
