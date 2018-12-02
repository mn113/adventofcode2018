<?php namespace AOC\php;

require_once __DIR__ . '/autoload.php';

// Which day and part was passed?
$options = getopt("d:p:");
$day = $options['d'];
$part = $options['p'];

switch($day) {
    // Leave room for days which need special treatment

    default:
        $dayClass = "\AOC\php\Day$day";
        if (!class_exists($dayClass)) {
            die("No valid day argument found.");
        }
        $today = new $dayClass();

        if (!isset($part)) {
            $today->part1();
            $today->part2();
        }
        else if ($part == 1) {
            $today->part1();
        }
        else if ($part == 2) {
            $today->part2();
        }
        break;
}