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
$table      = $_POST["table"];
$filed      = $_POST["filed"];
$value      = $_POST["value"];
// $datalist   = $_POST["datalist"];
if (!empty($table) && !empty($filed) && !empty($value)) {
    //to check from users to loan book
    // if ($select == 'user') {
    $output = "";
    $like = "%" . $value . "%";
    $select = "SELECT $filed FROM $table WHERE $filed  LIKE  ? ";
    $rows = get_all_data($select ,[$like]);
    if ($rows != 0) {
        foreach ($rows as $row) {
            $output .= ' <option value="' . $row[$filed] . '">';
        }
        $response['status'] = 1;
        $response['message'] = $output;
        echo json_encode($response);
        exit();
    } else {
        $response['status'] = 0;
        $response['message'] = lang('no_match_result');
        echo json_encode($response);
        exit();
    }
    // }
}
