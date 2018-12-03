<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day03 extends Solution implements TwoParter {

    public $data;

    function __construct() {
        $this->data = file('../inputs/03.txt');
        $this->areas = array_map('self::parseLine', $this->data);
        $this->minX = min(array_map(function($a) { return $a['x']; }, $this->areas));
        $this->minY = min(array_map(function($a) { return $a['y']; }, $this->areas));
        $this->maxX = max(array_map(function($a) { return $a['x'] + $a['w']; }, $this->areas));
        $this->maxY = max(array_map(function($a) { return $a['y'] + $a['h']; }, $this->areas));
        $this->spanX = $this->maxX - $this->minX;
        $this->spanY = $this->maxY - $this->minY;
        echo "x: {$this->minX} to {$this->maxX} ({$this->spanX})\n";
        echo "y: {$this->minY} to {$this->maxY} ({$this->spanY})\n";
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): array {
        preg_match('/^#(\d+) @ (\d+),(\d+): (\d+)x(\d+)$/', $line, $matches);
        return [
            'id'=> intval($matches[1]),
            'x' => intval($matches[2]),
            'y' => intval($matches[3]),
            'w' => intval($matches[4]),
            'h' => intval($matches[5])
        ];
    }

    function printGrid($grid) {
        $h = count($grid);
        $w = count($grid[array_keys($grid)[0]]);
        $y0 = min(array_keys($grid));
        $x0 = min(array_keys($grid[array_keys($grid)[0]]));
        for ($y = $y0; $y < $y0 + $h; $y++) {
            for ($x = $x0; $x < $x0 + $w; $x++) {
                print $grid[$y][$x] . " ";
            }
            print "\n";
        }
    }

    /*
     * Counts number of occurrences of a particular item in a 2D array
     */
    function charCounter($c, array $grid): int {
        $total = 0;
        $h = count($grid);
        $w = count($grid[array_keys($grid)[0]]);
        $y0 = min(array_keys($grid));
        $x0 = min(array_keys($grid[array_keys($grid)[0]]));
        for ($y = $y0; $y < $y0 + $h; $y++) {
            for ($x = $x0; $x < $x0 + $w; $x++) {
                if ($grid[$y][$x] === $c) {
                    $total++;
                }
            }
        }
        return $total;
    }

    function part1() {
        // 2D array representing fabric, filled with 0's:
        $this->fabric = array_fill($this->minY, $this->spanY,
            array_fill($this->minX, $this->spanX, 0)
        );
        // Fill fabric with smaller areas:
        foreach ($this->areas as $a) {
            extract($a); // creates in scope: $id, $x, $y, $w, $h

            while ($w > 0) {
                while ($h > 0) {
//                    echo "($x,$y)\n";
                    // Place area id in empty cell:
                    if ($this->fabric[$y][$x] === 0) {
                        $this->fabric[$y][$x] = $id;
                    }
                    // Place '@' where areas overlap:
                    else if ($this->fabric[$y][$x] !== '@' && $this->fabric[$y][$x] !== 0) {
                        $this->fabric[$y][$x] = '@';
                    }
                    $y++;
                    $h--;
                }
                $y = $a['y'];
                $h = $a['h'];
                $x++;
                $w--;
            }
        }
        //$this->printGrid($fabric);
        $charCounts = $this->charCounter('@', $this->fabric);
        echo "Overlap: $charCounts\n";
    }

    function part2() {
        // $this->fabric was filled in part 1
        foreach ($this->areas as $a) {
            extract($a); // creates in scope: $id, $x, $y, $w, $h
            $area = $this->charCounter($id, $this->fabric);
            echo "$id: $area\n";
            if ($area == $w * $h) {
                die("$id fills $area cells and is not overlapped.\n");
            }
        }
        echo "No region found.\n";
    }

}