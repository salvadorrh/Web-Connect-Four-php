<?php

class Board
{
    var int $width;
    var int $height;
    var array $boardMatrix;
    var array $movesUser = array();
    var array $movesServer = array();


    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
        $this->boardMatrix = array();
        $colBoard = array();

        for ($i = 0; $i < $width; $i++) {
            $colBoard[] = "."; // One dimensional array of 0's
        }

        for ($j = 0; $j < $height; $j++) {
            $this->boardMatrix[] = $colBoard;
        }
    }

    function drop($slot, $token) {
        for ($i = $this->height -1 ; $i >= 0; $i--) {
            if ($this->boardMatrix[$i][$slot] == ".") {
                $this->boardMatrix[$i][$slot] = $token;
                return;
            }
        }
        if ($token == 'X') $this->movesUser[] = $slot+1;
        else $this->movesServer[] = $slot+1;
    }

    function printBoard() {
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                echo $this->boardMatrix[$i][$j]." ";
            }
            echo "\n";
        }

        for ($i = 1; $i <= $this->width; $i++) {
            echo "$i ";
        }
        echo "\n";
    }

    function isSlotOpen($slot): bool
    {
        return $this->boardMatrix[0][$slot] == ".";
    }

    function printServerMoves() {
        $i = 0;
        for (; $i < count($this->movesServer) - 1 ; $i++) {
            echo $this->movesServer[$i].", ";
        }
        echo $this->movesServer[$i]."\n";
    }

    function printUserMoves() {
        $i = 0;
        for (; $i < count($this->movesUser) -1 ; $i++) {
            echo $this->movesUser[$i].", ";
        }
        echo $this->movesUser[$i]."\n";
    }
}