<?php
// include required file
require_once 'init.php';
$pageTitle =  lang('publishers');
$activePage = "publishers";
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
// validate param
if (!filter_input(INPUT_GET, 'pub', FILTER_VALIDATE_INT)) {
    $url = "publishers.php";
    redirect_user(lang("redirect_pub_message"), 5 ,$url);
}

$pub_id = filter_input(INPUT_GET, 'pub', FILTER_VALIDATE_INT);

$results = get_data(SELECT_PUBLISHER_INFO, [$pub_id]);

//  author breadcrumb
// name => url : href= "example.php"
// name => class: class="active" this for the current items
// note : the last element should be the active class element
$items = array(
    lang("home") => 'href="index.php"',
    lang("publishers") => 'href="publishers.php"',
    $results['pub_name'] => 'class="active"'
);
breadcrumb($items);

// author details section
$pub_detail = lang('was_established') . $results['pub_name'] . lang('in_date') . $results['establishment_date'] . lang('by') . $results['owner'] . lang('sequential_deposit_no') . $results['sequential_deposit_no'];
section_detail($results['pub_name'], $pub_detail);

// delete popup section
delete_popup(lang('delete_book_popup_text'), "temp");
// no delete popup section
no_delete_popup(lang('no_delete_book_popup_text'));

// publisher box section
$container_id = "publisher_container";
container_site($container_id);

// to load items
load_script("load_section.php", $container_id, "pub", $pub_id);

// footer section
include $tmpl . 'footer.php';
