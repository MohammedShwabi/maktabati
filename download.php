<?php
ob_start();
session_start();
require_once 'init.php';
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
if (!isset($_GET['id']) || !isset($_GET['do'])) {
    redirect_user("", 0, "index.php");
}
$book_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
$do = strip_tags($_GET['do']);
//check from id after filtering
if (empty($book_id)) {
    redirect_user("", 0, "index.php");
}
if ($do != "download" && $do != "open") {
    redirect_user("", 0, "index.php");
}
// if request is download
if ($do == "download") {
    //get path of file from DB
    /*
    $row = get_path($book_id);
    $filename = $row['part_path'];
    */
    $results = get_data(SELECT_FILE_PATH, [$book_id]);
    $filename = $results['part_path'];

    if (empty($filename)) {
         redirect_user(lang('file_not_exist'), 5, "index.php");
    }
    $filename = basename($filename);
    $filepath = 'upload/pdf/' . $filename;
    // check from file 
    if (empty($filename) || !file_exists($filepath)) {
        //if user coming from previous page or redirect it to index.php
        if (isset($_SERVER['HTTP_REFERER'])) {
            $temp = explode('/', $_SERVER['HTTP_REFERER']);
            $url = end($temp);
        } else {
            $url = 'login.php';
        }
         redirect_user(lang('file_not_exist'), 5, "index.php");
    }
    //execute download
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    readfile($filepath);
    exit;
}
// if request is open
if ($do == "open") {
    
    $results = get_data(SELECT_FILE_PATH, [$book_id]);
    $filename = $results['part_path'];

    //get extension if file
    $fileExtension =  substr(strrchr($filename, '.'), 1);
    // The location of the PDF file
    // on the server
    $filepath = 'upload/pdf/' . $filename;
    if (empty($filename) || !file_exists($filepath)) {
        //if user coming from previous page or redirect it to index.php
        if (isset($_SERVER['HTTP_REFERER'])) {
            $temp = explode('/', $_SERVER['HTTP_REFERER']);
            $url = end($temp);
        } else {
            $url = 'login.php';
        }
        redirect_user(lang('file_not_exist'), 5, "index.php");
    }
    //chick if the file is .pdf
    if ($fileExtension !== 'pdf') {
        //if file extension is not .pdf
        //check if user coming from previous page book_details.php  
        if (isset($_SERVER['HTTP_REFERER'])) {
            $temp = explode('/', $_SERVER['HTTP_REFERER']);
            $url = end($temp);
        } else {
            $url = 'index.php';
        }
        //redirect user 
        redirect_user(lang("file_not_exist"), 5, $url);
    }
    // Header content type
    header("Content-type: application/pdf");
    header("Content-Length: " . filesize($filepath));
    // Send the file to the browser.
    readfile($filepath);
    exit;
}
include $tmpl . 'footer.php';
