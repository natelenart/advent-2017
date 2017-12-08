<?php

$input = trim(file_get_contents('2-input.txt'));

$rows = explode("\n", $input);

$total = 0;
foreach ($rows as $row) {
    $columns = explode("\t", $row);
    $total += max($columns) - min($columns);
}

echo 'Part A: ' . $total . PHP_EOL;



$total = 0;
foreach ($rows as $row) {
    $columns = explode("\t", $row);
    sort($columns);
    $columns = array_reverse($columns);
    for ($i = 0; $i < count($columns); $i++) {
        $a = (int)($columns[$i]);
        for ($j = $i+1; $j < count($columns); $j++) {
            $b = (int)($columns[$j]);
            if ($a % $b == 0) {
                $total += ($a / $b);
                break 2;
            }
        }
    }
}

echo 'Part B: ' . $total . PHP_EOL;
