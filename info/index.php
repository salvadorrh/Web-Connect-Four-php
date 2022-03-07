<?php // index.php

$strategies = array("Smart" => "SmartStrategy", "Random" => "RandomStrategy");
const WIDTH = 7;
const HEIGHT = 6;

$info = new GameInfo(WIDTH, HEIGHT, array_keys($strategies));
echo json_encode($info);

class GameInfo {
    public $width;
    public $height;
    public $strategies;
    function __construct($width, $height, $strategies)
    {
        $this->width=$width;
        $this->height=$height;
        $this->strategies=$strategies;
    }
}
?>




