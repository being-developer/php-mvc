<?php



class Login
{
    private $post_var;
    function __construct() {


    }
    function login($request)
    {
        $email = isset($request['email'])?$request['email']:null;
        $password = isset($request['password'])?$request['password']:null;
        $message = array("message" => "User does not exist", "token" => null, "success" => false);
       if($email && $password) {
           require_once("Database.php");
           $con = new Database();
           $conn = $con->connect();
           $stmt = $conn->prepare("select distinct id,salt,password from users where email=?");
           $stmt->bindParam(1, $email);
           $stmt->execute();

           $row = $stmt->fetch();
           if ($stmt->rowCount() > 0) {
               $hashed_value = $row['password'];
               $salt = $row['salt'];
               $user_id = $row['id'];
               $hashed_expected = hash_hmac('ripemd160', $password, $salt);
               if ($hashed_value == $hashed_expected) {

                   require_once("utils.php");
                   $utility = new utils();
                   $token = $utility->generate_token();
                   try {

                       $sql = "INSERT INTO `token`( `token`, `active`, `user_id`) VALUES (?,1,?)";
                       $insert = $conn->prepare($sql);
                       $insert->bindParam(1, $token);
                       $insert->bindParam(2, $user_id);
                       $insert->execute();
                   } catch (PDOException $e) {
                       echo $e->getMessage();
                   }
                   $message['message'] = "Succesfully looged in ";
                   $message['token'] = $token;
                   $message['success'] = true;
                   echo json_encode($message);

               } else {
                   $message['message'] = "password is incorrect";
                   echo json_encode($message);
               }
           } else {
               echo json_encode($message);
           }

           $con->close();
       }
        else
        {
            $message['message']='Invalid parameters';
        }

        echo json_encode($message);
    }

}