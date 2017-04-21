<?php
$_['name']            = 'Blog Category';
//Статус Frontend редатора
$_['frontend_status'] = '1';
//GET параметр route в админке 
$_['backend_route']   = 'd_blog_module/category/edit';

$_['backend_route_regex'] = 'd_blog_module/category/*';
//GET параметр route на Frontend
$_['frontend_route']  = 'd_blog_module/category';
//GET параметр содержащий id страницы в админке
$_['backend_param']   = 'category_id';
//GET параметр содержащий id страницы на Frontend
$_['frontend_param']  = 'category_id';
//Путь для сохранения описания на Frontend
$_['edit_url']        = 'index.php?route=d_blog_module/category/editCategory';

$_['events']          = array(
    'admin/view/d_blog_module/category_form/after' => 'event/d_blog_module/view_category_after',
    'catalog/view/d_blog_module/category/before' => 'event/d_blog_module/view_category_before'
);