<?php
// include required file
require_once 'init.php';
$pageTitle = lang("advance_search_title");
$activePage = "advance_search";
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_user("", 0, "advance_search.php");
}

//check if variables is not empty
if (
    empty($_POST["book_title"]) && empty($_POST["search_author"]) && empty($_POST["search_publish_date"]) && empty($_POST["search_language"])
    && empty($_POST["search_category"]) && empty($_POST["search_publisher"]) && empty($_POST["search_dewyNo"]) && empty($_POST["search_isbn"])
    && empty($_POST["search_series_name"])
) {
    //print empty message
    echo " <div class='alert alert-danger text-center fw-bold'>" . lang('search_empty') . "</div>";
} else {
    //filter all element
    $title          = filter_input(INPUT_POST, 'book_title', FILTER_SANITIZE_SPECIAL_CHARS);
    $author         = filter_input(INPUT_POST, 'search_author', FILTER_SANITIZE_SPECIAL_CHARS);
    $publish_date   = filter_input(INPUT_POST, 'search_publish_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $language       = filter_input(INPUT_POST, 'search_language', FILTER_SANITIZE_SPECIAL_CHARS);
    $category       = filter_input(INPUT_POST, 'search_category', FILTER_SANITIZE_SPECIAL_CHARS);
    $publisher      = filter_input(INPUT_POST, 'search_publisher', FILTER_SANITIZE_SPECIAL_CHARS);
    $dewyNo         = filter_input(INPUT_POST, 'search_dewyNo', FILTER_SANITIZE_SPECIAL_CHARS);
    $isbn           = filter_input(INPUT_POST, 'search_isbn', FILTER_SANITIZE_SPECIAL_CHARS);
    $series_name    = filter_input(INPUT_POST, 'search_series_name', FILTER_SANITIZE_SPECIAL_CHARS);

    //work to generate select 
    $where = "";
    $table = "advance_search";
    $values  = array();

    if (!empty($title)) {
        $where .= "title  like  ? AND ";
        $values[]  = "%" . $title . "%";
    }
    if (!empty($author)) {
        $where .= "author_name  like  ? AND ";
        $values[] = "%" . $author . "%";
    }
    //check from date format
    if (!empty($publish_date)) {
        $good_date=date_create($publish_date);
        if($good_date){
            if(date_format($good_date, "Y-m")){
                $where .= "publication_date  like  ? AND ";
                $values[] = "%" . $publish_date . "%";
            }
        }
    }
    if (!empty($language)) {
        $where .= "	lang_name  like  ? AND ";
        $values[] = "%" . $language . "%";
    }
    if (!empty($category)) {
        $where .= "cat_name  like  ? AND ";
        $values[] = "%" . $category . "%";
    }
    if (!empty($publisher)) {
        $where .= "pub_name  like  ? AND ";
        $values[] = "%" . $publisher . "%";
    }
    if (!empty($dewyNo)) {
        $where .= "dewey_no  like  ? AND ";
        $values[] = "%" . $dewyNo . "%";
    }
    if (!empty($isbn)) {
        $where .= "isbn  like  ? AND ";
        $values[] = "%" . $isbn . "%";
    }
    if (!empty($series_name)) {
        $table = "search_with_series";
        $where .= "series_name  like  ? AND ";
        $values[] = "%" . $series_name . "%";
    }

    //get the matching data from DB
    $results = 0;
    if(count($values) !== 0){
        $sql = "Select * From " . $table . " Where " . rtrim($where, "AND ");
        $results = get_all_data($sql, $values);
    }

    // make breadcrumb
    $items = array(
        lang("home") => 'href="index.php"',
        lang("result_breadcrumb_title") => 'class="active"'
    );
    breadcrumb($items);

    // check if there is a search result 
    if ($results != 0) {
        // delete popup section
        delete_popup(lang('delete_book_popup_text'), "temp");
        // no delete popup section
        no_delete_popup(lang('no_delete_book_popup_text'));

        // start of container
        echo ('<div class="container-site"><div class="row"><div class="col-lg-12"><div class="row">');

        // to print all items
        foreach ($results as $output) {
            // get all variable
            $book_id = $output["book_id"];
            $book_img = $output["photo"];
            // make delete icon function
            $delete_fun = " onclick=\"deletePop('book_delete.php', {'id':'$book_id', 'img':'$book_img'} )\" ";

            // initialize var
            $author = array(
                "name" => $output["author_name"],
                "url" => "author_section.php?auth=" . $output["author_id"],
            );
            book($book_img, $book_id, $output["title"], $output["rating"], $delete_fun, $author);
        }
        // end of container
        echo ('</div></div></div></div>');
    } else {
        // display error message
        echo ('<div class="container text-center">
                    <h1 class="mb-0 pb-0 pt-5 text-muted">' . lang("no_result_advance") . '</h1>
                    <img src="img/No_data.svg" class="img-fluid w-75 w">
               </div>'
        );
    }
}
include $tmpl . 'footer.php';
