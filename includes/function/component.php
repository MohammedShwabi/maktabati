<?php
require_once "includes/function/queries.php";

// for book design
function book($book_img, $book_id, $title, $rating, $delete_fun, $section)
{
    $popup = get_data(SELECT_BOOK_STATE, [$book_id])["book_state"] ? "#no_del_pop" : "#del_pop";
    $rate = star_fill($rating);

    $book = '
    <div class="views-row col-lg-2 col-md-4 col-sm-6 book-box col-6">

        <div class="book-teaser d-grid h-100 text-center bg-white border rounded position-relative">
            <a title="' . $title . '" href="book_details.php?book_id=' . $book_id . '" class="d-inline-block">
                <div class="book-image">
                    <img class="img lazyload border rounded mw-100 h-auto" width="160px" height="200px" title="' . $title . '" alt="' . $title . '" data-src="upload/books/' . $book_img . '">
                </div>
            </a>
            <ul class="book-links">
                <li><a ' . $delete_fun . ' id="delete_icon" data-bs-toggle="modal" data-bs-target="' . $popup . '" data-tip="' . lang('delete_book') . '">
                        <i class="fa-duotone fa-trash"></i>
                    </a>
                </li>
                <li><a id="edit_icon" href="book_edit.php?book=' . $book_id . '" data-tip="' . lang('edit_book') . '"><i class="fa-duotone fa-pen"></i></a></li>
            </ul>
            <h3 class="book-title fs-6 my-2 text-center lh-base">
                <a class="text-decoration-none" title="' . $title . '" href="book_details.php?book_id=' . $book_id . '">' . $title . '</a>
            </h3>
            <p class="author-label fs-6">
                <a class="text-decoration-none" title="' . $section["name"] . '" href="' . $section["url"] . '">' . $section["name"] . '</a>
            </p>
            <div class="rating-favorites d-flex justify-content-between align-items-center">
                <div class="rating-book">
                    <div class="rating">' . $rate . '</div>
                </div>
            </div>
        </div>
    </div>
    ';

    echo $book;
}

// for star rate design
function star_fill($rating)
{
    $rate = '';
    for ($i = 0; $i < 5; $i++) {

        $star_fill = ($rating > 0) ? "full-stars" : "empty-stars";
        $rate .= "<i class=\"fa-solid fa-star $star_fill \" style=\"font-size: 19px;\"></i>";

        $rating--;
    }
    return $rate;
}

// for author design
function author($author_id, $author_name, $author_img, $book_no, $delete_fun)
{
    // make delete icon popup
    $delete_pop = $book_no > 0 ? 'no_del_pop' : 'del_pop';
    $author = '
    <div class="views-row col-lg-3 col-md-6 col-sm-12 book-box">

        <div class="category-box d-flex justify-content-center align-content-center align-items-center flex-wrap border rounded"
        onclick="window.location.href = \'author_section.php?auth=' . $author_id . '\'" title="' . $author_name . '" role="button">
            <div class="book-result mt-0">
                <div class="writer-avatar d-flex justify-content-center">
                    <img class="img lazyload media-object" width="auto" height="auto" title="' . $author_name . '" alt="' . $author_name . '" data-src="upload/authors/' . $author_img . '">
                </div>
                <h3>' . $author_name . '</h3>
                <p><span><i class="fa-duotone fa-books" style="color: #207ABF;"></i> ' . $book_no . '</span> ' . lang("book") . '</p>
            </div>
            <ul class="book-links text-center">
                <li>
                    <a ' . $delete_fun . ' style="border: 1px solid red" id="delete_icon" data-bs-toggle="modal" data-bs-target="#' . $delete_pop . '" data-tip="' . lang('delete_auth') . '">
                        <i class="fa-duotone fa-trash"></i>
                    </a>
                </li>
                <li><a style="border: 1px solid" id="edit_icon" href="author_edit.php?auth=' . $author_id . '" data-tip="' . lang('edit_auth') . '" onclick="event.stopPropagation();"><i class="fa-duotone fa-pen"></i></a></li>
            </ul>
        </div>
    </div>
    ';
    echo $author;
}

