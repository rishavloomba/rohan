<?php

require('ssp.php');
header('Content-type: application/json');

$db = $_GET['db'];
$sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "` WHERE " . $_GET['key'] . " = '" . $_GET['val'] . "'";

echo SSP::simple($_GET['callback'], $db, $sql);

?>
