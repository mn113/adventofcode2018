<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day09 extends Solution implements TwoParter {

    private $marbList = [0];
    private $scores;

    function __construct() {
        $case1 = [9,25]; // => high score 32 ok
        $case2 = [9,48]; // => hs 63 ok
        $case3 = [1,48]; // => hs 95 ok
        $case4 = [10,1618]; // => hs 8317 ok
        $case5 = [13,7999]; // => hs 146373 ok
        $case6 = [17,1104]; // => hs 2764 ok
        $case7 = [462,71938]; // => hs ??

        $this->players = $case7[0];
        $this->turns = $case7[1];
        $this->scores = array_fill(1, $this->players, 0);
    }

    /*
     * Splice the next marble in after index 1
     */
    function addMarble($val) {
        if ($val % 1000 == 0) echo "Marble $val\n";
        $this->pointer = ($this->pointer + 2) % count($this->marbList);
        array_splice($this->marbList, $this->pointer, 0, [$val]);
    }

    /*
     * Splice a marble out at index -7
     */
    function removeMarble() {
        if ($this->marbList)
        $this->pointer = (count($this->marbList) + $this->pointer - 7) % count($this->marbList);
        $removed = array_splice($this->marbList, $this->pointer, 1);
        return $removed[0];
    }

    function part1() {
        $m = 1;
        $player = 1;
        $this->pointer = 0;
        while ($m <= $this->turns) {
            if ($m % 23 == 0) {
                $r = $this->removeMarble();
                $this->scores[$player] += $m + $r;
                //echo "Player $player gained $m + $r points\n";
            }
            else {
                $this->addMarble($m);
            }
            //echo "Player $player used marble $m\n";
            //echo "[" . implode(" ", $this->marbList) . "]\n";
            //echo "Pointing at {$this->marbList[$this->pointer]}\n";
            $m++;
            $player++;
            if ($player > $this->players) $player = 1;
        }
        echo "Last marble played, next is $m\n";
        // Sort by value ascending:
        arsort($this->scores);
        //var_dump($this->scores);
        echo "High score: " . $this->scores[array_keys($this->scores)[0]];
    }

    function part2() {

    }
}