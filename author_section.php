<?php
// include required file
require_once 'init.php';
$pageTitle =  lang('authors');
$activePage = "authors";
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

// validate param
if (!filter_input(INPUT_GET, 'auth', FILTER_VALIDATE_INT)) {
    $url = "authors.php";
    redirect_user(lang("redirect_auth_message"), 5, $url);
}


$author_id = filter_input(INPUT_GET, 'auth', FILTER_VALIDATE_INT);

//to get data form database
$results = get_data(SELECT_AUTHOR_INFO, [$author_id]);

//  author breadcrumb
// name => url : href= "example.php"
// name => class: class="active" this for the current items
// note : the last element should be the active class element
$items = array(
    lang("home") => 'href="index.php"',
    lang("authors") => 'href="authors.php"',
    $results['author_name'] => 'class="active"'
);
breadcrumb($items);

// author details section
section_detail($results['author_name'], $results['author_description'], $results['author_img']);

// delete popup section
delete_popup(lang('delete_book_popup_text'), "temp");
// no delete popup section
no_delete_popup(lang('no_delete_book_popup_text'));

// author box section 
$container_id = "author_container";
container_site($container_id);

// to load items
load_script("load_section.php", $container_id, "author", $author_id);

// footer section
include $tmpl . 'footer.php';
