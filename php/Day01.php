<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day01 extends Solution implements TwoParter {

    protected $inputFile = '../inputs/01.txt';
    public $data;
    public $freq;
    public $visited;

    function __construct() {
        $this->data = $this->readInputToArray();
        $this->freq = 0;
        $this->visited = [0 => true];
    }

    function process_list() {        
        foreach ($this->data as $instr) {
            $this->freq += intval($instr);
            // Part 2:
            if (array_key_exists($this->freq, $this->visited) && $this->visited[$this->freq]) {
                die("Revisited $this->freq\n");
            }
            $this->visited[$this->freq] = true;
        }
        echo "Freq at loop's end: $this->freq\n";
    }

    function part1() {
        $this->process_list();
    }

    function part2() {
        while (true) {
            $this->process_list(); // should eventually die
        }
    }
}
$d = new Day01();
$d->part1();
$d->part2();
