<?php

require('auth.php');
header('Content-type: application/json');

$db = $_GET['db'];

if(isset($_GET['key']) and $_GET['key'] != '') {
    $val = urldecode($_GET['val']);
    $sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "` WHERE " . $_GET['key'] . " = '" . $val . "'";
} else {
    $sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "`";
}

if(in_array($db, $user_priv)) {
    echo SSP::line($_GET['callback'], $db, $sql);
}
?>
