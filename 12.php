<?php

class Comm
{
    public $arr;
    public $conn;
    public $groups;

    public function __construct()
    {
        $contents = trim(file_get_contents('12-input.txt'));
        $lines = explode("\n", $contents);

        $arr = [];
        foreach ($lines as $line) {
            $parts = explode(" ", $line);
            $a = (int)(array_shift($parts));
            array_shift($parts); // <->
            $b = [];
            foreach ($parts as $part) {
                $part = (int)(trim($part, ","));
                $b[] = $part;
            }
            $arr[$a] = $b;
        }

        $this->arr = $arr;
        $this->conn = [];
        $this->groups = [];
    }

    public function recurse($x)
    {
        foreach ($this->arr[$x] as $y) {
            if (! in_array($y, $this->conn)) {
                $this->conn[] = $y;
                $this->recurse($y);
            }
        }
    }

    public function partA()
    {
        $this->conn = [0];
        $this->recurse(0);

        return count($this->conn);
    }

    public function hash()
    {
        sort($this->conn);
        $x = implode("#", $this->conn);

        return sha1($x);
    }

    public function partB()
    {
        foreach ($this->arr as $x => $y) {
            $this->conn = [$x];
            $this->recurse($x);
            $h = $this->hash();
            $groups[$h] = true;
        }

        return count($groups);
    }
}

$c = new Comm;
echo 'Part A: ' . $c->partA() . PHP_EOL;
echo PHP_EOL;
echo 'Part B: ' . $c->partB() . PHP_EOL;
