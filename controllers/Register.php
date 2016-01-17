<?php


class Register
{
    private $post_var;
    function __construct()
    {

    }
    function register($request)
    {
        $name = isset($request['name']) ? $request['name'] : null;
        $email = isset($request['email']) ? $request['email'] : null;
        $password = isset($request['password']) ? $request['password'] : null;
        $message = array("message" => " invalid post params",  "success" => false);

        if ($name && $password && $email) {

            require_once("utils.php");
            require_once("Database.php");
            $utility = new utils();
            $con = new Database();
            $conn = $con->connect();
            $stmt = $conn->prepare("select *  from users where email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $row = $stmt->fetch();
            if ($stmt->rowCount() == 0) {
                  $salt='randomstring';
                  $password=hash_hmac('ripemd160', $password, $salt);
                try {
                    $sql = "INSERT INTO `users`( `name`, `email`, `password`,`salt`,`active`, `created_at`) VALUES (?,?,?,?,1,Now())";
                    $insert = $conn->prepare($sql);
                    $insert->bindParam(1, $name);
                    $insert->bindParam(2, $email);
                    $insert->bindParam(3, $password);
                    $insert->bindParam(4, $salt);
                    $insert->execute();
                    $message['message'] = "user successfully Created";
                    $message['success'] = true;
                }
                catch(PDOException $e){

                    $message['message']=$e->getCode()." : ".$e->getMessage();
                }
            }
            else {
                $message['message'] = "user exists";
                $message['success'] = false;
            }

            $con->close();
        }

            echo json_encode($message);

    }
}