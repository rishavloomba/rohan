<?php

require('auth.php');
header('Content-type: application/json');

$db = $_GET['db'];

if(isset($_GET['key']) and $_GET['key'] != '') {
    $val = urldecode($_GET['val']);
    $sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "` WHERE " . $_GET['key'] . " = '" . $val . "'";
} elseif(isset($_GET['nonzero']) and $_GET['nonzero'] != '') {
    $sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "` WHERE " . $_GET['col'] . " != '0' and " . $_GET['col'] . " != ''";
} else {
    $sql = "SELECT `dt`,`" . $_GET['col'] . "` FROM `" . $_GET['tb'] . "`";
}

if($user_valid and strpos($user_priv,$db) !== false) {
    echo SSP::line($_GET['callback'], $db, $sql);
}
?>
