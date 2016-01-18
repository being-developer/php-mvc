<?php
include 'Controllers.php';
class Brand extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function add($request){

        $name=isset($request['name'])?$request['name']:null;
        $display_name=isset($request['display_name'])?$request['display_name']:null;
        $description=isset($request['description'])?$request['description']:null;
        $token=isset($request['token'])?$request['token']:null;
        $message = array("message" => "Invalid parameters", "success" => false);
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $id=$this->getUserFromToken($token);
        $message['message']='No Permission to add Brand';

        if($this->isActive($token) && $this->hasPermission($id,'manageBrand') ) {

            if ($name && $display_name) {


                $params = array();
                $params['name'] = $name;
                $params['display_name'] = $display_name;
                $params['description'] = $description;
                $params['created_at'] = date("Y-m-d h:i:sa");

                if ($this->conn->insert('brands', $params)) {
                    $message['message'] = "Brand Succesfully Created";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Brand not created';
            }
            else{
                $message = array("message" => "Invalid parameters", "success" => false);
            }
        }
        echo json_encode($message);
    }


    function delete($request){
        $id=isset($request['id'])?$request['id']:null;
        $token=isset($request['token'])?$request['token']:null;
        $message = array("message" => "Invalid parameters", "success" => false);
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $user_id=$this->getUserFromToken($token);
        $message['message']='No Permission to manage Brand';

        if($this->isActive($token) && $this->hasPermission($user_id,'manageBrand') ) {
            if ($id) {

                $conditions = "id=$id";
                if ($this->con->delete('brands', $conditions)) {
                    $message['message'] = "Brand Succesfully deleted";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Brand not deleted';
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
        $token=isset($request['token'])?$request['token']:null;
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $user_id=$this->getUserFromToken($token);

        $message['message']='No Permission to manage Brand';

        if($this->isActive($token)  && $this->hasPermission($user_id,'manageBrand') ) {
            if ($id) {

                $params = array();
                if ($name) $params['name'] = $name;
                if ($display_name) $params['display_name'] = $display_name;
                if ($description) $params['description'] = $description;
                $params['updated_at'] = date("Y-m-d h:i:sa");
                if ($this->conn->update('brands', $id, $params)) {
                    $message['message'] = "Brand Succesfully updated";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Brand not updated';

            }
            else{
                $message = array("message" => "Invalid parameters",  "success" => false);
            }
        }
        echo json_encode($message);
    }

    function brands($request){

        $message = array("message" => "Invalid parameters",  "success" => false);




        if($row=$this->conn->select('brands'))
        {   $message['message']="Brand ";
            $message['success']=true;
            $message['data']=$row->results;

        }
        else
            $message['message']='Brand';
        echo json_encode($message);
    }

    function brands_exist($id){


        $sql="select * from brands where id =$id";
        $row=$this->conn->query($sql,'select');
        if($row->count>0){
            return true;
        }
        else{
            return false;
        }
    }
}