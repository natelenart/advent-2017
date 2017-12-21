<?php

class Duet
{
    public function __construct($id)
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

    private function set($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] = (int)($then);
    }

    private function add($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] += (int)($then);
    }

    private function mul($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] *= (int)($then);
    }

    private function mod($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] %= (int)($then);
    }

    public function processPartA()
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
                    echo 'Part A: ' . $this->freq[count($this->freq)-1] . PHP_EOL;
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

    public function tickPartB($i)
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

    public function rec($val)
    {
        $this->receive[] = $val;
    }
}

$c = new Duet(-1);
$c->processPartA();

$c0 = new Duet(0);
$c1 = new Duet(1);

$counter = 0;
$ticks = [ 0, 0 ];
$stuck = [ false, false ];
while (true) {
    $ret = [ $c0->tickPartB($ticks[0]) ];
    $ret[1] = $c1->tickPartB($ticks[1]);

    if ($ret[1][0] == 'snd') {
        $counter++;
    }

    foreach ($ret as $idx => $resp) {
        if ($resp[0] == 'snd') {
            $c = ($idx == 0) ? $c1 : $c0;
            $c->rec($resp[1]);
            $ticks[$idx]++;
        } elseif ($resp[0] == 'num') {
            $ticks[$idx]++;
        } elseif ($resp[0] == 'jgz') {
            $ticks[$idx] += $resp[1];
        } elseif ($resp[0] == 'rcv') {
            if ($resp[1] == 'free') {
                $ticks[$idx]++;
                $stuck[$idx] = false;
            } elseif ($resp[1] == 'blocked') {
                $stuck[$idx] = true;
            }
        }
    }

    if ($stuck[0] && $stuck[1]) {
        break;
    }
}

echo 'Part B: ' . $counter . PHP_EOL;
