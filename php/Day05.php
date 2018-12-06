<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day05 extends Solution implements TwoParter {

    public $data;

    function __construct() {
        $this->input = trim(file('../inputs/05.txt')[0]);
    }

    function sameApartFromCase(string $a, string $b): bool {
        return (strtoupper($a) == $b && strtolower($b) == $a) ||
                (strtoupper($b) == $a && strtolower($a) == $b);
    }

    function reduce(string $input): string {
        $reduced = $input;
        $pattern1 = '/(aA|bB|cC|dD|eE|fF|gG|hH|iI|jJ|kK|lL|mM|nN|oO|pP|qQ|rR|sS|tT|uU|vV|wW|xX|yY|zZ)/';
        $pattern2 = '/(Aa|Bb|Cc|Dd|Ee|Ff|Gg|Hh|Ii|Jj|Kk|Ll|Mm|Nn|Oo|Pp|Qq|Rr|Ss|Tt|Uu|Vv|Ww|Xx|Yy|Zz)/';
        while (preg_match($pattern1, $reduced) || preg_match($pattern2, $reduced)) {
            $reduced = preg_replace([$pattern1, $pattern2], '', $reduced);
        }
        return $reduced;
    }

    function part1() {
        // Find resulting length after replacement loop:
        echo strlen($this->reduce($this->input));
    }

    function part2() {
        $results = [];
        // Process whole alphabet:
        foreach (str_split('abcdefghijklmnopqrstuvwxyz') as $a) {
            $string = $this->input;
            // Strip char:
            echo "$a ";
            $string = preg_replace("/$a/", '', $string);
            $string = preg_replace("/".strtoupper($a)."/", '', $string);
            $length = strlen($this->reduce($string));
            $results[$a] = $length;
            echo "$length ";
        }
        // Find min:
        asort($results);
        echo "\nShortest: " . $results[array_keys($results)[0]];
    }

}