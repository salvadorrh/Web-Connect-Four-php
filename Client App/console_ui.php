<?php
require_once 'Board.php';

class ConsoleUI {
    function welcome() {
        echo "Welcome to Connect Four game!\n";
    }

    function thankYou($board, $endGame) {
        if ($endGame == 'Error') {
            echo "Error: with server\n";
        }
        else if ($endGame == 'Win') {
            echo "\nYou have won the game! :)\n";
        }
        else if ($endGame == 'Loose') {
            echo "\nYou have lost the game! :( \n";
        }
        else if ($endGame == 'Draw') {
            echo "\nThere has been a draw!! \n";
        }

        echo "Moves by user: ";
        $board->printUserMoves();
        echo "Moves by server: ";
        $board->printServerMoves();
        echo "Thank you for playing Connect Four!\n";
        echo "**********************************\n";
    }

    function promptForStrategy($arr) {
        $pickStrategy = ">Select the server strategy: ";

        for ($i = 0; $i < count($arr['strategies']); $i++) {
            $j = $i + 1;
            $pickStrategy .= " $j ";
            $pickStrategy .= $arr['strategies'][$i];
        }

        $pickStrategy .= " [default = 1] ";

        $correctStrategy = false;
        $selectedStrategy = $arr['strategies'][1]; //Default Random
        while(!$correctStrategy) {
            echo $pickStrategy;
            $line = readline();
            if ($line == "") {
                $selectedStrategy = $arr['strategies'][0];
                break;
            }
            $newSelection = intval($line);
            $newSelection -= 1;
            if ($newSelection >= 0 && $newSelection < count($arr['strategies'])) {
                $selectedStrategy = $arr['strategies'][$newSelection];
                $correctStrategy = true;
            }
            else {
                $newSelection += 1;
                echo "Invalid selection : $newSelection\n";
            }
        }

        echo "Selected strategy: $selectedStrategy\n";
        return $selectedStrategy;
    }

    function promptForMove($board, $url): string
    {
        $width = $board->width;
        $pickSlot = ">Select a slot [1- $width]: ";
        $correctSlot = false;
        $slot = -1;
        while (!$correctSlot) {
            echo $pickSlot;
            $line = readline();
            $slot = intval($line);
            if ($slot > 0 && $slot <= $width && $board->isSlotOpen($slot - 1)) {
                $board->drop($slot-1, "X");
                echo "Selected slot by player: $slot \n";
                $correctSlot = true;
                $board->movesUser[] = $slot;
            }
            else {
                echo "Invalid slot: $slot \n";
            }
        }

        $slot--;
        $url = $url.$slot;
        $serverInfo =  @file_get_contents($url);
        $arrGame = json_decode($serverInfo, true);
        // echo "Json: ".$serverInfo."\n";
        $response = $arrGame['response'];
        $ack_move = $arrGame['ack_move'];

        if (!$response) {
            $reason = $response['reason'];
            return "Error: $reason \n";
        }

        if ($ack_move['isWin']) {
            $board->printBoard();
            $this->pointPlayerMove($slot);
            $winningRow = $ack_move['row'];
            $this->printWinRow($winningRow);
            return 'Win';
        }
        else if ($ack_move['isDraw']) {
            $board->printBoard();
            $this->pointPlayerMove($slot);
            return 'Draw';
        }

        // Drop Slot for server move
        $move =  $arrGame['move'];
        $serverSlot = $move['slot'];
        $serverSlot++;
        echo "Server move: $serverSlot \n";
        $serverSlot--;
        $board->drop($serverSlot, "O");
        $board->printBoard();
        $this->pointPlayerMove($slot);
        $board->movesServer[] = $serverSlot+1;

        if ($move['isWin']) {
            $winningRow = $move['row'];
            $this->printWinRow($winningRow);
            return 'Loose';
        }
        else if ($move['isDraw']) {
            return 'Draw';
        }
        return 'Continue';
    }

    function pointPlayerMove($move) {
        for ($i = 0; $i < $move; $i++) {
            echo "  ";
        }
        echo "*\n";
    }

    function printWinRow($row) {
        echo "Winning Row: ";
        for ($i = 0; $i < count($row) -1; $i++) {
            echo $row[$i].", ";
        }
        echo $row[$i]."\n";
    }
}

