<?php

$gmax = 0;

function parse(&$registers, &$instructions, $row) {
    $parts = explode(" ", $row);
    $reg1 = $parts[0];
    $op1  = $parts[1];
    $val1 = $parts[2];
    $reg2 = $parts[4];
    $op2  = $parts[5];
    $val2 = $parts[6];

    if (! isset($registers[$reg1])) {
        $registers[$reg1] = 0;
    }
    if (! isset($registers[$reg2])) {
        $registers[$reg2] = 0;
    }

    $instructions[] = [
        'reg1' => $reg1,
        'op1'  => $op1,
        'val1' => (int)($val1),
        'reg2' => $reg2,
        'op2'  => $op2,
        'val2' => (int)($val2),
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
    switch ($inst['op2']) {
    case '==':
        if ($registers[$inst['reg2']] == $inst['val2']) {
            perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
        }
        break;
    case '>=':
        if ($registers[$inst['reg2']] >= $inst['val2']) {
            perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
        }
        break;
    case '<=':
        if ($registers[$inst['reg2']] <= $inst['val2']) {
            perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
        }
        break;
    case '!=':
        if ($registers[$inst['reg2']] != $inst['val2']) {
            perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
        }
        break;
    case '>':
        if ($registers[$inst['reg2']] > $inst['val2']) {
            perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
        }
        break;
    case '<':
        if ($registers[$inst['reg2']] < $inst['val2']) {
            perform($registers, $gmax, $inst['reg1'], $inst['op1'], $inst['val1']);
        }
        break;
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
