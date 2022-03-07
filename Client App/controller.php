<?php

class Controller
{
    function start() {
        require_once 'Board.php';
        require_once 'console_ui.php';

        $consoleUI = new ConsoleUI();
        $defaultUrl = 'http://cheon.atwebpages.com/c4';
        $consoleUI->welcome();
        echo ">Enter the server URL [default: http://cheon.atwebpages.com/c4/] \n";
        $url = readline();

        if ($url == '') {
            $url = $defaultUrl;
        }

        echo "Obtaining server information ....\n";
        echo "$url\n";

        $serverInfo =  @file_get_contents($url.'/info/index.php');

        if ($serverInfo === FALSE) {
            echo "Link is wrong!\n";
            return;
        }

        $arr = json_decode($serverInfo, true);
        $strategy = $consoleUI->promptForStrategy($arr);
        $width = $arr['width'];
        $height = $arr['height'];

        echo "**********************************\n";
        echo "Creating new game\n";

        $serverInfo =  @file_get_contents($url.'/new/index.php/?strategy='.$strategy);
        $arrGame = json_decode($serverInfo, true);
        $response = $arrGame['response'];
        $pid = $arrGame['pid'];

        if (!$response) {
            echo "Error: Unknown Strategy\n";
            return;
        }

        $url = $url.'/play/index.php/?pid='.$pid.'&move=';
        $endGame = 'Continue';
        $board = new Board($width, $height);
        $board->printBoard();

        while ($endGame == "Continue") {
            $endGame = $consoleUI->promptForMove($board, $url);
        }
        $consoleUI->thankYou($board, $endGame);
    }
}