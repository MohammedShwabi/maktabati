<?php
// include  file
require_once 'init.php';
$pageTitle =  lang('add_book');
require_once $tmpl . 'header.php';

// init upload status code and default book_photo 
$uploadImgStatus = 1;
$book_photo = "book_upload.svg";

//message
$statusMsg = "";
$msgContainer = "d-none";

// File upload path
$upload_dir = "upload/books/";

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

//check if method is post 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $con;
    try {
        //start transaction
        $con->beginTransaction();
        //check from all list
        if (
            !isset($_POST['category']) || !isset($_POST['author_name']) || !isset($_POST['work_on_book'])
            || !isset($_POST['publisher']) || !isset($_POST['language'])
        ) {
            throw new ErrorException(lang('still_one_choses_empty'));
        }

        // validate profile img
        if (
            !empty($_FILES["book_photo"]["name"])
        ) {
            // File upload name
            $book_photo = basename($_FILES["book_photo"]["name"]);

            // File upload path 
            $target_file = $upload_dir . $book_photo;

            //check if the file name is not repeated
            if (!file_exists($target_file)) {
                //allowed file extension
                $allow_types = array('png', 'jpeg', 'jpg');
                // get uploaded file's extension
                $file_extension = strtolower(pathinfo($book_photo, PATHINFO_EXTENSION));
                // Check whether file type is valid  
                if (in_array($file_extension, $allow_types)) {
                    // Upload file to server  
                    if (move_uploaded_file($_FILES["book_photo"]["tmp_name"], $target_file)) {
                        $uploadImgStatus = 1;
                    } else {
                        throw new ErrorException(lang('author_error_uploading'));
                    }
                } else {
                    throw new ErrorException(lang('not_allowed') . join(", ", $allow_types));
                }
            } else {
                throw new ErrorException(lang('author_photo_exists'));
            }
        } else {
            throw new ErrorException(lang('book_photo_required'));
        }

        // validate post param
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
            $uploadImgStatus != 1 ||
            empty($book_title) || empty($book_description) || empty($category) || empty($dewyNo)  || empty($author_id)
            || empty($work_id) || empty($publish_place) || empty($language) || empty($chose_part) || !isset($_POST['part_number'])
        ) {
            throw new ErrorException(lang('login_empty'));
        } else {
            //insert into book table
            $sql_insert_book = insert_data(INSERT_BOOK_INFO, [$book_title, $book_sub_title, $book_photo, $book_description, $depository_no, $isbn, $dewyNo, 3, $publish_place, $category]);

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
                    //check date format and add to it default day for insert ot in DB
                    $good_date = date_create($publish_date);
                    if ($good_date) {
                        if (date_format($good_date, "Y-m")) {
                            $good_date = $publish_date . "-01";
                        } else {
                            throw new Exception("");
                        }
                    }
                    $good_date = $publish_date . "-01";

                    //insert into part table
                    $sql_insert_part = insert_data(INSERT_BOOK_PART, [$part_number, "part_path", $book_id, $good_date, $price, $pages_no, "size", $edition_no, $edition_desc, "pdf", $no_of_copy]);

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
        $statusMsg = lang("add_book_success");
        $msgContainer = "alert-success";
    } catch (ErrorException $e) {
        //rollback if any error happened  with custom error msg
        $con->rollBack();
        $msgContainer = "alert-danger";
        $statusMsg = $e->getMessage();
    } catch (Exception $e) {
        //rollback if any error happened 
        $con->rollBack();
        $msgContainer = "alert-danger";
        $statusMsg = lang("unexpected_error");
    }
}

?>
<br><br><br><br>

