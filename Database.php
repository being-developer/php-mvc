<?php

class Database
{
    protected $conn;
    protected $error=false;
    protected $result=[];
    protected $count=0;
    protected $message='';

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
            $this->conn = new PDO("mysql:host=$host;port=3306;dbname=$database", $user,$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('SET NAMES "utf8"');

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            exit();        }

        return $this->conn;
    }


    function query($sql,$type, $params = array())
    {

        $this->error = false;
        $this->query = $this->conn->prepare($sql);
        try{
        if ($this->query) {

            if (count($params)) {

                $counter = 1;

                foreach ($params as $param) {
                    $this->query->bindValue($counter, $param);
                    $counter++;
                }

            }

            if($this ->query->execute() ) {

                $this->results = $this->query-> fetchAll(PDO::FETCH_OBJ);
                $this->count   = $this->query-> rowCount();


            }

        }

        }
        catch(PDOException $e){
             $this->message=$e->getMessage();
             if($type=='select')
             $this->error=true;
        }
        return $this;
    }

    function delete($table,$conditions){

        $sql="Delete from $table where $conditions";

        if(! $this->query($sql,'delete')->error) {

            return true;
        }
        return false;
    }

    public function insert($table, $params = array()) {



        if(count($params)) {

            $keys = array_keys($params);
            $values = null;
            $x=1;

            foreach($params as $field) {
                $values.= "?";
                if($x < count($params)) {
                    $values.= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES({$values})";


            if(! $this->query($sql, $params.'insert')->error) {

                return true;
            }

        }
        return false;

    }

    public function update($table, $id, $params=array()) {

        $set = '';
        $x=1;

        foreach($params as $name => $value) {
            $set.= "`{$name}` = ?";
            if($x < count($params)) {
                $set.= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $params,'update')->error()) {

            return true;
        }

        return false;
    }

    public function select($table,$params=array()){
        $sql="Select * from $table ";
        return $this->query($sql,'select');
    }

    function close() {
         $this->conn=null;
        }
    function error()
    {
        return $this->error;
    }
}