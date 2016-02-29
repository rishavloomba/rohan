<?php

// DB table to use
$table = 'baidu';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'id', 'dt' => 0 ),
	array( 'db' => 'st', 'dt' => 1 ),
	array( 'db' => 'ht', 'dt' => 2 ),
	array( 'db' => 'dt', 'dt' => 3 )
);

// SQL server connection information
$sql_details = array(
	'user' => '',
	'pass' => '',
	'db'   => '../sqlite/search_engine.db'
);

require( 'ssp.class.php' );

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);


