<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day04 extends Solution implements TwoParter {

    public $data;

    function __construct() {
        $this->raw = file('../inputs/04.txt');
        $this->data = array_map('self::parseLine', $this->raw);
        $this->sortData();
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): array {
        preg_match('/^\[(([\d\-]{10}) (\d\d):(\d\d))\] (wake|fall|Guard #\d+)/', $line, $matches);
        return [
            'timestamp' => strtotime($matches[1]),
            'date'=> $matches[2],
            'h' => $matches[3],
            'm' => $matches[4],
            'type' => $matches[5]
        ];
    }

    function prettyPrint($data, $typeFilter = null) {
        foreach ($data as $row) {
            extract($row);
            if ($type === $typeFilter || is_null($typeFilter)) {
                echo "$timestamp $date $h:$m $type\n";
            }
        }
    }

    /*
     * Sort the data chronologically ascending by date, hour, minute
     */
    function sortData() {
        usort($this->data, function($a, $b) {
            return strcmp($a['date'].$a['h'].$a['m'], $b['date'].$b['h'].$b['m']);
        });
    }

    function countAllSleep($guardFilter = 'none') {
        $sleep = [];
        $currentGuard = null;
        $slept = null;
        foreach ($this->data as $row) {
            extract($row);
            if (strpos($type, 'G') === 0) {
                $currentGuard = $type;
                if (!array_key_exists($currentGuard, $sleep)) {
                    $sleep[$currentGuard] = 0;
                }
            }
            else if ($type == 'fall') {
                $slept = $timestamp;
            }
            else if ($type == 'wake' && $slept) {
                $sleep[$currentGuard] += $timestamp - $slept;
                $slept = null;
            }
            // Output block:
            if ($currentGuard === $guardFilter) {
                echo "$date $h:$m $type\n";
            }
        }
        return $sleep;
    }

    function findSleepiestMinute($guard) {
        $minutes = range(0,59);
        $currentGuard = null;
        foreach ($this->data as $row) {
            extract($row);
            if (strpos($type, 'G') === 0) {
                $currentGuard = $type;
            }
            else if ($type == 'fall') {
                $slept = $timestamp;
            }
            else if ($type == 'wake' && $slept) {
                $sleep[$currentGuard] += $timestamp - $slept;
                $slept = null;
            }
        }
        return arsort($minutes);
    }

    function part1() {
        $this->prettyPrint($this->data);
        $sleep = $this->countAllSleep();
        arsort($sleep);
        //var_dump($sleep);
        $sleepiest = array_keys($sleep)[0];
        echo "Sleepiest: $sleepiest with {$sleep[$sleepiest]} minutes\n";
        // Print entries:
        //var_dump($this->findSleepiestMinute($sleepiest));
        echo 39 * 2953;
    }

    function part2() {

    }

}