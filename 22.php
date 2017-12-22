<?php

class Grid
{
    public function __construct($part)
    {
        $contents = trim(file_get_contents("22-input.txt"));
        $this->grid = [];
        $rows = explode("\n", $contents);
        foreach ($rows as $y => $row) {
            $cols = str_split($row);
            foreach ($cols as $x => $col) {
                $this->grid[$y-12][$x-12] = $col;
            }
        }

        $this->currX = 0;
        $this->currY = 0;

        $this->dir = 'u';

        $this->infections = 0;

        $this->part = $part;
    }

    public function burst()
    {
        $this->wakeup();
        $this->work();
        $this->move();
    }

    public function wakeup()
    {
        if ($this->part == 'A') {
            if ($this->grid[$this->currY][$this->currX] == '#') {
                $this->right();
            } else {
                $this->left();
            }
        } else {
            if ($this->grid[$this->currY][$this->currX] == '.') {
                $this->left();
            } elseif ($this->grid[$this->currY][$this->currX] == '/') {
                // do nothing
            } elseif ($this->grid[$this->currY][$this->currX] == '#') {
                $this->right();
            } else { // flagged $
                $this->right();
                $this->right();
            } 
        }
    }

    public function work()
    {
        // Weak:     /
        // Infected: #
        // Flagged: $
        // Clean: .

        if ($this->grid[$this->currY][$this->currX] == '#') {
            if ($this->part == 'A') {
                $this->grid[$this->currY][$this->currX] = '.';
            } else {
                $this->grid[$this->currY][$this->currX] = '$';
            }
        } elseif ($this->grid[$this->currY][$this->currX] == '.') {
            if ($this->part == 'A') {
                $this->grid[$this->currY][$this->currX] = '#';
                $this->infections++;
            } else {
                $this->grid[$this->currY][$this->currX] = '/';
            }
        } elseif ($this->grid[$this->currY][$this->currX] == '/') {
            $this->grid[$this->currY][$this->currX] = '#';
            if ($this->part == 'B') {
                $this->infections++;
            }
        } else { // flagged $
            $this->grid[$this->currY][$this->currX] = '.';
        }
    }

    public function move()
    {
        if ($this->dir == 'u') {
            $this->currY--;
        } elseif ($this->dir == 'r') {
            $this->currX++;
        } elseif ($this->dir == 'd') {
            $this->currY++;
        } else {
            $this->currX--;
        }

        if (! isset($this->grid[$this->currY])) {
            $this->grid[$this->currY] = [];
        }
        if (! isset($this->grid[$this->currY][$this->currX])) {
            $this->grid[$this->currY][$this->currX] = '.';
        }
    }

    public function right()
    {
        if ($this->dir == 'r') {
            $this->dir = 'd';
        } elseif ($this->dir == 'd') {
            $this->dir = 'l';
        } elseif ($this->dir == 'l') {
            $this->dir = 'u';
        } else {
            $this->dir = 'r';
        }
    }

    public function left()
    {
        if ($this->dir == 'r') {
            $this->dir = 'u';
        } elseif ($this->dir == 'u') {
            $this->dir = 'l';
        } elseif ($this->dir == 'l') {
            $this->dir = 'd';
        } else {
            $this->dir = 'r';
        }
    }
}

$g = new Grid('A');

for ($i = 0; $i < 10000; $i++) {
    $g->burst();
}

echo 'Part A: ' . $g->infections . PHP_EOL;



$g = new Grid('B');

for ($i = 0; $i < 10000000; $i++) {
    $g->burst();
}

echo 'Part B: ' . $g->infections . PHP_EOL;
