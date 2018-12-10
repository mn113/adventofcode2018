<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day10 extends Solution implements TwoParter
{

    function __construct()
    {
        $this->input = file('../inputs/10.txt');
        $this->points = array_map('self::parseLine', $this->input);
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): array {
        preg_match('/([\d\-]+),\s*([\d\-]+).*=<\s*([\d\-]+),\s*([\d\-]+)/', $line, $matches);
        return [
            'x'=> intval($matches[1]),
            'y' => intval($matches[2]),
            'vx' => intval($matches[3]),
            'vy' => intval($matches[4])
        ];
    }

    function pointsToHash(array $points): array {
        $hash = [];
        foreach($points as $p) {
            $hash[$p['y']][$p['x']] = true;
        }
        return $hash;
    }

    function printGrid($minX, $maxX, $minY, $maxY ) {
        $latestHash = $this->pointsToHash(($this->points));
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                echo ($latestHash[$y][$x]) ? "#" : ".";
            }
            echo "\n";
        }
    }

    function min_val(array $points, string $key): int {
        return min(array_map(function($p) use ($key) {
            return $p[$key];
        }, $points));
    }

    function max_val(array $points, string $key): int {
        return max(array_map(function($p) use ($key) {
            return $p[$key];
        }, $points));
    }

    /*
     * Add the velocity of each point to its coordinates (time increases by 1)
     */
    function movePoints(array $points, int $factor = 1): array
    {
        for ($i = 0; $i < count($points); $i++) {
            $points[$i]['x'] += $points[$i]['vx'] * $factor;
            $points[$i]['y'] += $points[$i]['vy'] * $factor;
        }
        return $points;
    }

    function part1()
    {
        $t = 0;
        // First, a massive time jump:
        $jump = 10237;
        $this->points = $this->movePoints($this->points, $jump);
        $t += $jump;
        $step = 1;
        // Now increment:
        while ($t < 10242) {
            $this->points = $this->movePoints($this->points, $step);
            $minX = $this->min_val($this->points, 'x');
            $maxX = $this->max_val($this->points, 'x');
            $minY = $this->min_val($this->points, 'y');
            $maxY = $this->max_val($this->points, 'y');
            $spanX = $maxX - $minX;
            $spanY = $maxY - $minY;
            echo "t=$t" .
                " x:[" . $minX . "," . $maxX . "]" .
                " y:[" . $minY . "," . $maxY . "]" .
                " sx=" . $spanX . " sy=" . $spanY .
                "\n";
            $this->printGrid($minX, $maxX, $minY, $maxY);
            $t += $step;
            // Wait for keypress (visually examine the grid after each loop):
            if (fgets(STDIN)) continue;
        }
    }

    function part2()
    {
        echo "10240 seconds elapsed for RLEZNRAN!gst\n";
    }

}