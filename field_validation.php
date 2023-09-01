<?php
include 'init.php';
// initialize session
ob_start();
session_start();
session_regenerate_id();
// initialize the var
$response = array(
    'status' => 0,
    'message' => ""
);
if (!isset($_SESSION['UserEmail'])) {
    $response['message'] = lang('no_session');
    echo json_encode($response);
    exit();
}
$select = $_POST["select"];
$filed = $_POST["filed"];
if (!empty($select) && !empty($filed)) {
    //to check from users to loan book
    if ($select == 'user') {
        $result = get_data(SELECT_LOAN_USER, [$filed]);
        if ($result != 0) {
            $response['status'] = 1;
            $response['message'] = lang('user_have_loan');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }
    //check from book title
    //to check from part 
    if ($select == 'book_title') {
        $result = get_data(SELECT_BOOK_NAME, [$filed]);
        if ($result != 0) {
            $response['status'] = 1;
            $response['message'] = lang('book_exist');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }

    //to check from part 
    if ($select == 'part') {
        $result = get_data(SELECT_LOAN_PART, [$filed]);
        if ($result != 0) {
            $response['status'] = 1;
            $response['message'] = lang('part_have_loan');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }
    //to check from part 
    if ($select == 'publisher') {
        $result = get_data(SELECT_PUB_NAME, [$filed]);
        if ($result != 0) {
            $response['status'] = 1;
            $response['message'] = lang('pub_exist');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }
    //to check from part 
    if ($select == 'publisher_seq') {
        $result = get_data(SELECT_PUB_SEQ, [$filed]);
        if ($result != 0) {
            $response['status'] = 1;
            $response['message'] = lang('pub_seq_exist');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }
}
