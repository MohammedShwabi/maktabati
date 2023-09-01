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

if (
    filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) &&
    filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_INT)
) {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $rate = filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_INT);

    // update the record from DB
    get_data(BOOK_RATING, [$rate, $id]);
}
go_back();
