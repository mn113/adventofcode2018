<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day12 extends Solution implements TwoParter
{

    function __construct() {
        $this->plants = "#...#..###.#.###.####.####.#..#.##..#..##..#.....#.#.#.##.#...###.#..##..#.##..###..#..##.#..##...";
        $this->rules = file("../inputs/12.txt");
        $this->rules = array_map('self::parseLine', $this->rules);
        echo strlen($this->plants) . "\n";
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): array {
        $parts = explode(" => ", $line);
        return array_map(trim, $parts);
    }

    function propagate(string $plants): string {
        // Pad with empty pots:
        //$allplants = ".." . $plants . ".."; // plants needs to grow by ../.. before each loop
        $newplants = "";
        for ($i = 2; $i < 2 + strlen($plants); $i++) {
            foreach ($this->rules as $rule) {
                if (substr($plants, $i - 2, 5) == $rule[0]) {
                    $newplants .= $rule[1];
                    break;
                }
            }
        }
        return $newplants;
    }

    function part1() {
        // Make a shitload of plant pots:
        $plants = str_pad($this->plants, 230, ".", STR_PAD_BOTH); // 66 either side
        $t = 0;
        $sum = 0;
        while ($t < 20) {
            $t++;
            $plants = $this->propagate($plants);
            echo "$t " . str_pad($plants, 230, "_", STR_PAD_BOTH);
            echo count(array_filter(str_split($plants), function($x) { return $x == "#"; }));
            echo "\n";
        }
        // We lost 4 pots per loop: 40 pots either side
        // So list should now start at -26

        // Sum plants by index:
        for ($j = 0; $j < strlen($plants); $j++) {
            if ($plants[$j] == "#") {
                echo $j - 26 . " ";
                $sum += $j - 26;
            }
        }
        echo "\nSum of plant pots: $sum\n";
        // 1787
    }

    function part2() {

    }
}