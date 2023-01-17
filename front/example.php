<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>ГОВОРЕЦ ЛЕОНИД ПЗ-53<h1>
            <form method="GET" action="example.php">
                <h3>Сообщение:</h3> <input type="text" name="case"><br>
                <input type="submit" value="Отправить">
            </form>
            <br>
</body>
</html><?php
$sock  = stream_socket_client("tcp://127.0.0.1:61523", $errno, $errstr);
if (!$sock) {
    echo "$errstr ($errno)<br />\n";
} else {
    if(isset($_GET['case'])){
        fwrite($sock, $_GET['case']."\r\n");
        echo fread($sock, 4096)."\n";
    }
    fclose($sock);
}
?>