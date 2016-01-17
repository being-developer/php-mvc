<?php


class route
{
    private $_uri=array();
    private $_method=array();
    public function add($uri,$method='/')
    {
        $this->_uri[]='/'.trim($uri,'/');
        if($method!=null)
        {
            $this->_method[]=$method;
        }

    }
    public function submit()
    {
       $urigetparam= isset($_GET['id'])?$_GET['id']:'/';
        print_r($this->_uri);
        print_r($this->_method);
        foreach ($this->_uri as $key=>$value)
        {
            if(preg_match("#^/$urigetparam$#",$value))
            {
                if (isset($_POST))
                {
                    $post=$_POST;
                    $var=explode('@',$this->_method[$key]);
                    $class=$var[0];
                    $method=$var[1];
                    if(class_exists($class))
                    {
                        $instance= new $class();
                        $instance->$method($post);
                    }
                    else
                    {
                        echo "Class not exist";
                    }
                }
                else
                {
                    if(class_exists($class))
                    {
                        $instance= $class();
                        $instance->$method();
                    }
                    else
                    {
                        echo "Class not exist";
                    }
                }


            }


        }


    }

}