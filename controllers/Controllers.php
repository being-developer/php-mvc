<?php

include 'Database.php';
class Controller{

    protected $conn=null;
    function __construct()
    {
        $this->conn=new Database();
    }

    function getUserFromToken($token){

        $sql='Select u.id from token as t
              left outer join users as u on u.id=t.user_id
              where token= ? and t.active=1';
        $params=array();
        $params['token']=$token;
        $row=$this->conn->query($sql,'select',$params);

        if(!$row->error)
        {
            return $row->results[0]->id;
        }
        return 0;;
    }

    function hasPermission($id,$permission){
        $sql='Select * from permissions as p
              left outer join permission_role as pr on p.id=pr.permission_id
              left outer join roles as r on r.id=pr.role_id
              left outer join role_user as rs on r.id=rs.role_id
              left outer join users as u on rs.user_id=u.id
              where u.id= ? and p.name=?';
        $params=array();
        $params['id']=$id;
        $params['name']=$permission;
        $row=$this->conn->query($sql,'select',$params);
        if(!$row->error)
        {
            return true;
        }
        return false;

    }
    function isActive($token){

        $sql='Select * from token
              where token= ? and active=1';
        $params=array();
        $params['token']=$token;
        $row=$this->conn->query($sql,'select',$params);

        if(!$row->error)
        {
            return true;
        }
        return false;
    }

    function generateToken()
    {
        $length=30;
        $token = bin2hex(openssl_random_pseudo_bytes($length));
        return $token;
    }


}
