<?php

class test_api_post
{
    public $post_var;
    function __construct($post) {
        $this->post_var=$post;
        $this->test_api($post);
    }

    function test_api($request)
    {
        $test=$request['test'];
        $message=array('message'=>"api up and running",'test'=>$test,'success'=>true);
        print_r (json_encode($message));
    }
}