<!-- add book section -->
<div class="container rounded card shadow pb-5 mb-5" id="add_book">

    <!-- page title -->
    <div class="row my-4">
        <div class="col-12">
            <h1 class="display-6 mx-4 text-center" id="add_book_title">
                <?= lang("add_book") ?>
            </h1>
            <hr>
        </div>
    </div>

    <!-- message that display after submit is made -->
    <div class="alert <?= $msgContainer ?> alert-dismissible fade show mx-5" role="alert">
        <p class='fw-bold'><?= $statusMsg ?></p>
        <?php
        // redirect user if add author success
        if ($msgContainer == "alert-success") {
            echo ('<p class="fs-6">' . lang('redirect_after') . '<span id="second"></span>' . lang('second') . '</p>');
            echo ("
            <script>
                var second = 5;
                $('#second').text(second);
                var redirectTime = setInterval(myTimer, 1000, 'index.php');
            </script>
        ");
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <form name="add_book" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">

        <div class="row px-5">

            <!-- book photo -->
            <div class="text-center mt-3 mb-5">
                <label for="book_photo" class="profile-upload">
                    <div class="profile-container">
                        <img src="img/book_upload.svg" alt="author image" id="profile_img">
                    </div>
                    <div class="camera-icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-camera-circle"></i>
                    </div>
                </label>
                <input type="file" name="book_photo" id="book_photo" onchange="checkFileExist(this, 'books')" hidden accept="image/png, image/jpeg">
                <div class="invalid-feedback"></div>
            </div>

            <hr>
            <!-- book title and subtitle -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="book_title" class="blue-text mb-2 me-4"><?= lang('book_title'); ?></label>
                        <input required class="form-control" type="text" id="book_title" name="book_title" onkeyup="checkField('book_title', '#book_title', '#check_book_title')" placeholder="<?php echo lang('book_title_place') ?>" autofocus />
                        <span class="invalid-feedback" id="check_book_title"></span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="book_sub_title" class="blue-text mb-2 me-4"><?= lang('book_sub_title'); ?></label>
                        <input class="form-control" type="text" id="book_sub_title" name="book_sub_title" placeholder="<?php echo lang('book_sub_title_place') ?>" autocomplete="off" required />
                    </div>
                </div>
            </div>

            <!-- description -->
            <div class="row">
                <div class="mt-3 text-end">
                    <label for="book_description" class="blue-text mb-2 me-4"><?= lang('book_description'); ?></label>
                    <textarea class="form-control" id="book_description" name="book_description" rows="3" placeholder="<?php echo lang('book_description_place') ?>" autocomplete="off" required></textarea>
                </div>
            </div>

            <!-- book_category and dewy number -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="category" class="blue-text mb-2 me-4"><?= lang('category'); ?></label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="" disabled selected><?= lang('select_category'); ?></option>
                            <?php
                            $cats = get_all_data("select * from categories");
                            foreach ($cats as $cat) {
                                echo '<option value="' . $cat['cat_id'] . '">' . $cat['cat_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="dewyNo" class="blue-text mb-2 me-4"><?= lang('dewyNo'); ?></label>
                        <input class="form-control" type="number" id="dewyNo" name="dewyNo" placeholder="<?php echo lang('dewy_no_place') ?> " autocomplete="off" required />
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <!-- author_name and work_on_book -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="author_name" class="blue-text mb-2 me-4"><?= lang('author_name'); ?></label>
                        <select class="form-control" id="author_name" name="author_name" required>
                            <option value="" disabled selected><?= lang('select_author_name'); ?></option>
                            <?php
                            $authors = get_all_data("select * from author");
                            foreach ($authors as $author) {
                                echo '<option value="' . $author['author_id'] . '">' . $author['author_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="work_on_book" class="blue-text mb-2 me-4"><?= lang('work_on_book'); ?></label>
                        <select class="form-control" id="work_on_book" name="work_on_book" required>
                            <option value="" disabled selected><?= lang('select_work_on_book'); ?></option>
                            <option value="1"><?= lang('authoring'); ?></option>
                            <option value="2"><?= lang('translate'); ?></option>
                            <option value="3"><?= lang('checking'); ?></option>
                            <option value="4"><?= lang('reviewing'); ?></option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <!-- publisher and publish_place -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="publisher" class="blue-text mb-2 me-4"><?= lang('publisher'); ?></label>
                        <select class="form-control" id="publisher" name="publisher" required>
                            <option value="" disabled selected><?= lang('select_publisher'); ?></option>
                            <?php
                            $publishers = get_all_data("select * from publisher");
                            foreach ($publishers as $pub) {
                                echo '<option value="' . $pub['pub_id'] . '">' . $pub['pub_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="publish_place" class="blue-text mb-2 me-4"><?= lang('publish_place'); ?></label>
                        <input class="form-control" type="text" id="publish_place" name="publish_place" placeholder="<?= lang('publish_placeholder'); ?>" autocomplete="off" required />
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <!-- language and book_location -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="language" class="blue-text mb-2 me-4"><?= lang('language'); ?></label>
                        <select class="form-control" name="language" required>
                            <option value="" disabled selected><?= lang('select_pub_lang'); ?></option>
                            <?php
                            $langs = get_all_data(SELECT_LANG);
                            foreach ($langs as $lang) {
                                echo '<option value="' . $lang['lang_id'] . '">' . $lang['lang_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="book_location" class="blue-text mb-2 me-4"><?= lang('book_location'); ?></label>
                        <input class="form-control" type="text" id="book_location" name="book_location" placeholder="<?php echo lang('book_location_place') ?>" autocomplete="off" />
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <!-- isbn and depository_no -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="isbn" class="blue-text mb-2 me-4"><?= lang('isbn'); ?></label>
                        <input class="form-control" type="text" id="isbn" name="isbn" placeholder="<?php echo lang('isbn_place') ?>" autocomplete="off" />
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-3 text-end">
                        <label for="depository_no" class="blue-text mb-2 me-4"><?= lang('depository_no'); ?></label>
                        <input class="form-control" type="text" id="depository_no" name="depository_no" placeholder="<?php echo lang('depository_no_place') ?>" autocomplete="off" />
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <!-- Radio button for part or multi part -->
            <div class="chose-how-part mt-4">
                <span class="dark-blue-text">
                    <?php echo lang('is_book') ?>
                </span>
                <div class="form-check form-check-inline">
                    <label class="form-check-label blue-text" for="one_part">
                        <?php echo lang('one_part') ?>
                    </label>
                    <input class="form-check-input" type="radio" name="chose_part" id="one_part" value="one" checked>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label blue-text" for="multi_parts">
                        <?php echo lang('multi_parts') ?>
                    </label>
                    <input class="form-check-input" type="radio" name="chose_part" value="multi" id="multi_parts">
                </div>
            </div>

            <!-- one-part-container -->
            <div class="one-part-container">
                <input class="form-control" type="hidden" id="part_number" name="part_number[]" value="1" />
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="edition_no" class="blue-text mb-2 me-4"><?= lang('edition_no'); ?></label>
                            <input class="form-control" type="number" id="edition_no" name="edition_no[]" placeholder="1" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="edition_desc" class="blue-text mb-2 me-4"><?= lang('edition_desc'); ?></label>
                            <input class="form-control" type="text" id="edition_desc" name="edition_desc[]" placeholder="<?php echo lang('edition_desc_place') ?>" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="publish_date" class="blue-text mb-2 me-4"><?= lang('publish_date'); ?></label>
                            <input class="form-control" type="month" id="publish_date[]" name="publish_date[]" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="pages_no" class="blue-text mb-2 me-4"><?= lang('pages_no'); ?></label>
                            <input class="form-control" type="number" id="pages_no" name="pages_no[]" placeholder="512" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="price" class="blue-text mb-2 me-4"><?= lang('price'); ?></label>
                            <input class="form-control" type="number" id="price" name="price[]" placeholder="20 $" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="no_of_copy" class="blue-text mb-2 me-4"><?= lang('no_of_copy'); ?></label>
                            <input class="form-control" type="number" id="no_of_copy" name="no_of_copy[]" placeholder="20" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- multi-part-container -->
            <div class="multi-part-container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="part_no" class="blue-text mb-2 me-4"><?= lang('part_no'); ?></label>
                            <input class="form-control" type="number" id="part_no" name="part_no" placeholder="3" onkeyup="howParts()" />
                            <div class="invalid-feedback" id="no_part_check"></div>
                        </div>
                    </div>
                </div>
                <div id="more_part">
                </div>
            </div>

            <!-- series details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 mb-2 text-end">
                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" id="checkbox_has_series" name="checkbox_has_series" />
                            <label for="checkbox_has_series" class="dark-blue-text"><?= lang('is_book_has_series'); ?></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="series-group">
                <div class="row series_group">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="series_name" class="blue-text mb-2 me-4"><?= lang('series_name'); ?></label>
                            <input type="text" autocomplete="off" list="series_auto_complete" class="form-control mb-3" id="series_name" name="series_name" placeholder="<?php echo lang('series_name_place') ?>">
                            <datalist id="series_auto_complete">
                                <?php
                                $rows = get_all_data("SELECT * FROM series");
                                if ($rows != 0) {
                                    foreach ($rows as $row) {
                                        echo  ' <option value="' . $row['series_name'] . '"></option>';
                                    }
                                }
                                ?>
                            </datalist>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="no_in_series" class="blue-text mb-2 me-4"><?= lang('no_in_series'); ?></label>
                            <input class="form-control" type="number" id="no_in_series" name="no_in_series" placeholder="4" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- attachment details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mt-3 mb-3 text-end">
                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" id="checkbox_has_attachment" name="checkbox_has_attachment" />
                            <label for="checkbox_has_attachment" class="dark-blue-text"><?= lang('is_book_has_attachment'); ?></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="attachment-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="attachment_name" class="blue-text mb-2 me-4"><?= lang('attachment_name'); ?></label>
                            <input class="form-control" type="text" id="attachment_name" name="attachment_name" placeholder="<?php echo lang('attachment_name_place') ?>" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="attachment_type" class="blue-text mb-2 me-4"><?= lang('attachment_type'); ?></label>
                            <input class="form-control" type="text" id="attachment_type" name="attachment_type" placeholder="<?php echo lang('attachment_type_place') ?>" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 text-end">
                            <label for="attachment_loc" class="blue-text mb-2 me-4"><?= lang('attachment_loc'); ?></label>
                            <input class="form-control" type="text" id="attachment_loc" name="attachment_loc" placeholder="<?php echo lang('attachment_loc_place') ?>" autocomplete="off" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- buttons -->
            <div class="modal-footer  mt-4 mb-2 justify-content-evenly">
                <button class="btn blue-btn add rounded-3 fw-bold" type="submit" id="submit"><?php echo lang('save') ?></button>
                <button class="btn blue-btn cancel me-auto rounded-3 fw-bold" onclick="window.location.href='index.php'"><?php echo lang('cancel') ?></button>
            </div>
        </div>
    </form>

</div>

<?php
// footer section
include $tmpl . 'footer.php';
