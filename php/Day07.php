<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day07 extends Solution implements TwoParter {

    public $data;

    function __construct() {
        $this->input = file('../inputs/07.txt');
        $this->pairs = array_map(function($line) {
            preg_match("/^Step ([A-Z]).*step ([A-Z])/", $line, $matches);
            return [
                'prereq' => $matches[1],
                'node' => $matches[2]
            ];
        }, $this->input);

        // build up the proper table of prerequisites:
        $this->table = [];
        foreach ($this->pairs as $pair) {
            extract($pair);
            echo "prereq = $prereq, node = $node\n";
            if (array_key_exists($node, $this->table)) {
                $this->table[$node][] = $prereq;
            }
            else {
                $this->table[$node] = [$prereq];
            }
        }
        var_dump($this->table);
        die();
    }

    function part1() {
        $output = [];
        $available = ["B"];

        while (count($available)) {
            echo count($available) . "\n";
            // get first available whose prerequisites are met:
            $reallyAvailable = array_filter($available, function($a) use ($output, $available) {
                echo "a = $a\n";
                $prereqs = $this->table[$a];
                if (!is_null($prereqs)) {
                    foreach ($prereqs as $p) {
                        if (!in_array($p, $output) && !in_array($p, $available)) return false;
                    }
                }
                return true;
            });
            asort($reallyAvailable);
            $current = array_shift($reallyAvailable);

            $output[] = $current;
            // Find out which nodes got unlocked by the current node:
            $unlocked = array_map(function($pair) {
                return $pair[1]; // the next nodes
            }, array_filter($this->pairs, function($pair) use ($current) {
                return $pair[0] == $current; // the previous node matches the current
            }));
            echo "Unlocked: " . implode(" ", $unlocked) . "\n";
            // Add unlocked nodes to availables:
            foreach ($unlocked as $u) {
                if (!in_array($u, $available)) $available[] = $u;
            }
        }
        echo implode("", $output);

        // BGJCNLQUYIFMOEZTADKSPVXRHW
    }

    function part2() {

    }
}