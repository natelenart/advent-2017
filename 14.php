<?php

function getPos($cnt, $idx) {
    $last = $cnt - 1;

    while ($idx > $last) {
        $idx -= $cnt;
    }

    return $idx;
}

function calc($length)
{
    $key = 'ffayrhll-' . (string)($length);

    $current = 0;
    $list = range(0, 255);
    $l = count($list);
    $skip = 0;

    $lengths = [];
    for ($i = 0; $i < strlen($key); $i++) {
        $lengths[] = ord($key[$i]);
    }

    $lengths = array_merge($lengths, [ 17, 31, 73, 47, 23 ]);

    for ($rounds = 0; $rounds < 64; $rounds++) {
        foreach ($lengths as $length) {
            $idx = getPos($l, $current);
            $arr = array_slice($list, $idx, $length);
            if (! array_key_exists($idx+$length, $list)) {
                $arr = array_merge($arr,
                    array_slice($list, 0, $length-count($arr)));
            }
            $arr = array_reverse($arr);

            if (count($arr) != $length) {
                echo 'ERROR: count of subset does not match length' . PHP_EOL;
                die;
            }

            foreach ($arr as $i => $item) {
                $idx = getPos($l, $current+$i);
                if (! array_key_exists($idx, $list)) {
                    echo 'ERROR: index ' . $idx . ' does not already exist' . PHP_EOL;
                    die;
                }
                $list[$idx] = $item;
            }

            $current = getPos($l, $current + $length + $skip);
            $skip += 1;
        }
    }

    $out = "";
    $chunks = array_chunk($list, 16);
    foreach ($chunks as $chunk) {
        $str = implode(' ^ ', $chunk);
        $xor = eval('return ' . $str . ';');
        $hex = str_pad(dechex($xor), 2, 0, STR_PAD_LEFT);
        $out .= $hex;
    }

    return $out;
}

$grid = [];
for ($i = 0; $i < 128; $i++) {
    $grid[] = calc($i);
}

$count = 0;
$g = [];
foreach ($grid as $row) {
    $bits = "";
    for ($i = 0; $i < strlen($row); $i++) {
        $bits .= str_pad((decbin(hexdec($row[$i]))), 4, 0, STR_PAD_LEFT);
    }

    $r = [];
    for ($i = 0; $i < strlen($bits); $i++) {
        if ($bits[$i] == 1) {
            $r[] = '#';
            $count++;
        } else {
            $r[] = '.';
        }
    }
    $g[] = $r;
}

echo 'Part A: ' . $count . PHP_EOL;

function recurse(&$g, &$region, $x, $y) {
    if ($g[$x][$y] != '#') {
        return;
    }

    // left
    if (isset($g[$x][$y-1]) && is_numeric($g[$x][$y-1])) {
        $g[$x][$y] = $g[$x][$y-1];

    // right
    } elseif (isset($g[$x][$y+1]) && is_numeric($g[$x][$y+1])) {
        $g[$x][$y] = $g[$x][$y+1];

    // up
    } elseif (isset($g[$x-1][$y]) && is_numeric($g[$x-1][$y])) {
        $g[$x][$y] = $g[$x-1][$y];

    // down
    } elseif (isset($g[$x+1][$y]) && is_numeric($g[$x+1][$y])) {
        $g[$x][$y] = $g[$x+1][$y];
    }

    $new = false;
    if ($g[$x][$y] == '#') {
        $new = true;
        $g[$x][$y] = $region;
    }

    recurse($g, $region, $x, $y-1);
    recurse($g, $region, $x, $y+1);
    recurse($g, $region, $x-1, $y);
    recurse($g, $region, $x+1, $y);

    if ($new) {
        $region++;
    }
}

$region = 1;
for ($x = 0; $x < 128; $x++) {
    for ($y = 0; $y < 128; $y++) {
        recurse($g, $region, $x, $y);
    }
}

echo 'Part B: ' . ($region-1) . PHP_EOL;
