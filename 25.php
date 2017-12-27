<?php

$g = [];
$curr = 0;
$state = 'A';

$steps = 12629077;

$step = 0;
while ($step < $steps) {
    if (! isset($g[$curr])) {
        $g[$curr] = 0;
    }

    switch ($state) {
    case 'A':
        if ($g[$curr] == 0) {
            $g[$curr] = 1;
            $curr++;
            $state = 'B';
        } else {
            $g[$curr] = 0;
            $curr--;
            $state = 'B';
        }
        break;
    case 'B':
        if ($g[$curr] == 0) {
            $g[$curr] = 0;
            $curr++;
            $state = 'C';
        } else {
            $g[$curr] = 1;
            $curr--;
            $state = 'B';
        }
        break;
    case 'C':
        if ($g[$curr] == 0) {
            $g[$curr] = 1;
            $curr++;
            $state = 'D';
        } else {
            $g[$curr] = 0;
            $curr--;
            $state = 'A';
        }
        break;
    case 'D':
        if ($g[$curr] == 0) {
            $g[$curr] = 1;
            $curr--;
            $state = 'E';
        } else {
            $g[$curr] = 1;
            $curr--;
            $state = 'F';
        }
        break;
    case 'E':
        if ($g[$curr] == 0) {
            $g[$curr] = 1;
            $curr--;
            $state = 'A';
        } else {
            $g[$curr] = 0;
            $curr--;
            $state = 'D';
        }
        break;
    case 'F':
        if ($g[$curr] == 0) {
            $g[$curr] = 1;
            $curr++;
            $state = 'A';
        } else {
            $g[$curr] = 1;
            $curr--;
            $state = 'E';
        }
        break;
    }

    $step++;

    if ($step % 1000 == 0) {
        echo ' > Step ' . $step . PHP_EOL;
    }
}

echo 'Part A: ' . array_sum($g) . PHP_EOL;
