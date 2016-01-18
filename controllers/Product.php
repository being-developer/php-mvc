<?php

class Product extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function add($request){

        $name=isset($request['name'])?$request['name']:null;
        $description=isset($request['description'])?$request['description']:null;
        $token=getallheaders()['token'];
        $price=isset($request['price'])?$request['price']:null;
        $category=isset($request['category_id'])?$request['category_id']:null;
        $brand=isset($request['brand_id'])?$request['brand_id']:null;
        $discount=isset($request['discount_id'])?$request['discount_id']:null;
        $fid=isset($request['fid'])?$request['fid']:null;


        $message = array("message" => "Invalid parameters", "success" => false);
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $user_id=$this->getUserFromToken($token);
        $message['message']='No Permission to add Product';

        if($this->isActive($token) && $this->hasPermission($user_id,'manageProduct') ) {
            $add=true;

            if(!$this->category_exist($category)) $add=false;
            if(!$this->brands_exist($brand))$add=false;
            if(!$this->discount_exist($discount))$add=false;


            $message['message']='Invalid Parameter ';

            if ($name && $price && $category && $brand && $discount && $add) {


                $params = array();
                $params['name'] = $name;
                $params['description'] = $description;
                $params['price'] = $price;
                $params['category'] = $category;
                $params['brands'] = $brand;
                $params['discount'] = $discount;
                $params['fid']=$fid;
                $params['created_by'] = $user_id;
                $params['created_at'] = date("Y-m-d h:i:sa");


                if ($this->conn->insert('products', $params)) {
                    $message['message'] = "Product Succesfully Created";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Product not created';
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
        $message['message']='No Permission to manage Product';

        if($this->isActive($token) && $this->hasPermission($user_id,'manageProduct') ) {
            if ($id) {

                $conditions = "id=$id";
                if ($this->conn->delete('products', $conditions)) {
                    $message['message'] = "Product Succesfully deleted";
                    $message['success'] = true;
                } else
                    $message['message'] = 'Product not deleted';
            } else {
                $message = array("message" => "Invalid parameters", "success" => false);
            }
        }

        echo json_encode($message);


    }

    function update($request){

        $id=isset($request['id'])?$request['id']:null;
        $name=isset($request['name'])?$request['name']:null;
        $description=isset($request['description'])?$request['description']:null;
        $price=isset($request['price'])?$request['price']:null;
        $category=isset($request['category_id'])?$request['category_id']:null;
        $brand=isset($request['brand_id'])?$request['brand_id']:null;
        $discount=isset($request['discount_id'])?$request['discount_id']:null;
        $fid=isset($request['fid'])?$request['fid']:null;
        $message = array("message" => "Invalid parameters",  "success" => false);
        $token=getallheaders()['token'];
        if(!$token)
        {
            $message['message']='Unauthorized Access';
            echo json_encode($message);
            exit();
        }
        $user_id=$this->getUserFromToken($token);

        $message['message']='No Permission to manage Product';

        if($this->isActive($token)  && $this->hasPermission($user_id,'manageProduct') ) {
            if ($id) {

                $update=true;
                $params = array();
                if ($name) $params['name'] = $name;
                if ($description) $params['description'] = $description;
                if ($price) $params['price'] = $price;
                if($category)
                    if($this->category_exist($category))$params['category'] = $category;
                    else $update=false;
                if($brand)
                    if( $this->brands_exist($brand))$params['brands'] = $brand;
                    else $update=false;
                if($discount)
                    if( $this->discount_exist($discount))$params['discount']=$discount;
                    else $update=false;
                if ($fid)$params['fid']=$fid;

                $params['updated_by']=$user_id;
                $params['updated_at'] = date("Y-m-d h:i:sa");
                if($update) {
                    if ($this->conn->update('products', $id, $params)) {
                        $message['message'] = "Product Succesfully updated";
                        $message['success'] = true;
                    } else
                        $message['message'] = 'Product not updated';
                }
                else{
                    $message = array("message" => "Invalid parameters",  "success" => false);
                }
            }
            else{
                $message = array("message" => "Invalid parameters",  "success" => false);
            }
        }
        echo json_encode($message);
    }

    function product($request){

        $message = array("message" => "Invalid parameters",  "success" => false);
        if($row=$this->conn->select('product'))
        {   $message['message']="Product ";
            $message['success']=true;
            $message['data']=$row->results;

        }
        else
            $message['message']='Product';
        echo json_encode($message);
    }


}