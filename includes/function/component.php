<?php
require_once "includes/function/queries.php";

/**
 * Generates HTML markup for displaying a book teaser with various details.
 *
 * @param string $book_img The filename of the book's image.
 * @param int $book_id The unique identifier of the book.
 * @param string $title The title of the book.
 * @param float $rating The rating of the book.
 * @param string $delete_fun The JavaScript function to trigger on delete icon click.
 * @param array $section An associative array containing section information (e.g., name and URL).
 *
 * @return void This function echoes the generated HTML markup for the book teaser.
 */
function book($book_img, $book_id, $title, $rating, $delete_fun, $section)
{
    // Determine the appropriate delete popup based on the book's state.
    $popup = get_data(SELECT_BOOK_STATE, [$book_id])["book_state"] ? "#no_del_pop" : "#del_pop";

    // Generate star rating HTML using the star_fill function.
    $rate = star_fill($rating);

    // Construct the HTML markup for the book teaser.
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
                <li><a id="edit_icon" href="edit_book_test.php?book=' . $book_id . '" data-tip="' . lang('edit_book') . '"><i class="fa-duotone fa-pen"></i></a></li>
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

    // Echo the generated HTML markup for the book teaser.
    echo $book;
}

/**
 * Generates HTML for displaying star ratings based on a given rating value.
 *
 * @param float $rating The rating value to represent with stars.
 *
 * @return string HTML representation of star ratings.
 */
function star_fill($rating)
{
    $rate = '';
    for ($i = 0; $i < 5; $i++) {

        // Determine whether to display a full or empty star based on the remaining rating.
        $star_fill = ($rating > 0) ? "full-stars" : "empty-stars";

        // Add the star icon to the HTML string.
        $rate .= "<i class=\"fa-solid fa-star $star_fill\" style=\"font-size: 19px;\"></i>";

        // Decrease the remaining rating.
        $rating--;
    }
    return $rate;
}

/**
 * Generates HTML markup for displaying author information and controls.
 *
 * @param int $author_id The unique identifier of the author.
 * @param string $author_name The name of the author.
 * @param string $author_img The filename of the author's image.
 * @param int $book_no The number of books associated with the author.
 * @param string $delete_fun The JavaScript function to trigger on delete icon click.
 *
 * @return void This function echoes the generated HTML markup for the author display.
 */
function author($author_id, $author_name, $author_img, $book_no, $delete_fun)
{
    // Determine the appropriate delete popup based on the number of associated books.
    $delete_pop = $book_no > 0 ? 'no_del_pop' : 'del_pop';

    // Construct the HTML markup for displaying author information.
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
                <li><a style="border: 1px solid" id="edit_icon" href="edit_author.php?auth=' . $author_id . '" data-tip="' . lang('edit_auth') . '" onclick="event.stopPropagation();"><i class="fa-duotone fa-pen"></i></a></li>
            </ul>
        </div>
    </div>
    ';

    // Echo the generated HTML markup for displaying author information.
    echo $author;
}

/**
 * Generates HTML markup for displaying category information and controls.
 *
 * @param int $book_no The number of books associated with the category.
 * @param array $section An associative array containing section information (e.g., name, URL, delete text, edit URL, edit text).
 * @param string $delete_fun The JavaScript function to trigger on delete icon click.
 *
 * @return void This function echoes the generated HTML markup for the category display.
 */
function category($book_no, $section, $delete_fun)
{
    // Determine the appropriate delete popup based on the number of associated books.
    $delete_pop = $book_no > 0 ? 'no_del_pop' : 'del_pop';

    // Construct the HTML markup for displaying category information.
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
    // Echo the generated HTML markup for displaying category information.
    echo $category;
}

/**
 * Generates HTML markup for displaying a featured item with an image, title, and subtitle.
 *
 * @param string $photo The filename of the featured item's image.
 * @param string $title The title of the featured item.
 * @param string $subtitle The subtitle or description of the featured item.
 *
 * @return void This function echoes the generated HTML markup for the featured item.
 */
