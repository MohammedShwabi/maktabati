<?php
// initialize session
ob_start();
session_start();
session_regenerate_id();

// include required file
require_once 'init.php';

//default response message
$response = array(
    'status' => 0,
    'message' => lang('no_cat_name')
);

// validate session
if (!isset($_SESSION['UserEmail'])) {
    $response['message'] = lang('no_session');
    echo json_encode($response);
    exit();
}

// validate get param
if (!filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_SPECIAL_CHARS)) {
    $response['message'] = lang('no_cat_name');
    echo json_encode($response);
}

$cat_name = filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_SPECIAL_CHARS);
$results = get_data(CHECK_CATEGORY, [$cat_name]);

if ($results != 0) {
    $response['message'] = lang('invalid_cat_name');
    echo json_encode($response);
    exit();
}

//new book inserted
$response['status'] = 1;
$response['message'] = lang('valid_cat_name');
echo json_encode($response);
