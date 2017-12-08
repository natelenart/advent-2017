<?php

function hashBank($bank) {
    return implode('#', $bank);
}

$initial = [
    4, 1, 15, 12, 0, 9, 9, 5, 5, 8, 7, 3, 14, 5, 12, 3,
];

$hashKey = hashBank($initial);
$sum = array_sum($initial);

$configs = [];

$counter = 0;
$bank = $initial; $loop = true;
do {
    $counter += 1;
    $configs[hashBank($bank)] = true;

    $max = [];
    foreach ($bank as $i => $val) {
        if (! isset($max[1]) || $val > $max[1]) {
            $max = [ $i, $val ];
        }
    }

    $idx = $max[0];

    $bank[$idx] = 0;

    for ($v = $max[1]; $v > 0; $v--) {
        $idx = $idx + 1;
        if (!isset($bank[$idx])) {
            $idx = 0;
        }

        $bank[$idx] += 1;
    }

    assert($sum == array_sum($bank));

    $key = hashBank($bank);
    if (array_key_exists($key, $configs)) {
        $loop = false;
    }
} while($loop);

echo "Part A: $counter" . PHP_EOL;



$c = array_reverse(array_keys($configs));
$counter = 0;
foreach ($c as $a) {
    $counter++;
    if ($key === $a) {
        break;
    }
}

echo "Part B: $counter" . PHP_EOL;
