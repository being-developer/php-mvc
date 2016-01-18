<?php

class Discount extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function add($request){


        $discount=isset($request['discount'])?$request['discount']:null;
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
        $message['message']='No Permission to add Discount';

        if($this->isActive($token) && $this->hasPermission($id,'manageDiscount') ) {

            if ($discount ) {


                $params = array();
                $params['discount'] = $discount;
                $params['description'] = $description;
                $params['created_at'] = date("Y-m-d h:i:sa");

                if ($this->conn->insert('discount', $params)) {
                    $message['message'] = "Discount Succesfully Created";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Discount not created';
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
        $message['message']='No Permission to manage Discount';

        if($this->isActive($token) && $this->hasPermission($user_id,'manageDiscount') ) {
            if ($id) {

                $conditions = "id=$id";
                if ($this->conn->delete('discount', $conditions)) {
                    $message['message'] = "Discount Succesfully deleted";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Discount not deleted';
            } else {
                $message = array("message" => "Invalid parameters", "success" => false);
            }
        }

        echo json_encode($message);


    }

    function update($request){
        $id=isset($request['id'])?$request['id']:null;
        $discount=isset($request['discount'])?$request['discount']:null;
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

        $message['message']='No Permission to manage Discount';

        if($this->isActive($token)  && $this->hasPermission($user_id,'manageDiscount') ) {
            if ($id) {

                $params = array();
                if ($discount) $params['discount'] = $discount;
                if ($description) $params['description'] = $description;
                $params['updated_at'] = date("Y-m-d h:i:sa");
                if ($this->conn->update('discount', $id, $params)) {
                    $message['message'] = "Discount Succesfully updated";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Discount not updated';

            }
            else{
                $message = array("message" => "Invalid parameters",  "success" => false);
            }
        }
        echo json_encode($message);
    }

    function discount($request){

        $message = array("message" => "Invalid parameters",  "success" => false);




        if($row=$this->conn->select('discount'))
        {   $message['message']="Discount ";
            $message['success']=true;
            $message['data']=$row->results;

        }
        else
            $message['message']='Discount';
        echo json_encode($message);
    }


}