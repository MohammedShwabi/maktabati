<?php
// initialize session
ob_start();
session_start();
session_regenerate_id();

// include required file
require_once 'init.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
// validate post param
if (!filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_SPECIAL_CHARS)) {
    go_back();
}
// initialize variable
$cat_name = filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_SPECIAL_CHARS);

if (filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT)) {
    $cat_id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT);
    // update category to database
    get_data(EDIT_CATEGORY, [$cat_name, $cat_id]);
} else {
    // add category to database
    get_data(ADD_CATEGORY, [$cat_name]);
}

go_back();
