<?php

class Logout
{
    protected $post_var;
    function __construct() {

    }

    function logout($request)
    {
        $token =isset($request['token'])?$request['token']:null;
        $message = array('message' => 'Invalid token', 'success' => false);
        if($token)
        {
            try {


                require_once("connection.php");
                $con = new DB_CONNECT();
                $conn = $con->connect();
                $stmt = $conn->prepare("delete from token where token=?");
                $stmt->bindParam(1,$token);
                $stmt->execute();

                $message['message']='successfully log out';
                $message['success']=true;

            }
            catch(PDOException $e){
                 $message['message']=$e->getCode()." : ".$e->getMessage();

            }
            $con->close();
        }
        else{
            $message['message']="invalid parameter";
        }



        echo json_encode($message);

    }
}