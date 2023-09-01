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
    'message' => lang('error_uploading')
);

// validate session
if (!isset($_SESSION['UserEmail'])) {
    $response['message'] = lang('no_session');
    echo json_encode($response);
    exit();
}

// validate param
if (
    !filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT) ||
    empty($_FILES['upload']['name']) ||
    !is_uploaded_file($_FILES['upload']['tmp_name'])
) {
    $response['message'] = lang('no_data');
    echo json_encode($response);
    exit();
}

$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

// File upload path
$upload_dir = "upload/pdf/";
// File upload name  
$file_name = basename($_FILES['upload']['name']);
// File upload path 
$target_file = $upload_dir . $file_name;

//check if the file name is not repeated
if (file_exists($target_file)) {
    $response['message'] = lang('file_exists');
    echo json_encode($response);
    exit();
}

//allowed file extension
$allow_types = array('pdf', 'doc', 'docx');
// get uploaded file's extension
$file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

// Check whether file type is valid  
if (!in_array($file_extension, $allow_types)) {
    $response['message'] = lang('not_allowed') . join(", ", $allow_types);
    echo json_encode($response);
    exit();
}

// Upload file to server  
if (!move_uploaded_file($_FILES['upload']['tmp_name'], $target_file)) {
    $response['message'] = lang('error_uploading');
    echo json_encode($response);
    exit();
}

//update DB path
$results = get_data(EDIT_FILE_PATH, [$file_name, $book_id]);

if ($results = 0) {
    $response['message'] = lang('error_insert');
    echo json_encode($response);
    exit();
}

//new book inserted
$response['status'] = 1;
$response['message'] = lang('book_inserted');
echo json_encode($response);