// for category and publisher design
function category($book_no, $section, $delete_fun)
{
    // make delete icon popup
    $delete_pop = $book_no > 0 ? 'no_del_pop' : 'del_pop';
    $category = '
    <div class="views-row col-lg-2 col-md-4 col-sm-6 book-box col-6">
        <div class="category-box d-flex justify-content-center align-content-center align-items-center flex-wrap border rounded"
            onclick="window.location.href = \'' . $section["url"] . '\'" title="' . $section["name"] . '" role="button">
            <div class="book-result ">
                <h3>' . $section["name"] . '</h3>
                <p> <span><i class="fa-duotone fa-books" style="color: #207ABF;"></i> ' . $book_no . '</span> ' . lang("book") . '</p>
            </div>
            </a>
            <ul class="book-links text-center">
                <li>
                    <a ' . $delete_fun . ' style="border: 1px solid red" id="delete_icon" data-bs-toggle="modal" data-bs-target="#' . $delete_pop . '" data-tip="' . $section["delete_txt"] . '">
                        <i class="fa-duotone fa-trash"></i>
                    </a>
                </li>
                <li><a style="border: 1px solid #0d6efd" id="edit_icon"  class="cat-edit" ' . $section["edit_url"] . ' data-tip="' . $section["edit_txt"] . '" onclick="event.stopPropagation();"><i class="fa-duotone fa-pen"></i></a></li>
            </ul>
        </div>
    </div>';
    echo $category;
}

// for feature box design
function feature($photo, $title, $subtitle)
{
    $feature = '
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="feature-box text-white rounded-1 shadow p-4">
            <img src="img/' . $photo . '" style="width: auto; height: 50px;" class=" my-2" />
            <h5>' . $title . '</h5>
            <p>' . $subtitle . '</p>
        </div>
    </div>';
    echo $feature;
}

// for Sticky Button design
function sticky_button($name, $action, $pop = '')
{
    $destination = '';
    if (!empty($action)) {
        $destination =  'onclick="window.location.href=\'' . $action . '\';"';
    } else {
        $destination =  'data-bs-toggle="modal" data-bs-target="#' . $pop . '"';
    }

    $sticky_button = '
    <div class="overflow-visible add-btn-div">
        <button type="button" class="add-btn add-btn-big rounded-pill pe-3 ps-3" ' . $destination . ' >
            <span>' . $name . '</span>
            <div class="btn-line d-inline"></div>
            <i class="fa-solid fa-plus"></i>
        </button>
        <button type="button" class="add-btn add-btn-small" ' . $destination . ' >
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>
    ';
    echo $sticky_button;
}

// for loading icon design
function load_icon()
{
    $load_icon = '
    <div class="col-xs-12 text-center load_more_scroll_loader">
        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    ';
    return $load_icon;
}

// for search design
function search_section($search_page, $search_place_Holder, $action, $search_val = "", $type = "", $table = "")
{
    $search = '
    <div class="container my-5 pb-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <!-- search bar -->
                <form id="form" action="' . $action . '" method="GET">
                    <div class="search">
                        <i class="fa-duotone fa-magnifying-glass" id="search_icon" style="--fa-animation-duration: 1s;"></i>
                        <input type="text" value="' . $search_val . '" name="search_txt" id="search_txt" oninput="' . search_method($search_page, $type, $table) . '" autocomplete="off" class="form-control" placeholder="' . $search_place_Holder . '">
                        <input type="submit" id="search_btn" value="' . lang("search_btn") . '" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <!-- search result list  -->
            <div class="col-md-8">
                <div class="list-group" id="result_list"></div>
            </div>
        </div>
    </div>';
    echo $search;
}

// to make jquery search suggestion method for both home page and other pages
function search_method($search_page, $type, $table)
{
    $section = ",$.param({ search_txt: $(this).val()";

    if (!empty($type) && !empty($table)) {
        $section .= ",type:'" . $type . "', table: '" . $table . "'";
    }
    $search_method = "search($(this).val(),'$search_page' $section}))";

    return $search_method;
}

// for page title design
function page_title($title)
{
    $page_title = '
    <div class="container mt-5 pt-5">
        <h1 class="text-center mb-0 pb-0" style="color: #207ABF;">' . $title . '</h1>
    </div>
    ';
    echo $page_title;
}

// for page main container design
function container_site($container_id)
{
    $container = '
    <div class="container-site">
        <div class="row">
            <div class="col-lg-12">
                <div class="row" id="' . $container_id . '"></div>
            </div>
        </div>
    </div>
    <!--  start of loading icon  -->'
        . load_icon();
    echo $container;
}

