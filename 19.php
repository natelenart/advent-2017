<?php

$contents = trim(file_get_contents('19-input.txt'), "\n");
$rows = explode("\n", $contents);
$grid = [];
foreach ($rows as $y => $row) {
    $r = [];
    $cols = str_split($row);
    foreach ($cols as $x => $col) {
        if ($y == 0 && $col == '|') {
            $start = $x;
        }
        $r[] = $col;
    }
    $grid[] = $r;
}

function is_valid(&$grid, &$x, &$y)
{
    return ($grid[$y][$x] == '|'
        || $grid[$y][$x] == '-'
        || $grid[$y][$x] == '+'
        || preg_match('#[A-Z]#', $grid[$y][$x])
    );
}

function traverse(&$grid, &$letters, &$x, &$y, &$dir)
{
    if ($grid[$y][$x] == '|') {
        if ($dir == 'l') {
            $x--;
        } elseif ($dir == 'r') {
            $x++;
        } elseif ($dir == 'u') {
            $y--;
        } elseif ($dir == 'd') {
            $y++;
        } else {
            var_dump('invalid1'); die();
        }
    } elseif ($grid[$y][$x] == '-') {
        if ($dir == 'l') {
            $x--;
        } elseif ($dir == 'r') {
            $x++;
        } elseif ($dir == 'u') {
            $y--;
        } elseif ($dir == 'd') {
            $y++;
        } else {
            var_dump('invalid2'); die();
        }
    } elseif ($grid[$y][$x] == '+') {
        if ($dir != 'l' &&
            ($grid[$y][$x+1] == '|'
            || $grid[$y][$x+1] == '-'
            || preg_match('#[A-Z]#', $grid[$y][$x+1]))
        ) {
            $dir = 'r';
            $x++;
        } elseif ($dir != 'r' &&
            ($grid[$y][$x-1] == '|'
            || $grid[$y][$x-1] == '-'
            || preg_match('#[A-Z]#', $grid[$y][$x-1]))
        ) {
            $dir = 'l';
            $x--;
        } elseif ($dir != 'u' && 
            ($grid[$y+1][$x] == '|'
            || $grid[$y+1][$x] == '-'
            || preg_match('#[A-Z]#', $grid[$y+1][$x]))
        ) {
            $dir = 'd';
            $y++;
        } elseif ($dir != 'd' && 
            ($grid[$y-1][$x] == '|'
            || $grid[$y-1][$x] == '-'
            || preg_match('#[A-Z]#', $grid[$y-1][$x]))
        ) {
            $dir = 'u';
            $y--;
        } else {
            var_dump('invalid3');die;
        }
    } elseif (preg_match("#[A-Z]#", $grid[$y][$x])) {
        $letters .= $grid[$y][$x];
        if ($dir == 'l') {
            $x--;
        } elseif ($dir == 'r') {
            $x++;
        } elseif ($dir == 'u') {
            $y--;
        } elseif ($dir == 'd') {
            $y++;
        } else {
            var_dump('invalid4'); die();
        }
    } else {
        var_dump('invalid5'); die();
    }

    if (!is_valid($grid, $x, $y)) {
        return false;
    }

    return true;
}

$steps = 0;
$letters = "";
$x = $start;
$y = 0;
$dir = 'd';
do {
    $steps++;
    $valid = traverse($grid, $letters, $x, $y, $dir);
} while ($valid);

echo 'Part A: ' . $letters . PHP_EOL;
echo 'Part B: ' . $steps . PHP_EOL;
