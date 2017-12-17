<?php

$factorA = 16807;
$factorB = 48271;

$div = 2147483647;

$a = 591;
$b = 393;

function calc($x, $y) {
    $x = str_pad(decbin($x), 32, 0, STR_PAD_LEFT);
    $y = str_pad(decbin($y), 32, 0, STR_PAD_LEFT);

    if (substr($x, 16) == substr($y, 16)) {
        return true;
    }

    return false;
}

function partA($a, $b, $div, $factorA, $factorB)
{
    $iters = 40000000;

    $count = 0;
    for ($i = 0; $i < $iters; $i++) {
        $a = ($a * $factorA) % $div;
        $b = ($b * $factorB) % $div;

        if (calc($a, $b)) {
            $count++;
        }
    }

    return $count;
}

function partB($a, $b, $div, $factorA, $factorB)
{
    $iters = 5000000;

    $compA = 0;
    $compB = 0;
    $count = 0;
    $comps = 0;

    $fhA = fopen('15a.txt', 'w+');
    $fhB = fopen('15b.txt', 'w+');

    while ($compA < $iters || $compB < $iters) {
        $a = ($a * $factorA) % $div;
        $b = ($b * $factorB) % $div;

        if ($a % 4 == 0 && $compA < $iters) {
            fputs($fhA, $a . PHP_EOL);
            $compA++;
        }

        if ($b % 8 == 0 && $compB < $iters) {
            fputs($fhB, $b . PHP_EOL);
            $compB++;
        }
    }

    fclose($fhA);
    fclose($fhB);

    $fhA = fopen('15a.txt', 'r');
    $fhB = fopen('15b.txt', 'r');

    $count = 0;
    $comps = 0;
    while ($comps < $iters) {
        $a = (int)(trim(fgets($fhA)));
        $b = (int)(trim(fgets($fhB)));

        if (calc($a, $b)) {
            $count++;
        }
        $comps++;
    }

    fclose($fhA);
    fclose($fhB);

    return $count;
}

echo 'Part A: ' . partA($a, $b, $div, $factorA, $factorB) . PHP_EOL;
echo PHP_EOL;
echo 'Part B: ' . partB($a, $b, $div, $factorA, $factorB) . PHP_EOL;
