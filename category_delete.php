<?php
// initialize session
ob_start();
session_start();
session_regenerate_id();

// include required file
require_once 'init.php';

// validate session and
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
// validate get param
if (filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // delete the record from DB
    delete_item(DELETE_CATEGORY, $id);
}
go_back();
