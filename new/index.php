<?php // index.php
define('STRATEGY', 'strategy');  //constant
$strategies = array('Smart', 'Random');
/* If strategy not specified in URL */
if (!array_key_exists(STRATEGY, $_GET)) {
    $info = array('response' => false, 'reason' => "Strategy not specified");
    echo json_encode($info);
    exit;
}
/* Select strategy, either Smart or Random */
$strategy = $_GET[STRATEGY];

//Require_once
/*require_once '../common/constants.php';
require_once '../common/utils.php';
*/
require_once '../play/Game.php';

$pid = uniqid();
$infoNew = new GameNew(true, $pid);
if ($strategy == 'Smart') {
    echo json_encode($infoNew);
}
else if ($strategy == 'Random') {
    echo json_encode($infoNew);
}
else {
    $info = array('response' => false, 'reason' => "Unknown strategy");
    echo json_encode($info);
    exit;
}

$game = new Game($strategy);
$base_dir = dirname(dirname(__FILE__)) . "/play/data/";
$file = $base_dir.$pid.".txt";
$jsonEncode = $game->toJsonString();
//file_put_contents($file, json_encode($game));
file_put_contents($file, $jsonEncode);


class GameNew {
    public $response;
    public $pid;

    function __construct($response, $pid) {
        $this->response = $response;
        $this->pid = $pid;
    }
}
?>
