<?php
include 'controllers/Controllers.php';
include "controllers/Brand.php";
include "controllers/Discount.php";
include "controllers/User.php";
include "controllers/Product.php";
include "controllers/Category.php";

include "route.php";



$route=new route();


$route->add('/login','User@login');
$route->add('/register','User@register');
$route->add('/logout','User@logout');


/*
 * Product routes
 */

$route->add('/addProduct','Product@add');
$route->add('/deleteProduct','Product@delete');
$route->add('/updateProduct/','Product@update');
$route->add('/product','Product@product');

/*
 * Brands Routes
 */
$route->add('/addBrand','Brand@add');
$route->add('/deleteBrand','Brand@delete');
$route->add('/updateBrand','Brand@update');
$route->add('/Brand','Brand@category');

/*
 * Category Routes
 */
$route->add('/addCategory','Category@add');
$route->add('/deleteCategory','Category@delete');
$route->add('/updateCategory','Category@update');
$route->add('/Category','Category@category');

/*
 * Discount Routes
 */
$route->add('/addDiscount','Discount@add');
$route->add('/deleteDiscount','Discount@delete');
$route->add('/updateDiscount','Discount@update');
$route->add('/Discount','Discount@category');



$route->submit();