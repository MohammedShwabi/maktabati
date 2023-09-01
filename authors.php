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
// to get the searcher text
$search_val = $_GET["search_txt"] ?? "";

// page title
page_title(lang("author_title"));

// search section
search_section("search.php", lang("search_author"), "", $search_val, "author", "author");

// delete popup section
delete_popup(lang('delete_author_popup_text'), "temp");

// no delete popup section
no_delete_popup(lang('no_delete_author_popup_text'));

// author box section 
$container_id = "author_container";
container_site($container_id);

// Sticky Button
sticky_button(lang("add_author"), "add_author.php");

if (empty($search_val)) {
    // to load items
    load_script("load_items.php", $container_id, "author");
} else {
    // to load items
    load_script("load_items.php", $container_id, "author", $search_val);
}
// footer section
include $tmpl . 'footer.php';
