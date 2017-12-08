<?php

function calc($input) {
    if ($input == 1) {
        return 0;
    }

    $loop = 1;
    $power = 3;
    while ($input > $power * $power) {
        $loop += 1;
        $power += 2;
    }

    $tmp  = $power * $power;
    $mod  = $power - 1;
    $half = ($power-1)/2;
    $walk = 0;
    while (true) {
        if ($input == $tmp) {
            return $mod;
        }

        if ($walk < $half) {
            $mod--;
        } else {
            $mod++;
        }

        $walk++;
        if ($walk == $power-1) {
            $walk = 0;
        }

        $tmp--;
    }
}

$input = 289326;
$answer = calc($input);

echo "Part A: $input -> $answer" . PHP_EOL;



$grid = [ 0 => [ 0 => [ 'id' => 1, 'val' => 1 ] ] ];

$x = 1;
$y = 0;

$power = 3;
$ups = 1;
$lefts = 2;
$downs = 2;
$rights = 3;

for ($num = 2; $num <= 100; $num++) {
    if (! isset($grid[$x])) {
        $grid[$x] = [];
    }

    $value = 0;
    for ($i = $x-1; $i <= $x+1; $i++) {
        for ($j = $y-1; $j <= $y+1; $j++) {
            if ($i == $x && $j == $y) {
                continue;
            }
            if (isset($grid[$i][$j])) {
                $value += $grid[$i][$j]['val'];
            }
        }
    }

    if ($value > 289326) {
        echo "Part B: $num -> $value" . PHP_EOL;
        die;
    }

    $grid[$x][$y] = [ 'id' => $num, 'val' => $value ];

    if ($ups > 0) {
        $ups--;
        $y--;
    } elseif ($lefts > 0) {
        $lefts--;
        $x--;
    } elseif ($downs > 0) {
        $downs--;
        $y++;
    } elseif ($rights > 0) {
        $rights--;
        $x++;
    } else {
        $power  += 2;
        $ups    = $power-2;
        $lefts  = $power-1;
        $downs  = $power-1;
        $rights = $power;
    }
}
