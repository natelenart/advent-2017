<?php

$contents = trim(file_get_contents('5-input.txt'));
$instructions = explode("\n", $contents);

$counter = 0;
$key = 0;
while (array_key_exists($key, $instructions)) {
    $counter++;
    $newKey = $key + (int)$instructions[$key];
    $instructions[$key]++;
    $key = $newKey;
}

echo 'Part A: ' . $counter . PHP_EOL;



$contents = trim(file_get_contents('5-input.txt'));
$instructions = explode("\n", $contents);

$counter = 0;
$key = 0;
while (array_key_exists($key, $instructions)) {
    $counter++;
    $offset = (int)$instructions[$key];
    $newKey = $key + $offset;
    if ($offset >= 3) {
        $instructions[$key]--;
    } else {
        $instructions[$key]++;
    }
    $key = $newKey;
}

echo 'Part B: ' . $counter . PHP_EOL;
