<?php

$contents = file_get_contents('4-input.txt');
$rows = explode("\n", trim($contents));

$valid = 0;
foreach ($rows as $row) {
    $words = explode(" ", $row);
    $w = [];
    $invalid = false;
    foreach ($words as $word) {
        if (!isset($w[$word])) {
            $w[$word] = true;
            continue;
        }
        $invalid = true;
    }

    if (! $invalid) {
        $valid++;
    }
}

echo 'Part A: ' . $valid . PHP_EOL;



$valid = 0;
foreach ($rows as $row) {
    $words = explode(" ", $row);

    foreach ($words as $idx => &$word) {
        $letters = array_filter(preg_split('##', trim($word)));
        sort($letters);
        $words[$idx] = implode('', $letters);
    }
    unset($word);

    $w = [];
    $invalid = false;
    foreach ($words as $word) {
        if (!isset($w[$word])) {
            $w[$word] = true;
            continue;
        }
        $invalid = true;
    }

    if (! $invalid) {
        $valid++;
    }
}

echo 'Part B: ' . $valid . PHP_EOL;
