<?php

function tick(&$particles)
{
    foreach ($particles as $i => $part)
    {
        // vel += acc
        $part[1][0] += $part[2][0];
        $part[1][1] += $part[2][1];
        $part[1][2] += $part[2][2];

        // pos += vel
        $part[0][0] += $part[1][0];
        $part[0][1] += $part[1][1];
        $part[0][2] += $part[1][2];

        $particles[$i] = $part;
    }
}

function findMinIdx(&$particles)
{
    $min = null;
    $idx = 0;
    foreach ($particles as $i => $part) {
        $m = manhattan($part[0]);
        if ($min == null || $m < $min) {
            $idx = $i;
            $min = $m;
        }

    }

    return $idx;
}

function manhattan($val)
{
    return abs($val[0]) + abs($val[1]) + abs($val[2]);
}

function partA($particles)
{
    $num_same = 0;
    $global_min_idx = null;
    while (true) {
        tick($particles);

        $idx = findMinIdx($particles); 

        if ($idx == $global_min_idx) {
            $num_same++;
        } else {
            $num_same = 0;
            $global_min_idx = $idx;
        }

        if ($num_same > 1000) {
            echo 'Part A: ' . $global_min_idx . PHP_EOL;
            return;
        }
    }
}

function partB($particles)
{
    $num_same = 0;
    $particle_count = null;
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

        if (count($particles) == $particle_count) {
            $num_same++;
        } else {
            $num_same = 0;
            $particle_count = count($particles);
        }

        if ($num_same > 1000) {
            echo 'Part B: ' . count($particles) . PHP_EOL;
            return;
        }
    }
}

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

$p1 = $particles;
$p2 = $particles;
unset($particles);

partA($p1);
partB($p2);
