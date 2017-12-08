<?php

$contents = trim(file_get_contents('7-input.txt'));
$rows = explode("\n", $contents);

$s = [];

$map = [];
foreach ($rows as $i => $row) {
    $parts = preg_split("#\-\>#", $row);
    $base = trim(explode(" ", $parts[0])[0]);
    $supported = [];
    if (trim($parts[1])) {
        $supported = explode(",", preg_replace("# #", "", trim($parts[1])));
    }

    if (empty($supported)) {
        $s[] = $base;
    } else {
        $map[$base] = $supported;
        foreach ($supported as $tower) {
            $s[] = $tower;
        }
    }
}

foreach ($s as $tower) {
    if (isset($map[$tower])) {
        unset($map[$tower]);
    }
}

$keys = array_keys($map);

echo 'Part A: ' . array_pop($keys) . PHP_EOL;



$contents = trim(file_get_contents('7-input.txt'));
$rows = explode("\n", $contents);

$map = [];
foreach ($rows as $i => $row) {
    $parts = preg_split("#\-\>#", $row);
    $first = explode(" ", $parts[0]);
    $base = trim($first[0]);
    preg_match('#\((\d+)\)#', $first[1], $matches);
    $weight = (int)($matches[1]);
    $supported = [];
    if (trim($parts[1])) {
        $supported = explode(",", preg_replace("# #", "", trim($parts[1])));
    }

    $map[$base] = [
        'weight' => $weight,
        'supp' => $supported,
    ];
}

function calc($key, $map) {
    $m = $map[$key];

    if (empty($m['supp'])) {
        return $m['weight'];
    }

    $w = [];
    foreach ($m['supp'] as $s) {
        $w[] = calc($s, $map);
    }

    if (count($w) > 1) {
        $oops = false;
        $x = array_shift($w);
        foreach ($w as $y) {
            if ($x !== $y && !$oops) {
                $oops = true;
                if ($x > $y) {
                    echo '  ' . $key . ' -> ' . $x . '-' . $y . ' = ' . abs($x-$y) . PHP_EOL;
                } else {
                    echo '  ' . $key . ' -> ' . $y . '-' . $x . ' = ' . abs($x-$y) . PHP_EOL;
                }
            }
        }
        $w[] = $x;
    }

    return $m['weight'] + array_sum($w);
}

echo 'Part B:' . PHP_EOL;
calc('mwzaxaj', $map);
