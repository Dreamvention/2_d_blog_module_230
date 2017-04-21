<?php
$_['name']            = 'Blog Author';
//Статус Frontend редатора
$_['frontend_status'] = '1';
//GET параметр route в админке 
$_['backend_route']   = 'd_blog_module/author/edit';

$_['backend_route_regex'] = 'd_blog_module/author/*';
//GET параметр route на Frontend
$_['frontend_route']  = 'd_blog_module/author';
//GET параметр содержащий id страницы в админке
$_['backend_param']   = 'author_id';
//GET параметр содержащий id страницы на Frontend
$_['frontend_param']  = 'user_id';
//Путь для сохранения описания на Frontend
$_['edit_url']        = 'index.php?route=d_blog_module/author/editAuthor';

$_['events']          = array(
    'admin/view/d_blog_module/author_form/after' => 'event/d_blog_module/view_author_after',
    'catalog/view/d_blog_module/author/before' => 'event/d_blog_module/view_author_before'
);