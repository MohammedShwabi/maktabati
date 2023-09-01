<?php
// include required file
require_once 'init.php';
$pageTitle =  lang('categories');
$activePage = "categories";
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

// validate param
if (!filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT)) {
    $url = "categories.php";
    redirect_user(lang("redirect_cat_message"), 5, $url);
}

$cat_id = filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT);

//to get data form database
$results = get_data(SELECT_CATEGORY_INFO, [$cat_id]);

//  category breadcrumb

// name => url : href= "example.php"
// name => class: class="active" this for the current items
// note : the last element should be the active class element
$items = array(
    lang("home") => 'href="index.php"',
    lang("categories") => 'href="categories.php"',
    $results['cat_name'] => 'class="active"'
);
breadcrumb($items);

// delete popup section
delete_popup(lang('delete_book_popup_text'), "temp");
// no delete popup section
no_delete_popup(lang('no_delete_book_popup_text'));

// categories box section
$container_id = "category_container";
container_site($container_id);

// to load items
load_script("load_section.php", $container_id, "cat", $cat_id);

// footer section
include $tmpl . 'footer.php';
