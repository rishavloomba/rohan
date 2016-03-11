<?php

class SSP {

    static function sql_exec ($conn, $sql) {
        $stmt = $conn->prepare($sql);
        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    static function fatal ($msg) {
        echo json_encode(array("error"=>$msg));
        exit(0);
    }

    static function db_connect ($db_file) {
        try {
            $db = @new PDO("sqlite:{$db_file}",
                           null,
                           null,
                           array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                          );
        }
        catch (PDOException $e) {
            self::fatal("An error occurred while connecting to the database. " .
                        "The error reported by the server was: ".$e->getMessage()
                       );
        }
        return $db;
    }

    static function simple ($request, $db_file, $sql, $columns) {
        $conn = self::db_connect($db_file);
        $data = self::sql_exec($conn, $sql);
        $output = self::data_output($data, $columns);
        return $request['callback'] . '(' . $output . ')';
    }

    static function data_output ($data, $columns) {
        $out = array();
        for ($i=0, $ien=count($data); $i<$ien; $i++) {
            for ($j=0, $jen=count($columns); $j<$jen; $j++) {
                if ($j == 0) {
                    $row[$j] = strtotime($data[$i][$columns[$j]]) * 1000;
                }
                else {
                    $row[$j] = floatval($data[$i][$columns[$j]]);
                }
            }
            $out[] = $row;
        }
        return json_encode($out);
    }

}

?>
