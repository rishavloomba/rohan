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
            $db = @new PDO("sqlite:../sqlite/{$db_file}",
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

    static function line ($request, $db_file, $sql) {
        $conn = self::db_connect($db_file);
        $data = self::sql_exec($conn, $sql);
        $output = self::data_line($data);
        return $request . '(' . $output . ')';
    }

    static function data_line ($data) {
        $out = array();
        for ($i=0, $ien=count($data); $i<$ien; $i++) {
            $row[0] = strtotime($data[$i][0]) * 1000;
            $row[1] = floatval(str_replace(',','',$data[$i][1]));
            $out[] = $row;
        }
        return json_encode($out);
    }

}

?>
