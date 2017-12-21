<?php

$contents = trim(file_get_contents('20-input.txt'));
$rows = explode("\n", $contents);

$particles = [];
foreach ($rows as $row) {
    $parts = explode(", ", $row);
    $p = explode(',', substr(substr(explode("=", $parts[0])[1], 1), 0, -1));
    $v = explode(',', substr(substr(explode("=", $parts[1])[1], 1), 0, -1));
    $a = explode(',', substr(substr(explode("=", $parts[2])[1], 1), 0, -1));
    $particles[] = [ $p, $v, $a ];
}

function tick(&$particles)
{
    foreach ($particles as $i => $part)
    {
        // +vel
        $part[1][0] += $part[2][0];
        $part[1][1] += $part[2][1];
        $part[1][2] += $part[2][2];

        // +pos
        $part[0][0] += $part[1][0];
        $part[0][1] += $part[1][1];
        $part[0][2] += $part[1][2];

        $particles[$i] = $part;
    }
}

function calc(&$particles)
{
    $min = null;
    $idx = 0;
    foreach ($particles as $i => $part) {
        $m = manhattan($part[0]);
        if ($m < $min || $min == null) {
            $idx = $i;
            $min = $m;
        }

    }

    return [ $idx, $min ];
}

function manhattan($val)
{
    return abs($val[0]) + abs($val[1]) + abs($val[2]);
}

// Part A
//while (true) {
//    tick($particles);
//    list($idx, $min) = calc($particles); 
//    echo $idx . PHP_EOL;
//}

while (true) {
    tick($particles);

    $colls = [];
    foreach ($particles as $i => $part) {
        $key = $part[0][0] . ',' . $part[0][1] . ',' . $part[0][2];
        if (! isset($colls[$key])) {
            $colls[$key] = [];
        }
        $colls[$key][] = $i;
    }

    foreach ($colls as $key => $coll) {
        if (count($coll) > 1) {
            foreach ($coll as $i) {
                unset($particles[$i]);
            }
        }
    }

    echo count($particles) . PHP_EOL;
}
