<?php
// include required file
require_once 'init.php';
$pageTitle =  lang('result_breadcrumb_title');
$activePage = "index";
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
  redirect_user();
}

// validate param
if (!filter_input(INPUT_GET, 'search_txt', FILTER_SANITIZE_SPECIAL_CHARS)) {
  $url = "index.php";
  redirect_user(lang("redirect_search_message"), 5 ,$url);
}

$search_val = filter_input(INPUT_GET, 'search_txt', FILTER_SANITIZE_SPECIAL_CHARS);

// search breadcrumb
$items = array(
  lang("home") => 'href="index.php"',
  lang("result_breadcrumb_title") => 'class="active"'
);
breadcrumb($items);

// delete popup section
delete_popup(lang('delete_book_popup_text'), "temp");
// no delete popup section
no_delete_popup(lang('no_delete_book_popup_text'));

// search section
search_section("search_all.php", lang("search_home"), "result.php", $search_val);

// book box section
$container_id = "search-container";
container_site($container_id);

// to load items
load_script("load_result.php", $container_id, "", $search_val);

// footer section
include $tmpl . 'footer.php';