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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    redirect_user("", 0, "advance_search.php");
}

//check if variables is not empty
if (empty($_GET["search_input"]) || empty($_GET["search_option"])) {
    //print empty message
    redirect_user(lang('input_search_empty'), 5, "advance_search.php");
} else {
    //filter all element
    $search_input          = filter_input(INPUT_GET, 'search_input', FILTER_SANITIZE_SPECIAL_CHARS);
    $search_option         = filter_input(INPUT_GET, 'search_option', FILTER_SANITIZE_SPECIAL_CHARS);

    //get the matching data from DB
    $results = 0;
    // $sql = "Select * From advance_search Where " . rtrim($search_option) . " Like % " . $search_input . "%";

    $sql = "SELECT * FROM advance_search WHERE $search_option LIKE ?";
    $search_input = trim($search_input);
    $searchTerm = '%' . $search_input . '%';
    $results = get_all_data($sql, [$searchTerm]);

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
            $delete_fun = " onclick=\"deletePop('delete_book.php', {'id':'$book_id', 'img':'$book_img'} )\" ";

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
