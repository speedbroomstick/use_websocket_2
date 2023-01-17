<?php
use Workerman\Worker;

require_once __DIR__ . '/vendor/autoload.php';

$ws_worker = new Worker('tcp://127.0.0.1:61523');

$ws_worker->count = 4;

$ws_worker->onConnect = function ($connection) {
    echo "New connection\n";
    echo "Клиент " . $connection->getRemoteIp()." соединился.\n";
};

$ws_worker->onMessage = function ($connection, $data) {
    $comand = explode(" ", $data);
    echo "<".$data;
    if($comand[0]) {
        switch ($comand[0]) {
            case "stroka":
                if($comand[1] != null && $comand[2] == null) {
                    $strochka_zamen = array();
                    $strochka = strval($comand[1]);
                    $chet = 1;
                    for ($i = 0; $i < strlen($strochka) - 1; $i++) {
                        if ($chet % 4 == 0) {
                            array_push($strochka_zamen, '%');
                        } else {
                            array_push($strochka_zamen, $strochka[$i]);
                        }
                        $chet++;
                    }
                    echo ">" . "200 ОК " . "\n" . implode("", $strochka_zamen) . "\n";
                    $connection->send("200 ОК " . "\n" . implode("", $strochka_zamen));
                    break;
                }
                else{
                    echo ">"."500 NOT FOUND ARGUMENTS OR NOT CORRECT"."\n";
                    $connection->send("500 NOT FOUND ARGUMENTS OR NOT CORRECT");
                }
                break;
            case "math":
                if($comand[1] != null && $comand[2] != null) {
                    $itog = (int)$comand[1]+(int)$comand[2];
                    echo ">"."200 ОК "."\n".$itog."\n";
                    $connection->send("200 ОК "."\n".$itog);
                }
                else{
                    echo ">"."500 NOT FOUND ARGUMENTS"."\n";
                    $connection->send("500 NOT FOUND ARGUMENTS");
                }
                break;
            case "count":
                if($comand[1] != null && $comand[2] == null){
                    $strochka = strval($comand[1]);
                    $count_c=(int)strlen($strochka)-2;
                    echo ">"."200 ОК "."\n".$count_c."\n";
                    $connection->send("200 ОК "."\n".$count_c);
                }
                else{
                    echo ">"."500 NOT FOUND ARGUMENTS OR NOT CORRECT"."\n";
                    $connection->send("500 NOT FOUND ARGUMENTS OR NOT CORRECT");
                }
                break;
            default:
                echo ">"."404 NOT FOUND COMMAND"."\n";
                $connection->send("404 NOT FOUND COMMAND");
                break;
        }
    }
};
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n"."\n";
    echo "\n";
};

Worker::runAll();

?>