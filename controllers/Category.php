<?php

class Category
{
    function __construct()
    {

    }

    function add($request){

        $name=isset($request['name'])?$request['name']:null;
        $display_name=isset($request['display_name'])?$request['display_name']:null;
        $description=isset($request['description'])?$request['description']:null;


        $message = array("message" => "Invalid parameters",  "success" => false);
        if($name && $display_name){

            require_once('Database.php');
            $params =array();
            $params['name']=$name;
            $params['display_name']=$display_name;
            $params['description']=$description;
            $params['created_at']=date("Y-m-d h:i:sa");
            $con = new Database();
            if($con->insert('category',$params))
            {   $message['message']="Category Succesfully Created";
                $message['success']=true;
            }
            else
                $message['message']='Category not created';
        }
        echo json_encode($message);
    }


    function delete($request){
        $id=isset($request['id'])?$request['id']:null;
        $message = array("message" => "Invalid parameters",  "success" => false);
        if($id){
            require_once('Database.php');
            $con = new Database();
            $conditions="id=$id";
            if($con->delete('category',$conditions))
            {   $message['message']="Category Succesfully deleted";
                $message['success']=true;
            }
            else
                $message['message']='Category not deleted';
        }

            echo json_encode($message);


    }

    function update($request){
        $id=isset($request['id'])?$request['id']:null;
        $name=isset($request['name'])?$request['name']:null;
        $display_name=isset($request['display_name'])?$request['display_name']:null;
        $description=isset($request['description'])?$request['description']:null;
        $message = array("message" => "Invalid parameters",  "success" => false);
        if($id){
            require_once('Database.php');
            $con = new Database();
            $params=array();
            if($name)$params['name']=$name;
            if($display_name)$params['display_name']=$display_name;
            if($description)$params['description']=$description;
            $params['updated_at']=date("Y-m-d h:i:sa");

            if($con->update('category',$id,$params))
            {   $message['message']="Category Succesfully updated";
                $message['success']=true;
            }
            else
                $message['message']='Category not updated';

        }
        echo json_encode($message);
    }

    function category($request){

        $message = array("message" => "Invalid parameters",  "success" => false);
        require_once('Database.php');
        $con = new Database();



        if($row=$con->select('category'))
        {   $message['message']="Category Succesfully updated";
            $message['success']=true;
            $message['data']=$row->results;

        }
        else
            $message['message']='Category not updated';
        echo json_encode($message);
    }

}