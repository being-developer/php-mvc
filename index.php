<?php

include "controllers/Login.php";
include "controllers/Logout.php";
include "controllers/Register.php";
include "controllers/Category.php";
include "controllers/test_api.php";
include "route.php";



$route=new route();


$route->add('/login','Login@login');
$route->add('/register','Register@register');
$route->add('/logout','Logout@logout');


/*
 * Product routes
 */

$route->add('/addProduct','Product@add');
$route->add('/deleteProduct','Product@delete');
$route->add('/updateProduct','Product@update');
$route->add('/editProduct','Product@edit');
$route->add('/product','Product@product');

/*
 * Category Routes
 */
$route->add('/addCategory','Category@add');
$route->add('/deleteCategory','Category@delete');
$route->add('/updateCategory','Category@update');
$route->add('/editCategory','Category@edit');
$route->add('/Category','Category@category');



$route->add('/home',"home");
$route->add('/test-api',"test_api_post");

$route->add('/change_password','change_password');

$route->add('/get_role','get_role');
$route->add('/is_verified','is_verified');

$route->submit();