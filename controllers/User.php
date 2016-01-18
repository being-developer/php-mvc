<?php

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
            $sql="select distinct id,salt,password from users where email=?";
            $params=array();
            $params['email']=$email;
            $row = $this->conn->query($sql,"select",$params);


            if ($row->count > 0) {
                $hashed_value = $row->results[0]->password;
                $salt = $row->results[0]->salt;
                $user_id = $row->results[0]->id;
                $hashed_expected = hash_hmac('ripemd160', $password, $salt);
                if ($hashed_value == $hashed_expected) {
                    $token = $this->generateToken();
                    $params=array();
                    $params['token']=$token;
                    $params['active']=1;
                    $params['user_id']=$user_id;

                    if ($this->conn->insert('token', $params)) {
                        $message['message'] = "Succesfully logged in";
                        $message['token'] = $token;
                        $message['success'] = true;
                    } else
                        $message['message'] = 'Failed to login';




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
            $params=array();
            $params['email']=$email;
            $row = $this->conn->query("select *  from users where email = ?",'select',$params);

            if ($row->count==0) {
                $salt='randomstring';
                $password=hash_hmac('ripemd160', $password, $salt);

                $params=array();
                $params['name']=$name;
                $params['email']=$email;
                $params['password']=$password;
                $params['salt']=$salt;
                $params['active']=1;
                $params['created_at'] = date("Y-m-d h:i:sa");
                if ($this->conn->insert('users', $params)) {
                    $message['message'] = "User Succesfully Created";
                    $message['success'] = true;
                } else
                    $message['message'] = 'User not created';



            }
            else {
                $message['message'] = "user exists";
                $message['success'] = false;
            }


        }

        echo json_encode($message);
    }

    function logout($request=[])
    {

        $token=getallheaders()['token'];
        $message = array('message' => 'Invalid token', 'success' => false);
        if($token)
        {


               $sql="delete from token where token=?";
                $params=array();
                $params['token']=$token;
                $row = $this->conn->query($sql,'delete',$params);
                if(!$row->error) {
                    $message['message'] = 'successfully log out';
                    $message['success'] = true;
                }
                else{
                    $message['message']='Unable to logout';
                }


        }
        else{
            $message['message']="invalid parameter";
        }

        echo json_encode($message);

    }
}