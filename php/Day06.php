<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day06 extends Solution implements TwoParter {

    public $data;

    function __construct() {
        $this->input = file('../inputs/06.txt');
        $this->keys = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWX'); // 50 nodes in problem
        // Create proper coordinates for the nodes:
        $this->nodes = array_combine($this->keys, array_map(function($pair) {
            $parts = explode(', ', $pair);
            return [
                'x' => intval($parts[0]),
                'y' => intval($parts[1]),
            ];
        }, $this->input));
    }

    function manhattanDist(array $p, array $q): int {
        return abs($p['x'] - $q['x']) + abs($p['y'] - $q['y']);
    }

    /*
     * Used for scaling down all the coordinate pairs to visualise them on a lower-res grid
     */
    function roundCoords(array $coords, int $factor): array {
        foreach ($coords as $k => $v) {
            $coords[$k] = ['x' => round($v['x'] / $factor), 'y' => round($v['y'] / $factor)];
        }
        return $coords;
    }

    /*
     * Outputs a section of the grid in ASCII format
     */
    function printGrid(int $x0, int $x1, int $y0, int $y1) {
        for ($y = $y0; $y < $y1; $y++) {
            for ($x = $x0; $x < $x1; $x++) {
                $cell = $this->grid[$y][$x];
                // make them all 4 chars:
                if ($cell == null) echo "_   ";
                else if (count($cell['owners']) > 1) echo ".   ";
                else echo $cell['owners'][0] . str_pad($cell['dist'], 2) . " ";
            }
            echo "\n";
        }
    }

    /*
     * Count how many cells of the input grid are null
     */
    function countNulls(array $grid): int {
        $nulls = 0;
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                if (is_null($cell)) $nulls++;
            }
        }
        return $nulls;
    }

    /*
     * Get a list of points which are $radius Manhattan distance away from the $centre
     * This can include invalid points (not in grid)
     */
    function getRadiusCandidates(array $centre, int $radius): array {
        $candidates = [];
        for ($x = $centre['x'] - $radius; $x < $centre['x'] + $radius; $x++) {
            for ($y = $centre['y'] - $radius; $y < $centre['y'] + $radius; $y++) {
                $loc = ['x' => $x, 'y' => $y];
                if ($this->manhattanDist($centre, $loc) == $radius) {
                    $candidates[] = $loc;
                }
            }
        }
        return $candidates;
    }

    /*
     * Count the number of grid cells which are 'owned' by each main node
     */
    function sizeOfEachGroup(array $grid): array {
        $counts = array_fill_keys($this->keys, 0);
        // Traverse grid and count every valid cell once:
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                if (count($cell['owners']) == 1) {
                    $counts[$cell['owners'][0]]++;
                }
            }
        }
        return $counts;
    }

    function part1() {
        // Lower resolution of data:
        $nodes_div_10 = $this->roundCoords($this->nodes, 10);

        // Set up grid:
        $this->grid = array_fill(0, 400, array_fill(0, 400, null));

        // Start marking territory around each node:
        foreach ($this->nodes as $nodeName => $v) {
            // Mark each node's cell:
            $this->grid[$v['y']][$v['x']] = [
                'owners' => [$nodeName],
                'dist' => 0
            ];

            for ($radius = 1; $radius < 90; $radius++) {
                $candidates = $this->getRadiusCandidates($v, $radius);

                foreach ($candidates as $c) {
                    $value = $this->grid[$c['y']][$c['x']];
                    // empty cell OR new lowest dist, replace data:
                    if ($value == null || $radius < $value['dist']) {
                        $this->grid[$c['y']][$c['x']] = [
                            'owners' => [$nodeName],
                            'dist' => $radius
                        ];
                    }
                    // equal dist, push new co-owner:
                    else if ($radius == $value['dist']) {
                        $this->grid[$c['y']][$c['x']]['owners'][] = $nodeName;
                    }

                }
            }
        }
        $this->printGrid(50,350,50,350);
        $sizes = array_values($this->sizeOfEachGroup($this->grid));
        arsort($sizes);
        var_dump($sizes);
        echo $this->countNulls($this->grid) . " nulls";
        // 5749 too high
        // 2687 too low
        // 3210 too low
        // 3326 no (wait 5...)
        // 3347 no (wait 5...)
        // 3518 no (wait 5...)
        // 3588 no (wait 10...)
        // 3954 no
    }

    function part2() {

    }

}