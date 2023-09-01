<?php
include 'init.php';
// initialize the var
$response = array(
    'status' => 0,
    'message' => ""
);
$select = $_POST["select"];
$filed = $_POST["filed"];
if (!empty($select) && !empty($filed)) {
    //to check from email to signup 
    if ($select == 'email') {
        $result = get_data(USER_CHECK, [$filed]);
        if ($result != 0) {
            $response['status'] = 1;
            $response['message'] = lang('error_email_exist');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }
    //to check from password to signup 
    if ($select == 'password') {
        if (!preg_match("/^(?=.*\d)(?=.*\W+)(?=.*[a-z])(?=.*[A-Z]).{6,25}$/",  $filed)) {
            $response['status'] = 1;
            $response['message'] = lang('error_invalid_pass');
            echo json_encode($response);
            exit();
        }else{
            $response['status'] = 0;
            echo json_encode($response);
            exit();
        }
    }
}