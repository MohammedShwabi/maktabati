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

if (
    !(filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT) === 0 || filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT)) ||
    !filter_input(INPUT_POST, 'limit', FILTER_VALIDATE_INT) ||
    !filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS) ||
    !filter_input(INPUT_POST, 'section', FILTER_VALIDATE_INT)

) {
    exit();
}

// get all variable needed
$start = filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT);
$limit = filter_input(INPUT_POST, 'limit', FILTER_VALIDATE_INT);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
$section_id = filter_input(INPUT_POST, 'section', FILTER_VALIDATE_INT);

//store the result of select statement in $results var
$results = get_all_data(get_sql($type), [$section_id, $start, $limit]);

// check if there is an item match the searched text
if ($results != 0) {
    // to print all items
    foreach ($results as $output) {
        get_fun($type, $output, $section_id);
    }
}

// to get the proper sql statement depend on table name
function get_sql($type)
{
    $sql = '';
    switch ($type) {
        case 'cat':
            $sql = SELECT_CATEGORY_BOOK;
            break;
        case 'pub':
            $sql = SELECT_PUBLISHER_BOOK;
            break;
        case 'author':
            $sql = SELECT_AUTHOR_BOOK;
            break;
    }
    return $sql;
}

// to get the proper show template function depend on table name
function get_fun($type, $output)
{
    // get all variable
    $book_id = $output["book_id"];
    $book_img = $output["photo"];
    // make delete icon function
    $delete_fun = " onclick=\"deletePop('book_delete.php', {'id':'$book_id', 'img':'$book_img'} )\" ";

    switch ($type) {
        case 'cat':
            // initialize var
            $author = array(
                "name" => $output["author_name"],
                "url" => "author_section.php?auth=" . $output["author_id"],
            );

            book($book_img, $book_id, $output["title"], $output["rating"], $delete_fun, $author);
            break;

        case 'pub':
            // initialize var
            $author = array(
                "name" => $output["author_name"],
                "url" => "author_section.php?auth=" . $output["author_id"],
            );

            book($book_img, $book_id, $output["title"], $output["rating"], $delete_fun, $author);
            break;

        case 'author':
            // initialize var
            $category = array(
                "name" => $output["cat_name"],
                "url" => "category_section.php?cat=" . $output["cat_id"],
            );

            book($book_img, $book_id, $output["title"], $output["rating"], $delete_fun, $category);
            break;
    }
}
