<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day02 extends Solution implements TwoParter {

    public $data;

    function __construct() {
        $this->data = file('../inputs/02.txt');
    }

    function countLetters(string $word): array {
        $counts = [];
        foreach (str_split($word) as $c) {
            if (array_key_exists($c, $counts)) {
                $counts[$c]++;
            }
            else {
                $counts[$c] = 1;
            }
        }
        return $counts;
    }

    function hasCount(int $num, array $hash): bool {
        return in_array($num, array_values($hash));
    }

    function part1() {
        $twos = 0;
        $threes = 0;
        foreach ($this->data as $boxid) {
            $counts = $this->countLetters($boxid);
            if ($this->hasCount(2, $counts)) $twos++;
            if ($this->hasCount(3, $counts)) $threes++;
        }
        $checksum = $twos * $threes;
        echo "Checksum: $checksum\n";
    }

    function findDifferentChars(string $a, string $b): array {
        $diffIndexes = [];
        for ($i = 0; $i < count(str_split($a)); $i++) {
            if ($a[$i] != $b[$i]) {
                $diffIndexes[] = $i;
            }
        }
        return $diffIndexes;
    }

    function part2() {
        // loop of O(N^2)
        for ($i = 0; $i < count($this->data) - 1; $i++) {
            for ($j = $i + 1; $j < count($this->data); $j++) {
                if (count($this->findDifferentChars($this->data[$i], $this->data[$j])) == 1) {
                    // 2 box ids differ by 1 char
                    die($this->data[$i] . "\n" . $this->data[$j]);
                }
            }
        }
    }
}
