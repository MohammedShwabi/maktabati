<?php
// initialize session
ob_start();
session_start();
session_regenerate_id();

// include required file
require_once 'init.php';

// using guard approach
// validate session and
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
// validate get param
if (
    filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ||
    filter_input(INPUT_GET, 'img', FILTER_SANITIZE_SPECIAL_CHARS)
) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $img = filter_input(INPUT_GET, 'img', FILTER_SANITIZE_SPECIAL_CHARS);

    // delete author from DB
    delete_item(DELETE_AUTHOR, $id);

    //delete the img file
    if ($img != "auth_temp.svg") {
        unlink("upload/authors/" . $img);
    }
}
go_back();