// for load data on scroll JS
function load_script($url, $container_id, $type = "", $section = "")
{
    $option = empty($type) ? "{" : "{type: '$type'";
    $option .= empty($section) ? "};" : (empty($type) ? "section: '$section'};" : ",section: '$section'};");

    $load_script = '
    <script>
        option = ' . $option . '
        // to load item after page loaded
        if (load_status == "inactive") {
            load_status = "active";
            loadSection("' . $url . '", "' . $container_id . '", option);
        }

        // load more item on scroll
        $(window).scroll(function() {
            scrollLoader("' . $url . '", "' . $container_id . '", option);
        });
    </script>';
    echo $load_script;
}

// for breadcrumb design
function breadcrumb($items)
{
    $breadcrumb = '
    <header>
        <div class="article-header" style="margin-top: 70px;">
            <div class="container-site">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="title-page">' . array_key_last($items) . '</h1>

                        <ol class="breadcrumb p-0 m-0">
                            ' . breadcrumb_items($items) . '
                        </ol>

                    </div>
                </div>
            </div>
        </div>
    </header>
    ';
    echo $breadcrumb;
}

// to generate breadcrumb link
function breadcrumb_items($items)
{
    $breadcrumb_items = '';

    foreach ($items as $name => $url) {
        $breadcrumb_items .= '<li><a ' . $url . '>' . $name . '</a></li>';
    }

    return $breadcrumb_items;
}

// for author and publisher details
function section_detail($section_title, $section_subtitle, $section_img = "")
{
    $author_photo = empty($section_img) ? "" : '<div class="ps-4"><img class="media-object author-img" width="auto" height="auto" src="upload/authors/' . $section_img . '"></div>';
    $section_detail = '
    <div class="container-site">
        <div class="row">
            <div class="col-lg-12 book-box">
                <div class="book-teaser d-grid h-100 bg-white border rounded position-relative text-right">
                    <div class="d-flex align-items-start author-detail">
                        ' . $author_photo . '
                        <div class="author-body">
                            <h2 class="author-heading">' . $section_title . '</h2>
                            <p><strong>' . $section_subtitle . '</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
    echo $section_detail;
}

// delete pop up windows design
function delete_popup($delete_msg, $url)
{
    $popup = '
    <div class="modal fade" tabindex="-1" id="del_pop" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">' . lang('delete') . '</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">' . $delete_msg . '</p>
                </div>
                <div class="modal-footer justify-content-evenly">
                    <button type="button" id="delete_btn" class="btn logout-popup" onclick="' . $url . '">' . lang('delete') . '</button>
                    <button type="button" class="btn btn-secondary cancel" data-bs-dismiss="modal">' . lang('close') . '</button>
                </div>
            </div>
        </div>
    </div>

    ';
    echo $popup;
}

// delete pop up windows design
function no_delete_popup($no_delete_msg)
{
    $no_delete_pop = '
    <div class="modal fade" tabindex="-1" id="no_del_pop" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">' . lang('no_delete') . '</h4>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">' . $no_delete_msg . '</p>
            </div>
        </div>
    </div>
    </div>
    ';

    echo $no_delete_pop;
}

// add category popup
function add_cat($action = 'add_category.php')
{

    $add_cat = '
    <div class="modal fade" id="add_category" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add_category_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-4">
                <h5 class="modal-title pop-title" id="add_category_label">' . lang("add_category") . '</h5>
                <button type="button" class="btn-close m-0 " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="' . $action . '" method="POST" id="catForm">
                <div class="modal-body mx-5 px-4 mb-4">
                    <!-- input here -->
                    <div class="col-12">
                        <label for="cat_name" class="form-label category-name">' . lang('category_name') . '</label>
                        <input type="text" required class="form-control" name="cat_name" onkeyup="checkName(\'check_cat.php\', {cat_name: $(this).val()}, $(this))" id="cat_name" placeholder="' . lang('category_placeholder') . '" >
                        <div class="invalid-feedback"></div>
                    </div>
                    <!-- for update -->
                    <input type="hidden" name="cat_id" id="cat_id" >
                </div>
                <div class="modal-footer  justify-content-evenly">
                    <input type="submit" id="submit" class="add btn btn-primary" value="' . lang('add') . '" />
                    <input type="reset" class="cancel btn btn-secondary" data-bs-dismiss="modal" value="' . lang('cancel') . '" />
                </div>
            </form>
        </div>
        </div>
    </div>
    ';

    echo $add_cat;
}
