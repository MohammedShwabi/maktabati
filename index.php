<?php
// include required file
$activePage = "index";
require_once 'init.php';
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
?>

<!-- home page title section -->

<div class="container-fluid mt-5 pt-4">
    <div class="row" style="margin-top: -2px">
        <div class="col p-0" style="background-color: #0099ff;">
            <h1 class="text-center text-white pt-5 m-0"><?= lang("home_title") ?></h1>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#f5f4f4" fill-opacity="1" d="M0,160L48,154.7C96,149,192,139,288,160C384,181,480,235,576,229.3C672,224,768,160,864,128C960,96,1056,96,1152,122.7C1248,149,1344,203,1392,229.3L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>

    </div>
</div>

<?php
// search section
search_section("search_all.php", lang("search_home"), "result.php")
?>

<!-- feature section -->
<div class="container my-5 py-5">
    <div id="feature" class="row gx-5 gy-2">

        <?php
        feature("search_feature.svg", lang("feature_one_title"), lang("feature_one_subtitle"));
        feature("book_feature.svg", lang("feature_two_title"), lang("feature_two_subtitle"));
        feature("author_feature.svg", lang("feature_three_title"), lang("feature_three_subtitle"));
        feature("people_feature.svg", lang("feature_four_title"), lang("feature_four_subtitle"));
        ?>

    </div>
</div>

<?php

// delete popup section
delete_popup(lang('delete_book_popup_text'), "temp");
// no delete popup section
no_delete_popup(lang('no_delete_book_popup_text'));

// book box section
$container_id = "book-container";
container_site($container_id);

// Sticky Button
sticky_button(lang("add_book"), "add_book.php");

// to load items
load_script("load_items.php", $container_id, "book");
include $tmpl . 'footer.php';

?>