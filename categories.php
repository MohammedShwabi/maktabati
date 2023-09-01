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
// to get the searcher text
$search_val = $_GET["search_txt"] ?? "";

// add category popup
add_cat();

// page title
page_title(lang("category_title"));

// search section
search_section("search.php", lang("search_category"), "", $search_val, "cat", "categories");

// delete popup section
delete_popup(lang('delete_category_popup_text'), "temp");

// no delete popup section
no_delete_popup(lang('no_delete_category_popup_text'));

// categories box section
$container_id = "category_container";
container_site($container_id);

// Sticky Button
sticky_button(lang("add_category"), "", "add_category");

if (empty($search_val)) {
    // to load items
    load_script("load_items.php", $container_id, "cat");
} else {
    // to load items
    load_script("load_items.php", $container_id, "cat", $search_val);
}

// footer section
include $tmpl . 'footer.php';
