<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day23 extends Solution implements TwoParter {

    public $bots;

    function __construct() {
        $this->raw = file('../inputs/23.txt');
        $this->bots = array_map('self::parseLine', $this->raw);
        echo count($this->bots) . " bots loaded\n";
        usort($this->bots, function(Bot $a, Bot $b) {
            return $b->getRadius() - $a->getRadius();
        });
    }

    /*
     * Parses a single line of input and extracts relevant numerical values
     */
    static function parseLine(string $line): Bot {
        preg_match('/^pos=<(\-?\d+),(\-?\d+),(\-?\d+)>, r=(\d+)$/', $line, $matches);
        return new Bot([
            'x' => intval($matches[1]),
            'y' => intval($matches[2]),
            'z' => intval($matches[3]),
            'r' => intval($matches[4])
        ]);
    }

    function manhattanDist3D(Bot $p, Bot $q): int {
        return abs($p->x - $q->x) + abs($p->y - $q->y) + abs($p->z - $q->z);
    }

    function strongestN(array $bots, int $n = 1): array {
        return array_slice($bots, 0, $n);
    }

    function smallerBotsInRange(Bot $chief): array {
        return array_filter($this->bots, function(Bot $bot) use ($chief) {
            return $bot !== $chief
                   && $bot->getRadius() <= $chief->getRadius()
                   && $this->manhattanDist3D($chief, $bot) <= $chief->getRadius();
        });
    }

    function part1() {
        $chief = $this->bots[0];
        echo "Strongest bot: $chief\n";
        $inrange = $this->smallerBotsInRange($chief);
        echo count($inrange) . " in range of " . $chief . "\n";
    }

    function part2() {
        $xVals = array_map(function($bot) { return $bot->x; }, $this->bots);
        $yVals = array_map(function($bot) { return $bot->y; }, $this->bots);
        $zVals = array_map(function($bot) { return $bot->z; }, $this->bots);
        $loX = min($xVals);
        $hiX = max($xVals);
        $loY = min($yVals);
        $hiY = max($yVals);
        $loZ = min($zVals);
        $hiZ = max($zVals);
        echo "x: $loX - $hiX y: $loY - $hiY z: $loZ - $hiZ\n";

        $guess0 = new Bot([
            'x' => 65000000,
            'y' => 48000000,
            'z' => 50000000,
            'r' => POSIX_RLIMIT_INFINITY
        ]);
        $inrange = array_filter($this->bots, function(Bot $bot) use ($guess0) {
            return $this->manhattanDist3D($guess0, $bot) <= $bot->getRadius();
        });
        echo count($inrange) . " in range of guess0\n";

//        $level = 0;
//        $lockedon = [];
//        $bots = $this->bots;
//        while ($level < 5) {
//            echo $level++ . "\n";
//            $levelGroup = $this->strongestN($bots, 1);
//            foreach ($levelGroup as $currentBot) {
//                $lockedon[] = $currentBot;
//                $inrange = $this->smallerBotsInRange($currentBot);
//                echo count($inrange) . " in range of " . $currentBot . "\n";
//                $bots = $inrange;
//            }
//        }
    }
}

class Bot {
    public $x;
    public $y;
    public $z;
    public $r;

    public function __construct($params)
    {
        $this->x = $params['x'];
        $this->y = $params['y'];
        $this->z = $params['z'];
        $this->r = $params['r'];
    }

    public function getRadius() {
        return $this->r;
    }

    public function __toString() {
        return 'r' . $this->getRadius();
    }
}