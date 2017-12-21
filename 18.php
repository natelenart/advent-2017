<?php

class Duet
{
    function __construct($id)
    {
        $registers = range('a', 'z');
        $registers = array_flip($registers);
        foreach ($registers as $idx => $register) {
            $registers[$idx] = 0;
        }
        $this->registers = $registers;

        if ($id >= 0) {
            $this->registers['p'] = $id;
        }

        $contents = trim(file_get_contents('18-input.txt'));
        $this->inst = explode("\n", $contents);

        $this->freq = [];

        $this->id = $id;

        $this->receive = [];
    }

    function set($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] = (int)($then);
    }

    function add($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] += (int)($then);
    }

    function mul($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] *= (int)($then);
    }

    function mod($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] %= (int)($then);
    }

    function process()
    {
        $i = 0;
        while ($i < count($this->inst)) {
            $inc = true;
            $input = $this->inst[$i];
            $parts = explode(' ', $input);
            switch ($parts[0]) {
            case 'snd': $this->freq[] = $this->registers[$parts[1]]; break;
            case 'set':
            case 'add':
            case 'mul':
            case 'mod':
                $this->{$parts[0]}($parts[1], $parts[2]);
                break;
            case 'rcv':
                if ($this->registers[$parts[1]] != 0) {
                    echo 'Input: ' . $input . PHP_EOL;
                    echo 'Freq: ' . $this->freq[count($this->freq)-1] . PHP_EOL;
                    echo PHP_EOL;
                    return;
                }
                break;
            case 'jgz':
                $if = $this->registers[$parts[1]];
                $then = is_numeric($parts[2]) ? $parts[2] : $this->registers[$parts[2]];
                if ($if > 0) {
                    $i += (int)($then);
                    $inc = false;
                }
                break;
            }

            if ($i < 0 || $i > count($this->inst)) {
                break;
            }

            if ($inc) {
                $i++;
            }
        }
    }

    function tick($i)
    {
        if (! array_key_exists($i, $this->inst)) {
            return [ 'nop', 'null' ];
        }

        $input = $this->inst[$i];
        $parts = explode(' ', trim($input));

        switch ($parts[0]) {
        case 'snd':
            $val = is_numeric($parts[1]) ? $parts[1] : $this->registers[$parts[1]];
            return [ 'snd', (int)($val) ];
        case 'set':
        case 'add':
        case 'mul':
        case 'mod':
            $this->{$parts[0]}($parts[1], $parts[2]);
            return [ 'num', 'null' ];
        case 'rcv':
            if (count($this->receive) > 0) {
                $val = array_shift($this->receive);
                $this->registers[$parts[1]] = $val;

                return [ 'rcv', 'free' ];
            }
            return [ 'rcv', 'blocked' ];
        case 'jgz':
            $if   = (int) is_numeric($parts[1]) ? $parts[1] : $this->registers[$parts[1]];
            $then = (int) is_numeric($parts[2]) ? $parts[2] : $this->registers[$parts[2]];
            return [ 'jgz', $if > 0 ? $then : 1 ];
        }
    }

    function rec($val)
    {
        $this->receive[] = $val;
    }
}

$c = new Duet(-1);
$c->process();

$c0 = new Duet(0);
$c1 = new Duet(1);

$counter = 0;
$tick0 = 0;
$tick1 = 0;
$stuck0 = false;
$stuck1 = false;
$done0 = false;
$done1 = false;
while (true) {
    $ret0 = $c0->tick($tick0);
    $ret1 = $c1->tick($tick1);

    if ($ret1[0] == 'snd') {
        $counter++;
    }

    if ($ret0[0] == 'nop') {
        // no-op
        $done0 = true;
    } elseif ($ret0[0] == 'snd') {
        $c1->rec($ret0[1]);
        $tick0++;
    } elseif ($ret0[0] == 'num') {
        $tick0++;
    } elseif ($ret0[0] == 'jgz') {
        $tick0 += $ret0[1];
    } elseif ($ret0[0] == 'rcv') {
        if ($ret0[1] == 'free') {
            $tick0++;
            $stuck0 = false;
        } elseif ($ret0[1] == 'blocked') {
            // stall
            $stuck0 = true;
        } else {
            var_dump('invalid rcv state 0'); die();
        }
    } else {
        var_dump('invalid inst state 0'); die();
    }

    if ($ret1[0] == 'nop') {
        // no-op
        $done1 = true;
    } elseif ($ret1[0] == 'snd') {
        $c0->rec($ret1[1]);
        $tick1++;
    } elseif ($ret1[0] == 'num') {
        $tick1++;
    } elseif ($ret1[0] == 'jgz') {
        $tick1 += $ret1[1];
    } elseif ($ret1[0] == 'rcv') {
        if ($ret1[1] == 'free') {
            $tick1++;
            $stuck1 = false;
        } elseif ($ret1[1] == 'blocked') {
            // stall
            $stuck1 = true;
        } else {
            var_dump('invalid rcv state 0'); die();
        }
    } else {
        var_dump('invalid inst state 0'); die();
    }

    if (
        ($stuck0 && $stuck1)
        || ($done0 && $done1)
        || ($stuck0 && $done1)
        || ($done0 && $stuck1)
    ) {
        break;
    }
}

// 127 is too low
echo $tick0 . ' :: ' . $tick1 . PHP_EOL;
echo 'Part B: ' . $counter . PHP_EOL;
