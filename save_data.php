<?php
require_once 'init.php';
var_dump($_POST);
//exception handling for any error will happen
global $con;
try {
    //start transaction
    $con->beginTransaction();
if (
    !isset($_POST['category']) || !isset($_POST['author_name']) || !isset($_POST['work_on_book'])
    || !isset($_POST['publisher']) || !isset($_POST['language'] )
) {
    //redirect
    // echo lang('still_one_choses_empty');
    throw new ErrorException(lang('still_one_choses_empty'));
}
$book_title           = filter_var(strip_tags($_POST['book_title']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$book_sub_title       = filter_var(strip_tags($_POST['book_sub_title']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$book_description     = filter_var(strip_tags($_POST['book_description']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$category             = filter_var(strip_tags($_POST['category']), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
$dewyNo               = filter_var(strip_tags($_POST['dewyNo']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$author_id            = filter_var(strip_tags($_POST['author_name']), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
$work_id              = filter_var(strip_tags($_POST['work_on_book']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$publisher            = filter_var(strip_tags($_POST['publisher']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$publish_place        = filter_var(strip_tags($_POST['publish_place']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$language             = filter_var(strip_tags($_POST['language']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$book_location        = filter_var(strip_tags($_POST['book_location']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$isbn                 = filter_var(strip_tags($_POST['isbn']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
$depository_no        = filter_var(strip_tags($_POST['depository_no']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE) ?? NULL;
$chose_part           = filter_var(strip_tags($_POST['chose_part']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE) ?? NULL;
    //check all required field 
    if (
        empty($book_title) || empty($book_description) || empty($category) || empty($dewyNo)  || empty($author_id)
        || empty($work_id) || empty($publish_place) || empty($language) || empty($chose_part) || !isset($_POST['part_number'])
    ) {
        //redirect user to full empty filed
        throw new ErrorException(lang('login_empty'));
    } else {
        //insert into book table
        $sql_insert_book = insert_data(INSERT_BOOK_INFO, [$book_title, $book_sub_title, "photo", $book_description, $depository_no, $isbn, $dewyNo, 3, $publish_place, $category]);
        //to get last id of book table to use it more than once
        $get_last_id = get_data("Select max(book_id) from book");
        $book_id = $get_last_id['max(book_id)'];
        //to insert work on book
        $work_on_book = "";
        switch ($work_id) {
            case 1:
                $work_on_book = lang('authoring');
                break;
            case 2:
                $work_on_book = lang('translate');
                break;
            case 3:
                $work_on_book = lang('checking');
                break;
            default:
                $work_on_book = lang('reviewing');
                break;
        }
        //insert into book_author_rel table
        $sql_insert_book_author_rel =  insert_data(INSERT_BOOK_AUTHOR_REL, [$work_on_book, $work_id, $book_id, $author_id]);
        //insert into book_lang_rel table
        $sql_insert_book_lang_rel = insert_data(INSERT_BOOK_LANG_REL, [$language, $book_id]);
        //insert into book_pub_rel table
        $sql_insert_book_pub_rel = insert_data(INSERT_BOOK_PUB_REL, [$book_id, $publisher]);

        //loop on all part if all required field are not empty then insert them
        foreach ($_POST['part_number'] as $key => $value) {
            $part_number            = filter_var(strip_tags($_POST['part_number'][$key]), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
            $edition_no             = filter_var(strip_tags($_POST['edition_no'][$key]), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $edition_desc           = filter_var(strip_tags($_POST['edition_desc'][$key]), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
            $publish_date           = filter_var(strip_tags($_POST['publish_date'][$key]), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
            $pages_no               = filter_var(strip_tags($_POST['pages_no'][$key]), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $price                  = filter_var(strip_tags($_POST['price'][$key]), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $no_of_copy             = filter_var(strip_tags($_POST['no_of_copy'][$key]), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (
                empty($part_number) || empty($edition_no) || empty($publish_date) || empty($pages_no)  || empty($no_of_copy)
            ) {
                //redirect user to full empty filed about any part
                throw new ErrorException(lang('login_empty'));
                break;
            } else {
                    $good_date=date_create($publish_date);
                    if($good_date){
                        if(date_format($good_date, "Y-m")){
                            $good_date = $publish_date."-01";
                        }
                        else{
                            throw new Exception("");
                        }
                    }
                $good_date = $publish_date."-01";
                    //insert into part table
                    $sql_insert_part = insert_data(INSERT_BOOK_PART, [$part_number, "part_path", $book_id,$good_date, $price, $pages_no, "size", $edition_no, $edition_desc, "pdf", $no_of_copy]);
                    //to get last id of part table
                    $get_last_part_id = get_data("Select max(part_id) from part");
                    $part_id = $get_last_part_id['max(part_id)'];
                    //loop and insert all copes into DB 
                    for ($i = 1; $i <= $no_of_copy; $i++) {
                        $sql_insert_part = insert_data(INSERT_PART_COPY_REL, [$part_id]);
                    }         
            }
        }
        //insert series if it's found 
        $series_name           = filter_var(strip_tags($_POST['series_name']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $no_in_series           = filter_var(strip_tags($_POST['no_in_series']), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!empty($series_name) && !empty($series_name)) {
            $rows = get_data(SELECT_SERIES_NAME, [$series_name]);
            if ($rows > 0) {
                //if series is exist insert into series_part_rel
                $series_id = $rows['series_id'];
                $sql_insert_series_part_rel = insert_data(INSERT_SERIES_PART_REL, [$no_in_series, $series_id, $part_id]);
            } else {
                //if series is not exist insert into series then series_part_rel
                $sql[INSERT_SERIES] = array($series_name);
                $sql_insert_series_part_rel = insert_data(INSERT_SERIES_PART_REL_LAST, [$no_in_series, $part_id]);
            }
        }
        //insert attachment if it's found 
        $attachment_name          = filter_var(strip_tags($_POST['attachment_name']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $attachment_type          = filter_var(strip_tags($_POST['attachment_type']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $attachment_loc           = filter_var(strip_tags($_POST['attachment_loc']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        if (!empty($attachment_name) && !empty($attachment_type) && !empty($attachment_loc)) {
            $sql_insert_attachment = insert_data(INSERT_ATTACHMENT, [$attachment_name, $attachment_type, $attachment_loc, $part_id]);
        }
    }
    //commit all queries
    $con->commit();
    //print success message
    echo "<br/>".lang("book_inserted_successfully")."<br/> ";
} catch (ErrorException $e) {
    $con->rollBack();
    echo  $e->getMessage();
} catch (Exception $e) {
    $con->rollBack();
    echo  lang("unexpected_error") .$e;
}
