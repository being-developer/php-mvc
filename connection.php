<?php

class DB_CONNECT {
    protected $con;

    function __construct() {

        $this->connect();
    }

    function __destruct() {

        $this->close();
    }

    function connect() {

        try
        {
            $user="root";
            $password="root";

            $con = new PDO('mysql:host=127.0.0.1;port=3306;dbname=wingify', $user,$password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $con->exec('SET NAMES "utf8"');
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            exit();
        };
        return $con;
    }

    function close() {

        $con=null;
    }
}

?>