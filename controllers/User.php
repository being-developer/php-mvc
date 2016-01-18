<?php
include 'Controllers.php';
class User extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function login($request){

        $email = isset($request['email'])?$request['email']:null;
        $password = isset($request['password'])?$request['password']:null;
        $message = array("message" => "User does not exist", "token" => null, "success" => false);
        if($email && $password) {

            $stmt = $this->conn->prepare("select distinct id,salt,password from users where email=?");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            $row = $stmt->fetch();
            if ($stmt->rowCount() > 0) {
                $hashed_value = $row['password'];
                $salt = $row['salt'];
                $user_id = $row['id'];
                $hashed_expected = hash_hmac('ripemd160', $password, $salt);
                if ($hashed_value == $hashed_expected) {
                    $token = $this->generateToken();
                    try {

                        $sql = "INSERT INTO `token`( `token`, `active`, `user_id`) VALUES (?,1,?)";
                        $insert = $this->conn->prepare($sql);
                        $insert->bindParam(1, $token);
                        $insert->bindParam(2, $user_id);
                        $insert->execute();
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                    $message['message'] = "Succesfully looged in ";
                    $message['token'] = $token;
                    $message['success'] = true;


                } else {
                    $message['message'] = "password is incorrect";

                }
            }


        }
        else
        {
            $message['message']='Invalid parameters';
        }

        echo json_encode($message);


    }

    function register($request){
        $name = isset($request['name']) ? $request['name'] : null;
        $email = isset($request['email']) ? $request['email'] : null;
        $password = isset($request['password']) ? $request['password'] : null;
        $message = array("message" => " invalid post params",  "success" => false);

        if ($name && $password && $email) {

            $stmt = $this->conn->prepare("select *  from users where email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $row = $stmt->fetch();
            if ($stmt->rowCount() == 0) {
                $salt='randomstring';
                $password=hash_hmac('ripemd160', $password, $salt);
                try {
                    $sql = "INSERT INTO `users`( `name`, `email`, `password`,`salt`,`active`, `created_at`) VALUES (?,?,?,?,1,Now())";
                    $insert = $this->conn->prepare($sql);
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


        }

        echo json_encode($message);
    }

    function logout($request)
    {
        $token =isset($request['token'])?$request['token']:null;
        $message = array('message' => 'Invalid token', 'success' => false);
        if($token)
        {
            try {


                $stmt = $this->conn->prepare("delete from token where token=?");
                $stmt->bindParam(1,$token);
                $stmt->execute();

                $message['message']='successfully log out';
                $message['success']=true;

            }
            catch(PDOException $e){
                $message['message']=$e->getCode()." : ".$e->getMessage();

            }

        }
        else{
            $message['message']="invalid parameter";
        }

        echo json_encode($message);

    }
}