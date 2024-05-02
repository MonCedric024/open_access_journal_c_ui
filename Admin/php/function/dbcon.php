<?php
if (!function_exists('connect_to_database')) {
    function connect_to_database()
    {
        // $string = "mysql:host=srv1320.hstgr.io;dbname=u277379385_qcuj2024";
        $string = "mysql:host=localhost;dbname=u277379385_qcuj2024";
        $con = new PDO($string, 'u277379385_qcuj2024', 'Qcujournal1234.');

        // $string = "mysql:host=localhost;dbname=journal";
        // $con = new PDO($string, 'root', '');

        if (!$con) {
            return false;
        }

        return $con;
    }
}

if (!function_exists('execute_query')) {
    function execute_query($query, $vars = array(), $isInsert = false)
    {
        $con = connect_to_database();

        if (!$con) {
            return false;
        }

        $stm = $con->prepare($query);
        $check = $stm->execute($vars);

        if ($check) {
            if ($isInsert) {
                return $con->lastInsertId();
            }

            $data = $stm->fetchAll(PDO::FETCH_OBJ);

            if (count($data) > 0) {
                return $data;
            }
        }

        return false;
    }
}
?>