<?php namespace AOC\php;

require_once __DIR__ . '/autoload.php';

// Which day was passed?
$options = getopt("d:");
$day = $options['d'];

switch($day) {
    case '01':
        $today = new Day01();
        $today->part1();
        $today->part2();
        break;

    default:
        $dayClass = "\AOC\php\Day$day";
        if (!class_exists($dayClass)) {
            die("No valid day argument found.\n");
        }
        $today = new $dayClass();
        $today->part1();
        $today->part2();
        break;
}