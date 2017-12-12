<?php

function calc($c) {
    $counts = $c;

    while ($counts['sw'] > 0 && $counts['ne'] > 0) {
        $counts['sw']--;
        $counts['ne']--;
    }

    while ($counts['se'] > 0 && $counts['nw'] > 0) {
        $counts['se']--;
        $COunts['nw']--;
    }

    while ($counts['n'] > 0 && $counts['s'] > 0) {
        $counts['s']--;
        $counts['n']--;
    }

    if($counts['n'] > 0 && $counts['se'] > 0) {
        while($counts['n'] > 0 && $counts['se'] > 0) {
            $counts['ne']++;
            $counts['se']--;
            $counts['n']--;
        }
    } elseif($counts['s'] > 0 && $counts['ne'] > 0) {
        while($counts['s'] > 0 && $counts['ne'] > 0) {
            $counts['se']++;
            $counts['ne']--;
            $counts['s']--;
        }
    } elseif($counts['n'] > 0 && $counts['sw'] > 0) {
        while($counts['n'] > 0 && $counts['sw'] > 0) {
            $counts['nw']++;
            $counts['sw']--;
            $counts['n']--;
        }
    } elseif($counts['s'] > 0 && $counts['nw'] > 0) {
        while($counts['s'] > 0 && $counts['nw'] > 0) {
            $counts['sw']++;
            $counts['nw']--;
            $counts['s']--;
        }
    }

    return $counts;
}

function partA()
{
    $contents = trim(file_get_contents('11-input.txt'));
    $inputs = explode(",", $contents);

    $counts = [ 'nw' => 0, 'ne' => 0, 'se' => 0, 'sw' => 0, 'n' => 0, 's' => 0 ];
    foreach ($inputs as $step) {
        $counts[$step]++;
    }

    while ($counts['sw'] > 0 && $counts['ne'] > 0) {
        $counts['sw']--;
        $counts['ne']--;
    }

    while ($counts['se'] > 0 && $counts['nw'] > 0) {
        $counts['se']--;
        $counts['nw']--;
    }

    while ($counts['n'] > 0 && $counts['s'] > 0) {
        $counts['s']--;
        $counts['n']--;
    }

    $counts = calc($counts);
    $total = array_sum($counts);

    echo 'Part A: ' . $total . PHP_EOL;
}

function partB()
{
    $contents = trim(file_get_contents('11-input.txt'));
    $inputs = explode(",", $contents);

    $max = 0;
    $counts = [ 'nw' => 0, 'ne' => 0, 'se' => 0, 'sw' => 0, 'n' => 0, 's' => 0 ];
    foreach ($inputs as $step) {
        $counts[$step]++;
        $c = calc($counts);
        $total = array_sum($c);
        if ($total > $max) {
            $max = $total;
        }
    }

    echo 'Part B: ' . $max . PHP_EOL;
}

partA();
echo PHP_EOL;
partB();
