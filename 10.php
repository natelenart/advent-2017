<?php

function getPos($cnt, $idx) {
    $last = $cnt - 1;

    while ($idx > $last) {
        $idx -= $cnt;
    }

    return $idx;
}

function partA()
{
    $contents = trim(file_get_contents('10-input.txt'));

    $current = 0;
    $list = range(0, 255);
    $l = count($list);
    $skip = 0;
    $lengths = explode(",", $contents);

    foreach ($lengths as $k => $length) {
        $length = (int)($length);

        $idx = getPos($l, $current);
        $arr = array_slice($list, $idx, $length);
        if (! array_key_exists($idx+$length, $list)) {
            $arr = array_merge($arr,
                array_slice($list, 0, $length-count($arr)));
        }
        $arr = array_reverse($arr);

        if (count($arr) != $length) {
            echo 'ERROR: count of subset does not match length' . PHP_EOL;
            die;
        }

        foreach ($arr as $i => $item) {
            $idx = getPos($l, $current+$i);
            $list[$idx] = $item;
        }

        $current = getPos($l, $current + $length + $skip);
        $skip += 1;
    }

    return $list[0] * $list[1];
}

function partB()
{
    $contents = trim(file_get_contents('10-input.txt'));

    $current = 0;
    $list = range(0, 255);
    $l = count($list);
    $skip = 0;

    $lengths = [];
    for ($i = 0; $i < strlen($contents); $i++) {
        $lengths[] = ord($contents[$i]);
    }

    $lengths = array_merge($lengths, [ 17, 31, 73, 47, 23 ]);

    for ($rounds = 0; $rounds < 64; $rounds++) {
        foreach ($lengths as $length) {
            $idx = getPos($l, $current);
            $arr = array_slice($list, $idx, $length);
            if (! array_key_exists($idx+$length, $list)) {
                $arr = array_merge($arr,
                    array_slice($list, 0, $length-count($arr)));
            }
            $arr = array_reverse($arr);

            if (count($arr) != $length) {
                echo 'ERROR: count of subset does not match length' . PHP_EOL;
                die;
            }

            foreach ($arr as $i => $item) {
                $idx = getPos($l, $current+$i);
                if (! array_key_exists($idx, $list)) {
                    echo 'ERROR: index ' . $idx . ' does not already exist' . PHP_EOL;
                    die;
                }
                $list[$idx] = $item;
            }

            $current = getPos($l, $current + $length + $skip);
            $skip += 1;
        }
    }

    $out = "";
    $chunks = array_chunk($list, 16);
    foreach ($chunks as $chunk) {
        $str = implode(' ^ ', $chunk);
        $xor = eval('return ' . $str . ';');
        $out .= dechex($xor);
    }

    return $out;
}

echo 'Part A: ' . partA() . PHP_EOL;
echo '======' . PHP_EOL;
echo 'Part B: ' . partB() . PHP_EOL;
