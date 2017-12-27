<?php

function calc($bridge)
{
    $sum = 0;
    foreach ($bridge as $pipe) {
        $sum += $pipe[0] + $pipe[1];
    }
    return $sum;
}

function hasher($pipes)
{
    $parts = [];
    foreach ($pipes as $pipe) {
        $parts[] = implode('/', $pipe);
    }
    return implode(';', $parts);
}

function recurse(&$cache, $bridge, $pipes, $last)
{
    if (count($pipes) == 0) {
        return 0;
    }

    $max = 0;

    foreach ($pipes as $p) {
        if ($p[0] == $last || $p[1] == $last) {
            $last2 = $p[0] == $last ? $p[1] : $p[0];
            $p2 = $pipes;

            $key = null;
            foreach ($p2 as $i => $pipe) {
                if ($pipe == $p) {
                    $key = $i;
                    break;
                }
            }
            if ($key !== null) {
                unset($p2[$key]);
            }

            $bridge[] = $p;

            $sum = calc([$p]) + recurse($cache, $bridge, $p2, $last2);

            if ($sum > $max) {
                $max = $sum;
            }

            $h = hasher($bridge);
            $cache[$h] = true;

            array_pop($bridge);
        }
    }

    return $max;
}

$contents = trim(file_get_contents('24-input.txt'));
$rows = explode("\n", $contents);

$pipes = [];
foreach ($rows as $row) {
    $row = explode('/', $row);
    $pipes[] = [ (int)min($row), (int)max($row) ];
}

$cache = [];
$bridge = [];

$max = recurse($cache, $bridge, $pipes, 0);

echo 'Part A: ' . $max . PHP_EOL;


// =========== //


$glen = 0;
$gmax = 0;
foreach ($cache as $key => $val) {
    $bridge = [];
    $parts = explode(';', $key);
    foreach ($parts as $part) {
        $parts = explode('/', $part);
        $bridge[] = [ (int)$parts[0], (int)$parts[1] ];
    }
    if (count($bridge) > $glen) {
        $glen = count($bridge);
        $gmax = calc($bridge);
    } elseif (count($bridge) == $glen) {
        $max = calc($bridge);
        if ($max > $gmax) {
            $gmax = $max;
        }
    }
}

echo 'Part B: ' . $gmax . PHP_EOL;
