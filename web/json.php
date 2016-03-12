<?php

require('ssp.php');
require('config.php');
header('Content-type: application/json');
if (isset($_GET['chart']) and isset($config[$_GET['chart']])) {
    echo SSP::simple($_GET['callback'], $config[$_GET['chart']]['db'], $config[$_GET['chart']]['sql']);
}

?>
