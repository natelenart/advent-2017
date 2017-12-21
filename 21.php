<?php

$contents = trim(file_get_contents('21-input.txt'));
$rows = explode("\n", $contents);
$patterns = [];
foreach ($rows as $i => $pattern)
{
    $parts = explode(" => ", $pattern);
    $patterns[trim($parts[0])] = trim($parts[1]);
}
unset($i);

function stringize(array $g)
{
    $x = [];
    foreach ($g as $r) {
        $x[] = implode('', $r);
    }
    return implode('/', $x);
}

function gridize($s)
{
    $y = explode('/', $s);
    $z = [];
    foreach ($y as $r) {
        $z[] = str_split($r);
    }
    return $z;
} 

function rotate($g)
{
    if (count($g) == 3) {
        $g2 = [ [], [], [] ];
        $g2[0][0] = $g[0][2];
        $g2[0][1] = $g[1][2];
        $g2[0][2] = $g[2][2];
        $g2[1][0] = $g[0][1];
        $g2[1][1] = $g[1][1]; // center stays
        $g2[1][2] = $g[2][1];
        $g2[2][0] = $g[0][0];
        $g2[2][1] = $g[1][0];
        $g2[2][2] = $g[2][0];
    } else {
        $g2 = [ [], [] ];
        $g2[0][0] = $g[0][1];
        $g2[0][1] = $g[1][1];
        $g2[1][0] = $g[0][0];
        $g2[1][1] = $g[1][0];
    }

    return $g2;
}

function flip($g)
{
    if (count($g) == 3) {
        $g2 = [ [], [], [] ];
        $g2[0][0] = $g[0][2];
        $g2[0][1] = $g[0][1]; // center stays
        $g2[0][2] = $g[0][0];
        $g2[1][0] = $g[1][2];
        $g2[1][1] = $g[1][1]; // center stays
        $g2[1][2] = $g[1][0];
        $g2[2][0] = $g[2][2];
        $g2[2][1] = $g[2][1]; // center stays
        $g2[2][2] = $g[2][0];
    } else {
        $g2 = [ [], [] ];
        $g2[0][0] = $g[0][1];
        $g2[0][1] = $g[0][0];
        $g2[1][0] = $g[1][1];
        $g2[1][1] = $g[1][0];
    }

    return $g2;

}

function spl($g)
{
    $g2 = [];
    if (count($g) % 2 == 0) {
        for ($y = 0; $y < count($g); $y += 2) {
            $sub = [];
            for ($x = 0 ; $x < count($g); $x += 2) {
                $sub[] = [
                    [ $g[$y+0][$x], $g[$y+0][$x+1] ],
                    [ $g[$y+1][$x], $g[$y+1][$x+1] ]
                ];
            }
            $g2[] = $sub;
        }
    } else {
        for ($y = 0; $y < count($g); $y += 3) {
            $sub = [];
            for ($x = 0 ; $x < count($g); $x += 3) {
                $sub[] = [
                    [ $g[$y+0][$x], $g[$y+0][$x+1], $g[$y+0][$x+2] ],
                    [ $g[$y+1][$x], $g[$y+1][$x+1], $g[$y+1][$x+2] ],
                    [ $g[$y+2][$x], $g[$y+2][$x+1], $g[$y+2][$x+2] ],
                ];
            }
            $g2[] = $sub;
        }
    }

    return $g2;
}

function combine($g)
{
    $cnt = count($g[0][0]);

    $out = [];

    foreach ($g as $y => $r) {
        if ($cnt % 4 == 0) {
            $row1 = [];
            $row2 = [];
            $row3 = [];
            $row4 = [];
            foreach ($r as $x => $g2) {
                foreach ($g2 as $y2 => $r2) {
                    foreach ($r2 as $x2 => $c2) {
                        if ($y2 == 0) {
                            $row1[] = $c2;
                        } elseif ($y2 == 1) {
                            $row2[] = $c2;
                        } elseif ($y2 == 2) {
                            $row3[] = $c2;
                        } else {
                            $row4[] = $c2;
                        }
                    }
                }
            }
            $out[] = $row1;
            $out[] = $row2;
            $out[] = $row3;
            $out[] = $row4;
        } else {
            $row1 = [];
            $row2 = [];
            $row3 = [];
            foreach ($r as $x => $g2) {
                foreach ($g2 as $y2 => $r2) {
                    foreach ($r2 as $x2 => $c2) {
                        if ($y2 == 0) {
                            $row1[] = $c2;
                        } elseif ($y2 == 1) {
                            $row2[] = $c2;
                        } else {
                            $row3[] = $c2;
                        }
                    }
                }
            }
            $out[] = $row1;
            $out[] = $row2;
            $out[] = $row3;
        }
    }
    return $out;
}

$grid = [
    [ '.', '#', '.' ],
    [ '.', '.', '#' ],
    [ '#', '#', '#' ],
];
$iters = 5;
$part = 'A';

LOOP:

for ($i = 0; $i < $iters; $i++) {
    $grid = spl($grid);
    for ($y = 0; $y < count($grid); $y++) {
        for ($x = 0; $x < count($grid); $x++) {
            $g2 = $grid[$y][$x];
            $str = stringize($g2);
            $pattern = null;
            for ($tries = 0; $tries < 4; $tries++) {
                if (array_key_exists($str, $patterns)) {
                    $pattern = $patterns[$str];
                    break;
                }
                $g2 = rotate($g2);
                $str = stringize($g2);
                if (array_key_exists($str, $patterns)) {
                    $pattern = $patterns[$str];
                    break;
                }
                $g2 = flip($g2);
                $str = stringize($g2);
                if (array_key_exists($str, $patterns)) {
                    $pattern = $patterns[$str];
                    break;
                }
                $g2 = flip($g2);
            }
            $grid[$y][$x] = gridize($pattern);
        }
    }
    $grid = combine($grid);
}

$counter = 0;
foreach ($grid as $y => $r) {
    foreach ($r as $x => $c) {
        if ($c == '#') {
            $counter++;
        }
    }
}

echo 'Part ' . $part . ': ' . $counter . PHP_EOL;

$loop = false;
if ($part == 'A') {
    $loop = true;
}

$grid = [
    [ '.', '#', '.' ],
    [ '.', '.', '#' ],
    [ '#', '#', '#' ],
];
$iters = 18;
$part = 'B';

if ($loop) {
    goto LOOP;
}
