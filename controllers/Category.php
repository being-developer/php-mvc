<?php

class Category extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function add($request){

        $name=isset($request['name'])?$request['name']:null;
        $display_name=isset($request['display_name'])?$request['display_name']:null;
        $description=isset($request['description'])?$request['description']:null;
        $token=getallheaders()['token'];
        $message = array("message" => "Invalid parameters", "success" => false);
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $id=$this->getUserFromToken($token);
        $message['message']='No Permission to add Category';

        if($this->isActive($token) && $this->hasPermission($id,'manageCategory') ) {

            if ($name && $display_name) {


                $params = array();
                $params['name'] = $name;
                $params['display_name'] = $display_name;
                $params['description'] = $description;
                $params['created_at'] = date("Y-m-d h:i:sa");

                if ($this->conn->insert('category', $params)) {
                    $message['message'] = "Category Succesfully Created";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Category not created';
            }
            else{
                $message = array("message" => "Invalid parameters", "success" => false);
            }
        }
        echo json_encode($message);
    }


    function delete($request){
        $id=isset($request['id'])?$request['id']:null;
        $token=getallheaders()['token'];
        $message = array("message" => "Invalid parameters", "success" => false);
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $user_id=$this->getUserFromToken($token);
        $message['message']='No Permission to manage Category';

        if($this->isActive($token) && $this->hasPermission($user_id,'manageCategory') ) {
            if ($id) {

                $conditions = "id=$id";
                if ($this->conn->delete('category', $conditions)) {
                    $message['message'] = "Category Succesfully deleted";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Category not deleted';
            } else {
                $message = array("message" => "Invalid parameters", "success" => false);
            }
        }

            echo json_encode($message);


    }

    function update($request){
        $id=isset($request['id'])?$request['id']:null;
        $name=isset($request['name'])?$request['name']:null;
        $display_name=isset($request['display_name'])?$request['display_name']:null;
        $description=isset($request['description'])?$request['description']:null;
        $message = array("message" => "Invalid parameters",  "success" => false);
        $token=getallheaders()['token'];
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $user_id=$this->getUserFromToken($token);

        $message['message']='No Permission to manage Category';

        if($this->isActive($token)  && $this->hasPermission($user_id,'manageCategory') ) {
            if ($id) {

                $params = array();
                if ($name) $params['name'] = $name;
                if ($display_name) $params['display_name'] = $display_name;
                if ($description) $params['description'] = $description;
                $params['updated_at'] = date("Y-m-d h:i:sa");
                if ($this->conn->update('category', $id, $params)) {
                    $message['message'] = "Category Succesfully updated";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Category not updated';

            }
            else{
                $message = array("message" => "Invalid parameters",  "success" => false);
            }
        }
        echo json_encode($message);
    }

    function category($request){

        $message = array("message" => "Invalid parameters",  "success" => false);




        if($row=$this->conn->select('category'))
        {   $message['message']="Category ";
            $message['success']=true;
            $message['data']=$row->results;

        }
        else
            $message['message']='Category';
        echo json_encode($message);
    }

    function category_exist($id){


        $sql="select * from category where id =$id";
        $row=$this->con->query($sql,'select');
        if($row->count>0){
            return true;
        }
        else{
            return false;
        }
    }
}