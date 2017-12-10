<?php

$contents = trim(file_get_contents('9-input.txt'));

$group = false;
$garbage = false;
$ignore = false;

$level = 0;
$score = 0;
$chars = 0;

for ($i = 0; $i < strlen($input); $i++) {
    if ($ignore) {
        $ignore = false;
        continue;
    }

    if ($input[$i] == '!') {
        $ignore = true;
        continue;
    }

    if ($garbage) {
        if ($input[$i] == '>') {
            $garbage = false;
        } else { // Part B
            $chars++;
        }
    } else {
        if ($input[$i] == '{') {
            $group = true;
            $level++;
        } elseif ($input[$i] == '<') {
            $garbage = true;
        } elseif ($input[$i] == '}') {
            $group = false;
            $score += $level;
            $level--;
        } elseif ($input[$i] == '>') {
            $garbage = false;
        }
    }
}

echo 'Part A: ' . $score . PHP_EOL;
echo 'Part B: ' . $chars . PHP_EOL;
