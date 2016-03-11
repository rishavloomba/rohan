<?php
$db_file = 'csindex.com.cn.db';
$sql = 'select dt, pe from bk_jtsyl where name="沪深A股"';
$columns = array('dt', 'pe');
require( 'ssp.php' );
echo SSP::simple($_GET, $db_file, $sql, $columns);

?>