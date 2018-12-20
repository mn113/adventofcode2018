<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day18 extends Solution implements TwoParter
{

    function __construct()
    {
        $this->size = 50;
        $this->lines = file("../inputs/18.txt");    // 50x50
        $this->land = array_map('self::parseLine', $this->lines);
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): array
    {
        return str_split(trim($line));
    }


    function tick(array $grid): array {
        $newgrid = array_fill(0, $this->size, array_fill(0, $this->size, null));
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                $nbs = $this->neighbours($x, $y, $grid);
                // Business logic:
                // - An open acre (.) will become filled with trees (|) if three or more adjacent
                // acres contained trees. Otherwise, nothing happens.
                if ($grid[$y][$x] == '.') {
                    if (count(array_filter($nbs, function($n) { return $n == '|'; })) >= 3) {
                        $newgrid[$y][$x] = '|';
                    }
                    else {
                        $newgrid[$y][$x] = '.';
                    }
                }
                // - An acre filled with trees (|) will become a lumberyard (#) if three or more adjacent
                // acres were lumberyards. Otherwise, nothing happens.
                if ($grid[$y][$x] == '|') {
                    if (count(array_filter($nbs, function($n) { return $n == '#'; })) >= 3) {
                        $newgrid[$y][$x] = '#';
                    }
                    else {
                        $newgrid[$y][$x] = '|';
                    }
                }
                // - An acre containing a lumberyard (#) will remain a lumberyard if it was adjacent
                // to at least one other lumberyard and at least one acre containing trees.
                // Otherwise, it becomes open (.)
                if ($grid[$y][$x] == '#') {
                    if (in_array('|', $nbs) && in_array('#', $nbs)) {
                        $newgrid[$y][$x] = '#';
                    }
                    else {
                        $newgrid[$y][$x] = '.';
                    }
                }
            }
        }
        return $newgrid;
    }


    function pad_grid(array $grid): array {
        // pad grid with nulls to avoid out-of-bounds errors:
        array_push($grid, array_fill(0, $this->size, null));
        array_unshift($grid, array_fill(0, $this->size, null));
        foreach ($grid as $k => $row) {
            array_push($grid[$k], null);
            array_unshift($grid[$k], null);
        }
        return $grid;
    }

    function unpad_grid(array $grid): array {
        array_pop($grid);
        array_shift($grid);
        foreach ($grid as $k => $row) {
            array_pop($grid[$k]);
            array_shift($grid[$k]);
        }
        return $grid;
    }

    function neighbours(int $x, int $y, array $grid): array {
        $grid = $this->pad_grid($grid);
        // our coords have been offset:
        $y++;
        $x++;

        $neighbs = [
            [-1,-1], [0,-1], [1,-1],
            [-1, 0],         [1, 0],
            [-1, 1], [0, 1], [1, 1]
        ];
        $neighbs = array_map(function($n) use ($x,$y,$grid) {
            return $grid[$y + $n[0]][$x + $n[1]];
        }, $neighbs);
        return $neighbs;
    }


    function printGrid(array $grid) {
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                echo $grid[$y][$x];
            }
            echo "\n";
        }
    }


    function countSymbol(string $symbol, array $grid): int {
        $count = 0;
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                if ($grid[$y][$x] == $symbol) $count++;
            }
        }
        return $count;
    }


    function part1($limit = 10) {
        $t = 0;
        $grid = $this->land;
        $this->printGrid($grid);
        while ($t < $limit) {
            $t++;
            $grid = $this->tick($grid);
            echo "t = $t:\n";
            //$this->printGrid($grid);
            $resources = $this->countSymbol('|', $grid) * $this->countSymbol('#', $grid);
            echo "$resources\n";
        }
    }


    function part2() {
        // The pattern repeats every 28 cycles
        // The 1,000,000,000th cycle is therefore equivalent to the 468th
        $this->part1(468);
    }
}