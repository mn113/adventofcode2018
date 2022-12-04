<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;
use \Ds\Set;

class Day25 extends Solution implements TwoParter
{

    function __construct()
    {
        $this->data = file("../inputs/25.txt");
        $this->points = array_map('self::parseLine', $this->data);
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): array {
        return array_map('intval', explode(',', trim($line)));
    }

    static function manhattan4D(array $a, array $b): int {
        return abs($a[0] - $b[0]) + abs($a[1] - $b[1]) + abs($a[2] - $b[2]) + abs($a[3] - $b[3]);
    }

    function part1() {
        $points = $this->points;
        var_dump($points[0]);
        echo self::manhattan4D($points[0], $points[1]) . "\n";

        // build distance table, referring to points by index
        $table = [];
        $constellations = [];
        for ($i = 0; $i < count($points) - 1; $i++) {
            $table[$i] = [$i];
            $constellations[$i] = [$i];
            for ($j = $i + 1; $j < count($points); $j++) {
                // always reference as [$lower][$higher] index
                $d = self::manhattan4D($points[$i], $points[$j]);
                if ($d <= 3) $constellations[$i][] = $j;
            }
        }
        print_r($constellations);
        // aggregate groups:
        foreach ($constellations as $k => $list) {
            foreach ($list as $c) {
                if (array_key_exists($c, $constellations) && count($constellations[$c])) {
                    $constellations[$k] = array_merge($constellations[$k], $constellations[$c]);
                    $constellations[$c] = [];
                }
            }
        }
        print_r($constellations);
    }

    function part2() {

    }
}