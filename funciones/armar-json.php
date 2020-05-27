<?php if (!isset($_SESSION)) {session_start();}

$json = $_POST['json'];
$formato = $_POST['formato'];
$json_string = json_encode($json);
echo $json_string;

?>