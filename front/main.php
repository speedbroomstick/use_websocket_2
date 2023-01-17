<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>laba17</title>
</head>
<body style="text-align: center;">
<div style="--bs-bg-opacity: .6;" class="bg-warning bg-gradient text-dark">  
    <h1>ГОВОРЕЦ ЛЕОНИД ПЗ-53<h1>
    <form method="POST" action="laba.php">
    <h3>Адрес:</h3> 
       <input type="text" name="adres"><br>
     <h3 >Порт:</h3> <input type="text" name="port"><br>
        <input type="submit" class="btn btn-light"  value="Подключиться"><br>
    </form>
    <form method="GET" action="laba.php">
    <input type="hidden" name="exit" value="true">
    <input type="submit" class="btn btn-light" value="Отключиться"><br><br>
    </form>
    <form method="GET" action="laba.php">
    <h3>Сообщение:</h3> <input type="text" name="case"><br>
        <input type="submit" class="btn btn-light" value="Отправить">
    </form>
    <br>
</body>
<script>
    //ws = new WebSocket("ws://127.0.0.1:61523");
   // ws.onmessage = function(evt) {alert(evt.data);};
</script>
</html>
<?php
if(isset($_GET['exit']) && $_GET['exit']=="true"){
session_start();
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
echo 'Отключение произошло успешно';
}
?>
<?php
session_start();
if(isset($_POST['adres']) || (isset($_SESSION['adres']) && isset($_SESSION['port']) )){
    if(!isset($_SESSION['adres']) && !isset($_SESSION['port']) ){
$_SESSION['adres'] = $_POST['adres'];;
$_SESSION['port'] = $_POST['port'];
    }

$port = $_SESSION['port'];
$address = gethostbyname($_SESSION['adres']);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if($_SESSION['adres'] == "127.0.0.1") {
    $sock = stream_socket_client("tcp://127.0.0.1:61523", $errno, $errstr);
    if (!$sock) {
        echo "$errstr ($errno)<br />\n";
    } else {
        if (isset($_GET['case'])) {
            fwrite($sock, $_GET['case'] . "\r\n");
            echo '</div><br><textarea style="width:100%; height:500px;">'.">".$_GET['case']."\n"."<".fread($sock, 4096).'</textarea>';

        }
        fclose($sock);
    }
}

if(socket_connect($socket, $address, $port)){
echo 'соединение успешно, молодцы!!!!!!!!!!';

}
else{
    echo 'Какой вы плохой, соединение не успешное!';
}
if($_SESSION['adres'] != "127.0.0.1"){
if(isset($_GET['case'])) {
    $_GET['case'] = $_GET['case'] . "\r\n";
    $_GET['case'] .= "Host: " . $_SESSION['adres'] . "\r\n";
    $_GET['case'] .= "Connection: Close\r\n\r\n";
    socket_write($socket, $_GET['case'], strlen($_GET['case']));
    while ($read = socket_read($socket, 2048)) {
        $result .= $read;
    }
    echo '</div><br><textarea style="width:100%; height:500px;">'.">".$_GET['case']."\n"."<".$result.'</textarea>';
}
socket_close($socket);
}
}
?>