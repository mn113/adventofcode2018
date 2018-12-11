<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day11 extends Solution implements TwoParter
{

    function __construct() {
        $this->serial = 1955;
    }

    function calcPower(int $x, int $y): int {
        $rackId = $x + 10;
        $power = $rackId * $y;
        $power += $this->serial;
        $power *= $rackId;
        $power = floor($power / 100) % 10;  // extracts hundreds digit
        $power -= 5;
        return $power;
    }

    function sumOfSquare(int $x0, int $y0, int $size, array $grid): int {
        $sum = 0;
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $sum += $grid[$y0+$y][$x0+$x];
            }
        }
        return $sum;
    }

    function buildPowerGrid() {
        // Calculate entire power grid (90000 x calcPower):
        $this->powerGrid = [];
        for ($y = 0; $y < 298; $y++) {
            for ($x = 0; $x < 298; $x++) {
                $p = $this->calcPower($x, $y);
                $this->powerGrid[$y][$x] = $p;
            }
        }
    }

    function part1() {
        $this->buildPowerGrid();
        // Find the heaviest 3x3 area:
        $maxSum = 0;
        $maxLoc = [];
        for ($y = 0; $y < 298; $y++) {
            for ($x = 0; $x < 298; $x++) {
                $p = $this->sumOfSquare($x, $y, 3, $this->powerGrid);
                if ($p > $maxSum) {
                    $maxSum = $p;
                    $maxLoc = ['x' => $x, 'y' => $y];
                }
            }
        }
        echo "$maxSum is the sum at\n";
        print_r($maxLoc);
    }

    function part2() {
        if (!isset($this->powerGrid)) $this->buildPowerGrid();

        // Find the heaviest ?x? area:
        $maxSum = 0;
        $maxLoc = [];
        for ($y = 0; $y < 298; $y++) {
            echo "$y...";
            for ($x = 0; $x < 298; $x++) {
                for ($size = 3; $size < 20; $size++) {
                    $p = $this->sumOfSquare($x, $y, $size, $this->powerGrid);
                    if ($p > $maxSum) {
                        $maxSum = $p;
                        $maxLoc = ['x' => $x, 'y' => $y];
                        $maxSize = $size;
                    }
                }
            }
        }
        echo "\n$maxSum is the sum of square size $maxSize at\n";
        print_r($maxLoc);
    }
}