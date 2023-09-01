<?php
// initialize session
ob_start();
session_start();
session_regenerate_id();

// include required file
require_once 'init.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    exit();
}
// validate param
if (
    !(filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT) === 0 || filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT)) ||
    !filter_input(INPUT_POST, 'limit', FILTER_VALIDATE_INT) ||
    !filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS)
) {
    exit();
}

// get all variable needed
$start = filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT);
$limit = filter_input(INPUT_POST, 'limit', FILTER_VALIDATE_INT);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);

// for search section only
if (filter_input(INPUT_POST, 'section', FILTER_SANITIZE_SPECIAL_CHARS)) {
    // get variable needed
    $section_name = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_SPECIAL_CHARS);

    //store the result of select statement in $results var
    $results = get_all_data(get_search_sql($type), ["%" . $section_name . "%", $start, $limit]);
} else {
    //store the result of select statement in $results var
    $results = get_all_data(get_section_sql($type), [$start, $limit]);
}

// check if there is an item match the searched text
if ($results != 0) {
    // to print all items
    foreach ($results as $output) {
        get_fun($type, $output);
    }
}

// to get the proper sql statement depend on table name
function get_section_sql($type)
{
    $sql = '';
    switch ($type) {
        case 'cat':
            $sql = SELECT_CATEGORY;
            break;
        case 'pub':
            $sql = SELECT_PUBLISHER;
            break;
        case 'author':
            $sql = SELECT_AUTHOR;
            break;
        case 'book':
            $sql = SELECT_BOOK;
            break;
    }
    return $sql;
}

// to get the proper sql statement depend on table name
function get_search_sql($type)
{
    $sql = '';
    switch ($type) {
        case 'cat':
            $sql = SEARCH_CATEGORY;
            break;
        case 'pub':
            $sql = SEARCH_PUBLISHER;
            break;
        case 'author':
            $sql = SEARCH_AUTHOR;
            break;
    }
    return $sql;
}

// to get the proper show template function depend on table name
function get_fun($type, $output)
{
    switch ($type) {
        case 'cat':
            // get all variable
            $cat_id = $output["cat_id"];
            $category = array(
                "name" => $output["cat_name"],
                "url" => "category_section.php?cat=" . $cat_id,
                // "edit_url" => "category_edit.php?cat=" . $cat_id,
                "edit_url" => 'data-bs-toggle="modal" data-bs-target="#add_category" cat-data=' . $cat_id,
                "edit_txt" => lang('edit_cat'),
                "delete_txt" => lang('delete_cat'),
            );
            // make delete icon function
            $delete_fun = " onclick=\"deletePop('category_delete.php', {'id':'$cat_id'} )\" ";

            category($output["book_no"], $category, $delete_fun);
            break;

        case 'pub':
            // get all variable
            $pub_id = $output["pub_id"];
            $publisher = array(
                "name" => $output["pub_name"],
                "url" => "publisher_section.php?pub=" . $pub_id,
                // "edit_url" => "edit_publisher.php?pub=" . $pub_id,
                "edit_url" => 'href="edit_publisher.php?pub=' . $pub_id . '"',
                "edit_txt" => lang('edit_pub'),
                "delete_txt" => lang('delete_pub'),
            );
            // make delete icon function
            $delete_fun = " onclick=\"deletePop('publisher_delete.php', {'id':'$pub_id'} )\" ";

            category($output["book_no"], $publisher, $delete_fun);
            break;

        case 'author':
            // get all variable
            $auth_id = $output["author_id"];
            $auth_img = $output["author_img"];
            // make delete icon function
            $delete_fun = " onclick=\"deletePop('author_delete.php', {'id':'$auth_id', 'img':'$auth_img'} )\" ";
            author($auth_id, $output["author_name"], $auth_img, $output["book_no"], $delete_fun);
            break;

        case 'book':
            // get all variable
            $book_id = $output["book_id"];
            $book_img = $output["photo"];
            // make delete icon function
            $delete_fun = " onclick=\"deletePop('book_delete.php', {'id':'$book_id', 'img':'$book_img'} )\" ";
            // initialize var
            $author = array(
                "name" => $output["author_name"],
                "url" => "author_section.php?auth=" . $output["author_id"],
            );

            book($book_img, $book_id, $output["title"], $output["rating"], $delete_fun, $author);
            break;
    }
}
