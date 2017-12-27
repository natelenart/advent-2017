<?php

class Duet
{
    public function __construct($part = 'A')
    {
        $registers = range('a', 'h');
        $registers = array_flip($registers);
        foreach ($registers as $idx => $register) {
            $registers[$idx] = 0;
        }
        $this->registers = $registers;

        if ($part != 'A') {
            $this->registers['a'] = 1;
        }

        $contents = trim(file_get_contents('23-input.txt'));
        $this->inst = explode("\n", $contents);

        $this->freq = [];

        $this->mul_invoked = 0;

        $this->part = $part;
    }

    private static function isPrime($n)
    {
        for ($i = $n; --$i && $n%$i;);

        return $i==1;
    }

    private function set($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] = (int)($then);
    }

    private function sub($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] -= (int)($then);
    }

    private function mul($x, $y)
    {
        $then = is_numeric($y) ? $y : $this->registers[$y];
        $this->registers[$x] *= (int)($then);

        $this->mul_invoked++;
    }

    public function process()
    {
        $i = 0;
        while ($i < count($this->inst)) {
            $inc = true;
            $input = $this->inst[$i];
            $parts = explode(' ', $input);
            switch ($parts[0]) {
            case 'set':
            case 'sub':
            case 'mul':
                $this->{$parts[0]}($parts[1], $parts[2]);
                break;
            case 'jnz':
                $if   = (int) is_numeric($parts[1]) ? $parts[1] : $this->registers[$parts[1]];
                $then = (int) is_numeric($parts[2]) ? $parts[2] : $this->registers[$parts[2]];
                if ($if != 0) {
                    $i += (int)($then);
                    $inc = false;
                }
                break;
            default:
                var_dump('error');
                var_dump($input);die();
            }

            if ($i < 0 || $i > count($this->inst)) {
                break;
            }

            if ($inc) {
                $i++;
            }
        }
    }

    public function processB()
    {
        $cnt = 0;
        for ($i = 108100; $i < 125100; $i += 17) {
            if (! self::isPrime($i)) {
                $cnt++;
            }
        }

        return $cnt;
    }
}

$c = new Duet;
$c->process();

echo 'Part A: ' . $c->mul_invoked . PHP_EOL;


$c2 = new Duet('B');

echo 'Part B: ' . $c2->processB() . PHP_EOL;
