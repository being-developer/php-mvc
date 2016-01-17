<?php


class utils
{

    function php_version()
    {
        echo phpinfo();
    }

    function generate_token()
    {
        $length=30;
        $token = bin2hex(openssl_random_pseudo_bytes($length));
        return $token;
    }


    function genrate_user_id()
    {    $c = uniqid (rand(), true);
        $md5c = md5($c);
        return $md5c;
    }

    function get_user_id_from_token($token,$conn)
    {
        $statement = $conn->prepare("select user_id from is_login where token = :token");
        $statement->bindParam(':token', $token);
        $statement->execute();
        $row = $statement->fetch();
        if ($statement->rowCount() > 0) {
            return $row['user_id'];
        } else {
            return -1;
        }
    }

    function check_mobileno($mobile)
    {
        if (strlen($mobile) == 10) return true;
        else return false;
    }

    function  islogin($token)
    {
        $con = new DB_CONNECT();
        $conn = $con->connect();
        $statement = $conn->prepare("select user_id from is_login where token = :token");
        $statement->bindParam(':token', $token);
        $statement->execute();
        $row = $statement->fetch();
        if ($statement->rowCount() > 0) {
            $con->close();
            return $row['user_id'];
        } else {
            $con->close();
            return -1;
        }
    }








}