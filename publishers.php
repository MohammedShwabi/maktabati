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
// to get the searcher text
$search_val = filter_input(INPUT_GET, 'search_txt', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";

// page title
page_title(lang("publisher_title"));

// search section
search_section("search.php", lang("search_publisher"), "", $search_val, "pub", "publisher");

// delete popup section
delete_popup(lang('delete_publisher_popup_text'), "temp");

// no delete popup section
no_delete_popup(lang('no_delete_publisher_popup_text'));

// publisher box section
$container_id = "publisher_container";
container_site($container_id);

// Sticky Button
sticky_button(lang("add_publisher"), "add_publisher.php");

if (empty($search_val)) {
    // to load items
    load_script("load_items.php", $container_id, "pub");
} else {
    // to load items
    load_script("load_items.php", $container_id, "pub", $search_val);
}

// footer section
include $tmpl . 'footer.php';
