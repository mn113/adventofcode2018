<?php namespace AOC\php;

use \AOC\php\Solution;
use \AOC\php\TwoParter;

class Day08 extends Solution implements TwoParter {

    private $lastLetter = 'A';

    const STATES = [
        'header1',
        'header2',
        'children',
        'metadata'
    ];

    function __construct()
    {
        $this->input = file('../inputs/08sample.txt')[0]; // list of numbers
        $this->data = array_map(function($str) {
            return intval(trim($str));
        }, explode(' ', $this->input));
    }

    function incrLetter() {
        $this->lastLetter++;
        if ($this->lastLetter > 'Z') $this->lastLetter = 'A';
    }

    function visitNode(int $i, string $state): string {
        $num = $this->data[$i];
        $parent = $this->lastLetter;
        $node = $this->tree[$this->lastLetter];

        // What state are we in?
        switch ($state) {
            case Day08::STATES[0]:
                // head of new chain, i.e. childCount node
                $this->tree[$this->lastLetter] = [
                    'name' => $this->lastLetter,
                    'parent' => $parent,
                    'childCount' => $num,
                    'metadataCount' => null,
                    'children' => [],
                    'metadata' => []
                ];
                // move on to metadataCount:
                return Day08::STATES[1];

            case Day08::STATES[1]:
                // metadataCount node:
                $this->tree[$this->lastLetter]['metadataCount'] = $num;
                // move on to children or metadata:
                if ($node['childrenCount'] > 0) {
                    // enter child:
                    $this->incrLetter();
                    return Day08::STATES[0];
                }
                else if ($node['metadataCount'] > 0) {
                    return Day08::STATES[3];
                }
                else {
                    // new node:
                    $this->incrLetter();
                    return Day08::STATES[0];
                }

            case Day08::STATES[2]:
                // children nodes:
                if (count($node['children']) < $node['childrenCount']) {
                    $this->tree[$this->lastLetter]['children'][] = $num;
                    // next child:
                    return Day08::STATES[2];
                }
                else if (count($node['metadata']) < $node['metadataCount']) {
                    // move on to metadata:
                    return Day08::STATES[3];
                }
                // new node:
                return Day08::STATES[0];

            case Day08::STATES[3]:
                // metadatum node:
                if (count($node['metadata']) < $node['metadataCount']) {
                    $this->tree[$this->lastLetter]['metadata'][] = $num;
                    // next metadatum:
                    return Day08::STATES[3];
                }
                // return to parent, as child:
                $this->lastLetter = $parent;
                return Day08::STATES[2];
        }
    }

    function part1() {
        $this->tree = [];
        $length = count($this->data);
        $state = Day08::STATES[0]; // header1 -> header2 -> children -> metadata
        // traverse list
        for ($i = 0; $i < $length; $i++) {
            echo implode(" : ", [$this->data[$i], $this->lastLetter, $state, "\n"]);
            $state = $this->visitNode($i, $state);
        }
        print_r($this->tree);
    }

    function part2() {

    }
}