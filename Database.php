<?php

class Database
{
    protected $conn;

    function __construct() {

        $this->connect();
    }

    function __destruct() {

        $this->close();
    }

    function connect() {


        try{
            require_once("config.php");
            $user=DB_USER;
            $password=DB_PASSWORD;
            $host=DB_HOST;
            $database=DB_DATABASE;
            $conn = new PDO("mysql:host=$host;port=3306;dbname=$database", $user,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec('SET NAMES "utf8"');

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            exit();        }

        return $conn;
    }


    function close() {
         $conn=null;
        }
}