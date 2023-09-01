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
// validate get param
if (filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) && filter_input(INPUT_GET, 'img', FILTER_SANITIZE_SPECIAL_CHARS)) {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $img = filter_input(INPUT_GET, 'img', FILTER_SANITIZE_SPECIAL_CHARS);

    // delete the record from DB
    delete_item(DELETE_BOOK, $id);

    // delete the img file
    unlink("upload/books/" . $img);
}
go_back();
