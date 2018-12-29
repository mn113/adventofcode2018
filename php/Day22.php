<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day22 extends Solution implements TwoParter
{
    public $depth = 4845;
    public $target = ['x' => 6, 'y' => 770];

    function __construct() {
        $this->grid = array_fill(0, $this->target['y'], array_fill(0, $this->target['x'], null));
    }

    function printGrid() {
        for ($y = 0; $y <= $this->target['y']; $y++) {
            for ($x = 0; $x <= $this->target['x']; $x++) {
                if ($x == 0 && $y == 0) {
                    echo 'M';
                }
                else if ($x == $this->target['x'] && $y == $this->target['y']) {
                    echo 'T';
                }
                else {
                    echo str_split('.=|')[$this->grid[$y][$x]['type']];
                }
            }
            echo "\n";
        }
    }

    function sumGrid() {
        $sum = 0;
        for ($y = 0; $y <= $this->target['y']; $y++) {
            for ($x = 0; $x <= $this->target['x']; $x++) {
                $sum += $this->grid[$y][$x]['type'];
            }
        }
        return $sum;
    }

    function part1() {
        // Fill grid
        for ($y = 0; $y <= $this->target['y']; $y++) {
            for ($x = 0; $x <= $this->target['x']; $x++) {
                if ($y == 0 && $x == 0) {
                    // source:
                    $gi = 0;
                }
                else if ($x == $this->target['x'] && $y == $this->target['y']) {
                    // target:
                    $gi = 0;
                }
                else if ($y == 0) {
                    // row 0:
                    $gi = $x * 16807;
                }
                else if ($x == 0) {
                    // column 0:
                    $gi = $y * 48271;
                }
                else {
                    // depends on ero of 2 other cells:
                    $gi = $this->grid[$y][$x - 1]['ero'] * $this->grid[$y - 1][$x]['ero'];
                }
                $ero = ($gi + $this->depth) % 20183;
                $type = $ero % 3;
                $this->grid[$y][$x] = [
                    'gi' => $gi,
                    'ero' => $ero,
                    'type' => $type
                ];
            }
        }
        $this->printGrid();
        echo $this->sumGrid();
    }

    function costOfMove($x1, $y1, $x2, $y2, $mytool) {
        $type1 = $this->grid[$y1][$x1];
        $type2 = $this->grid[$y2][$x2];
        // type 0 => rocky  => climb | torch
        // type 1 => wet    => climb | neither
        // type 2 => narrow => torch | neither
        $nextTools = [
            [self::CLIMBING, self::TORCH],
            [self::CLIMBING, self::NEITHER],
            [self::TORCH, self::NEITHER]
        ][$type2];
        if (in_array($mytool, $nextTools)) {
            return 1;
        }
        else {
            // what to switch to? add 2 nodes?
            $mytool = [
            ][$type2];
            return 8;
        }
    }

    function part2() {
        $upperTimeBound =
    }
}