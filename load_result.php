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
    !filter_input(INPUT_POST, 'section', FILTER_SANITIZE_SPECIAL_CHARS)
) {
    exit();
}

// get all variable needed
$start = filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT);
$limit = filter_input(INPUT_POST, 'limit', FILTER_VALIDATE_INT);
$search_txt = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_SPECIAL_CHARS);

$val = array(
    "%" . $search_txt . "%",
    "%" . $search_txt . "%",
    "%" . $search_txt . "%",
    $start,
    $limit
);
// store the result of select statement in $results var
$results = get_all_data(SELECT_SEARCH_LOAD_ALL, $val);


// check if there is an item match the searched text
if ($results != 0) {

    // to print all items
    foreach ($results as $output) {
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
    }
}
