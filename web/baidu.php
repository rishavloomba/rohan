<?php

$table = 'baidu';
$primaryKey = 'id';
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'st', 'dt' => 1 ),
    array( 'db' => 'ht', 'dt' => 2 ),
    array( 'db' => 'dt', 'dt' => 3 )
);
$sql_details = array(
    'user' => '',
    'pass' => '',
    'db'   => '../sqlite/search_engine.db'
);

require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
