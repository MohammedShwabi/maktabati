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
if (!filter_input(INPUT_POST, 'search_txt', FILTER_SANITIZE_SPECIAL_CHARS)) {
    exit();
}

// get all variable needed
$search_txt = filter_input(INPUT_POST, 'search_txt', FILTER_SANITIZE_SPECIAL_CHARS);

//store the result of select statement in $results var
$results = get_all_data(SELECT_SEARCH_ALL, ["%" . $search_txt . "%", "%" . $search_txt . "%", "%" . $search_txt . "%", "%" . $search_txt . "%"]);

// check if there is an item match the searched text
if ($results != 0) {
    // to print all items
    foreach ($results as $output) {
        echo "<a href='result.php?search_txt=" . $output["title"]  . "' class='list-group-item list-group-item-action search-item'>" . $output["title"] . "</a>";
    }
} else {
    echo ("<a href='#' class='list-group-item list-group-item-action disabled' tabindex='-1' aria-disabled='true'>" . lang("no_result") . "</a>");
}
