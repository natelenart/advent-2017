<?php

$gmax = 0;

function parse(&$registers, &$instructions, $row) {
    $parts = explode(" ", $row);

    if (! isset($registers[$reg1])) {
        $registers[$parts[0]] = 0;
    }
    if (! isset($registers[$reg2])) {
        $registers[$parts[4]] = 0;
    }

    $instructions[] = [
        'reg1' => $parts[0],
        'op1'  => $parts[1],
        'val1' => (int)($parts[2]),
        'reg2' => $parts[4],
        'op2'  => $parts[5],
        'val2' => (int)($parts[6]),
    ];
}

function perform(&$registers, &$gmax, $reg, $op, $val) {
    switch ($op) {
    case 'inc':
        $registers[$reg] += $val;
        break;
    case 'dec':
        $registers[$reg] -= $val;
        break;
    }

    // Part B
    if ($registers[$reg] > $gmax) {
        $gmax = $registers[$reg];
    }
}

$registers = [];
$instructions = [];

$contents = trim(file_get_contents("8-input.txt"));
$rows = explode("\n", $contents);

foreach ($rows as $row) {
    parse($registers, $instructions, $row);
}

foreach ($instructions as $inst) {
    $stmt = 'return ' . $registers[$inst['reg2']] . ' ' . $inst['op2'] . ' ' . $inst['val2'] . ';';
    if (eval($stmt)) {
        perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
    }
}

$max = 0;
foreach ($registers as $reg => $val) {
    if ($val > $max) {
        $max = $val;
    }
}

echo 'Part A: ' . $max . PHP_EOL;
echo 'Part B: ' . $gmax . PHP_EOL;
