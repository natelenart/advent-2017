<?php

class Firewall
{
    public function __construct()
    {
        $contents = trim(file_get_contents('13-input.txt'));
        $input = explode("\n", $contents);

        $layers = [];
        foreach ($input as $row) {
            $idx = explode(':', $row)[0];
            $depth = trim(explode(':', $row)[1]);
            $layers[$idx] = [
                'depth' => (int)($depth),
                'dir'   => 'D',
                'pos'   => 0,
            ];
        }

        $this->pristine = $layers;
        $this->layers = $layers;
        $this->layer = -1;
        $this->severity = 0;
        $this->caught = false;
    }

    public function restart($layers)
    {
        $this->layers = $layers;
        $this->layer = -1;
        $this->severity = 0;
        $this->caught = false;
    }

    public function advance()
    {
        $this->layer++;
        if (array_key_exists($this->layer, $this->layers)) {
            if ($this->layers[$this->layer]['pos'] == 0) {
                $this->severity += $this->layer * $this->layers[$this->layer]['depth'];
                $this->caught = true;
            }
        }
    }

    public function updateScanners()
    {
        foreach ($this->layers as $layer => $row) {
            if ($row['dir'] == 'D') {
                if ($row['pos'] < $row['depth'] - 1) {
                    $row['pos']++;
                } else {
                    $row['dir'] = 'U';
                    $row['pos']--;
                }
            } elseif ($row['dir'] == 'U') {
                if ($row['pos'] > 0) {
                    $row['pos']--;
                } else {
                    $row['dir'] = 'D';
                    $row['pos']++;
                }
            }
            $this->layers[$layer] = $row;
        }
    }

    public function iter($b = false)
    {
        $last = array_pop(array_keys($this->layers));
        for ($i = 0; $i <= $last; $i++) {
            $this->advance();
            if ($b && $this->severity > 0) {
                return [ 's' => $this->severity, 'c' => $this->caught ];
            }
            $this->updateScanners();
        }

        return [ 's' => $this->severity, 'c' => $this->caught ];
    }

    public function iterB()
    {
        $severity = 0;
        $caught = false;
        $clean = $this->layers;

        $delay = 0;
        do {
            $this->restart($clean);
            $this->updateScanners();
            $clean = $this->layers;

            $ret = $this->iter(true);
            $severity = $ret['s'];
            $caught = $ret['c'];

            $delay++;

            if ($delay % 10000 == 0) {
                echo ' iter: ' . $delay . ' (' . $severity . ') ' . PHP_EOL;
            }

        } while ($severity > 0 || $caught);

        return $delay;
    }
}

$f = new Firewall;
echo 'Part A: ' . $f->iter()['s'] . PHP_EOL; // 9:55 -> 10:23
$f = new Firewall;
echo 'Part B: ' . $f->iterB() . PHP_EOL; // 10:23 -> 11:29