function feature($photo, $title, $subtitle)
{
    // Construct the HTML markup for displaying a featured item.
    $feature = '
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="feature-box text-white rounded-1 shadow p-4">
            <img src="img/' . $photo . '" style="width: auto; height: 50px;" class=" my-2" />
            <h5>' . $title . '</h5>
            <p>' . $subtitle . '</p>
        </div>
    </div>';
    
    // Echo the generated HTML markup for the featured item.
    echo $feature;
}

/**
 * Generates HTML markup for a sticky button with optional action or modal popup trigger.
 *
 * @param string $name The text displayed on the button.
 * @param string $action The action to be taken when the button is clicked (e.g., URL to navigate to).
 * @param string $pop The ID of the modal popup (if applicable).
 *
 * @return void This function echoes the generated HTML markup for the sticky button.
 */
function sticky_button($name, $action, $pop = '')
{
    $destination = '';

    // Determine the destination based on whether there's an action or modal popup.
    if (!empty($action)) {
        $destination = 'onclick="window.location.href=\'' . $action . '\';"';
    } else {
        $destination = 'data-bs-toggle="modal" data-bs-target="#' . $pop . '"';
    }

    // Construct the HTML markup for the sticky button.
    $sticky_button = '
    <div class="add-btn-div">
        <button type="button" class="add-btn rounded-pill" ' . $destination . ' >
            <i class="fa-solid fa-plus"></i>
            <span>' . $name . '</span>
        </button>
    </div>';

    // Echo the generated HTML markup for the sticky button.
    echo $sticky_button;
}

/**
 * Generates HTML markup for a loading spinner icon.
 *
 * @return string HTML markup for the loading spinner icon.
 */
function load_icon()
{
    // Construct the HTML markup for the loading spinner icon.
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

    // Return the generated HTML markup for the loading spinner icon.
    return $load_icon;
}

/**
 * Generates HTML markup for a search section, including a search bar and search result list.
 *
 * @param string $search_page The page or endpoint where the search action will be performed.
 * @param string $search_place_Holder The placeholder text for the search input.
 * @param string $action The action URL to submit the search form.
 * @param string $search_val The initial value of the search input (optional).
 * @param string $type The type of search (optional).
 * @param string $table The name of the table to search within (optional).
 *
 * @return void This function echoes the generated HTML markup for the search section.
 */
function search_section($search_page, $search_place_Holder, $action, $search_val = "", $type = "", $table = "")
{
    // Construct the HTML markup for the search section.
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

    // Echo the generated HTML markup for the search section.
    echo $search;
}


/**
 * Generates a JavaScript search method with optional parameters for filtering.
 *
 * @param string $search_page The page or endpoint where the search action will be performed.
 * @param string $type The type of search (optional).
 * @param string $table The name of the table to search within (optional).
 *
 * @return string JavaScript search method with optional filtering parameters.
 */
function search_method($search_page, $type, $table)
{
    $section = ",$.param({ search_txt: $(this).val()";

    if (!empty($type) && !empty($table)) {
        $section .= ",type:'" . $type . "', table: '" . $table . "'";
    }
    $search_method = "search($(this).val(),'$search_page' $section}))";

    return $search_method;
}

/**
 * Generates HTML markup for displaying a page title.
 *
 * @param string $title The title text to display on the page.
 *
 * @return void This function echoes the generated HTML markup for the page title.
 */
function page_title($title)
{
    // Construct the HTML markup for the page title.
    $page_title = '
    <div class="container mt-5 pt-5">
        <h1 class="text-center mb-0 pb-0" style="color: #207ABF;">' . $title . '</h1>
    </div>
    ';

    // Echo the generated HTML markup for the page title.
    echo $page_title;
}

/**
 * Generates HTML markup for a container site with a specified ID and includes a loading icon.
 *
 * @param string $container_id The ID attribute for the main container element.
 *
 * @return void This function echoes the generated HTML markup for the container site.
 */
function container_site($container_id)
{
    // Construct the HTML markup for the container site and include a loading icon.
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

    // Echo the generated HTML markup for the container site.
    echo $container;
}

