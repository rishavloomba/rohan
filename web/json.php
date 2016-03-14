<?php

require('ssp.php');
header('Content-type: application/json');

$db = $_GET['db'];
$val = urldecode($_GET['val']);
$sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "` WHERE " . $_GET['key'] . " = '" . $val . "'";

echo SSP::simple($_GET['callback'], $db, $sql);

?>
