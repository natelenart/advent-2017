<?php

class Perm
{
    public $inst;
    public $prog;
    public $seen;

    public function __construct()
    {
        $contents = trim(file_get_contents('16-input.txt'));
        $this->inst = explode(",", $contents);

        $mapped = [];
        foreach ($this->inst as $inst) {
            if ($inst[0] == 's') {
                $input = (int)(substr($inst, 1));
                $mapped[] = [ 's', $input ];
            } elseif (preg_match('#^x(\d+)/(\d+)$#', trim($inst), $matches)) {
                $a = (int)($matches[1]);
                $b = (int)($matches[2]);
                $mapped[] = [ 'x', $a, $b ];
            } elseif (preg_match('#^p([a-p])/([a-p])$#', trim($inst), $matches)) {
                $a = $matches[1];
                $b = $matches[2];
                $mapped[] = [ 'p', $a, $b ];
            }
        }
        $this->inst = $mapped;

        $this->prog = range('a', 'p');
    }

    private function swap($a, $b)
    {
        $tmp = $this->prog[$a];
        $this->prog[$a] = $this->prog[$b];
        $this->prog[$b] = $tmp;
        unset($tmp);
    }

    public function run()
    {
        if (is_string($this->prog)) {
            $this->prog = str_split($this->prog);
        }

        foreach ($this->inst as $inst) {
            if ($inst[0] == 's') {
                $sub = array_splice($this->prog, ($inst[1]*-1), $inst[1]);
                foreach (array_reverse($sub) as $elem) {
                    array_unshift($this->prog, $elem);
                }
                unset($sub);
            } elseif ($inst[0] == 'x') {
                $this->swap($inst[1], $inst[2]);
            } else {
                $keyA = array_search($inst[1], $this->prog, true);
                $keyB = array_search($inst[2], $this->prog, true);

                $this->swap($keyA, $keyB);
            }
        }
    }

    public function partA()
    {
        $this->run();
        echo 'Part A: ' . implode('', $this->prog) . PHP_EOL;
    }

    public function partB()
    {
        $this->prog = implode('', $this->prog);
        for ($i = 0; $i < 1000000000; $i++) {
            $memo = $this->prog;
            if (isset($this->seen[$memo])) {
                $this->prog = $this->seen[$memo];
            } else {
                $this->run();
                $this->prog = implode('', $this->prog);
                $this->seen[$memo] = $this->prog;
            }

            if ($i % 10000000 == 0) {
                echo ' > iter: ' . number_format($i) . PHP_EOL;
            }
        }

        echo 'Part B: ' . $this->prog . PHP_EOL;
    }
}

$p1 = new Perm;
$p1->partA();

$p2 = new Perm;
$p2->partB();