/**
 * Generates a JavaScript script for loading content into a specified container.
 *
 * @param string $url The URL or endpoint for content retrieval.
 * @param string $container_id The ID of the container where content will be loaded.
 * @param string $type The type of content to load (optional).
 * @param string $section The section or category of content to load (optional).
 *
 * @return void This function echoes the generated JavaScript script for loading content.
 */
function load_script($url, $container_id, $type = "", $section = "")
{
    $option = empty($type) ? "{" : "{type: '$type'";
    $option .= empty($section) ? "};" : (empty($type) ? "section: '$section'};" : ",section: '$section'};");

    // Construct the JavaScript script for loading content.
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

    // Echo the generated JavaScript script for loading content.
    echo $load_script;
}


/**
 * Generates HTML markup for a breadcrumb navigation.
 *
 * @param array $items An associative array representing the breadcrumb items (e.g., ['Home' => 'home_url', 'Category' => 'category_url', 'Current Page' => 'current_page_url']).
 *
 * @return void This function echoes the generated HTML markup for the breadcrumb navigation.
 */
function breadcrumb($items)
{
    // Construct the HTML markup for the breadcrumb navigation.
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

    // Echo the generated HTML markup for the breadcrumb navigation.
    echo $breadcrumb;
}


/**
 * Generates HTML markup for individual breadcrumb items based on an associative array.
 *
 * @param array $items An associative array representing the breadcrumb items (e.g., ['Home' => 'home_url', 'Category' => 'category_url', 'Current Page' => 'current_page_url']).
 *
 * @return string HTML markup for the individual breadcrumb items.
 */
function breadcrumb_items($items)
{
    $breadcrumb_items = '';

    foreach ($items as $name => $url) {
        $breadcrumb_items .= '<li><a ' . $url . '>' . $name . '</a></li>';
    }

    return $breadcrumb_items;
}


/**
 * Generates HTML markup for displaying section details, including a title, subtitle, and optional image.
 *
 * @param string $section_title The title of the section.
 * @param string $section_subtitle The subtitle or description of the section.
 * @param string $section_img The filename of an optional image for the section (if available).
 *
 * @return void This function echoes the generated HTML markup for the section details.
 */
function section_detail($section_title, $section_subtitle, $section_img = "")
{
    // Generate HTML markup for the author's photo (if available).
    $author_photo = empty($section_img) ? "" : '<div class="ps-4"><img class="media-object author-img" width="auto" height="auto" src="upload/authors/' . $section_img . '"></div>';

    // Construct the HTML markup for displaying section details.
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

    // Echo the generated HTML markup for the section details.
    echo $section_detail;
}

/**
 * Generates HTML markup for a delete confirmation popup modal.
 *
 * @param string $delete_msg The message displayed in the confirmation popup.
 * @param string $url The URL or action to be taken when the delete button is clicked.
 *
 * @return void This function echoes the generated HTML markup for the delete confirmation popup.
 */
function delete_popup($delete_msg, $url)
{
    // Construct the HTML markup for the delete confirmation popup modal.
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

    // Echo the generated HTML markup for the delete confirmation popup.
    echo $popup;
}


/**
 * Generates HTML markup for a popup modal indicating that an item cannot be deleted.
 *
 * @param string $no_delete_msg The message displayed in the "no delete" popup.
 *
 * @return void This function echoes the generated HTML markup for the "no delete" popup modal.
 */
function no_delete_popup($no_delete_msg)
{
    // Construct the HTML markup for the "no delete" popup modal.
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

    // Echo the generated HTML markup for the "no delete" popup modal.
    echo $no_delete_pop;
}

/**
 * Generates HTML markup for a modal to add a new category.
 *
 * @param string $action The form action URL for submitting the category addition (default is 'add_category.php').
 *
 * @return void This function echoes the generated HTML markup for the "add category" modal.
 */
function add_cat($action = 'add_category.php')
{
    // Construct the HTML markup for the "add category" modal.
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

    // Echo the generated HTML markup for the "add category" modal.
    echo $add_cat;
}
