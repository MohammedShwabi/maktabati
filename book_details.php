<?php

use LDAP\Result;

require_once 'init.php';
$pageTitle = lang("book_details");
require_once $tmpl . 'header.php';
?>
<?php
//check if has privilege
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
// validate get param
if (!filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT)) {
    redirect_user(lang("No_ID"), 5, "index.php");
}
$book_id = filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT);
//get all  data about the book
$row = get_data(SELECT_BOOK_INFO, [$book_id]);

if ($row <= 0) {
    redirect_user(lang("No_ID"), 5, "index.php");
} ?>
<!--start popup rating  -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  blue-text " id="staticBackdropLabel"><?php echo lang('rating_popup') ?></h5>
                <button type="button" class="btn-close m-0 " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="rate.php" method="POST">
                <div class="modal-body text-center m-4">

                    <div class="stars">
                        <?php
                        for ($i = 5; $i > 0; $i--) {
                        ?>
                            <label class="rate">
                                <input type="radio" required name="rate" value="<?= $i ?>">
                                <div class="face"></div>
                                <i class="fa-solid fa-star"></i>
                            </label>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer  justify-content-evenly">
                    <!-- put book id here -->
                    <input type="hidden" name="id" value="<?php echo $row['book_id'] ?>" />
                    <input type="submit" class="add btn btn-primary" value="<?php echo lang('add') ?>" />
                    <button type="button" class="cancel btn btn-secondary" data-bs-dismiss="modal"><?php echo lang('cancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if ($row['rating'] != 0) {
?>
    <script>
        $('.rate').eq(<?= abs($row['rating'] - 5) ?>).addClass("active");
        $("input:radio[name='rate']").eq(<?= abs($row['rating'] - 5) ?>).prop("checked", true);
    </script>
<?php
}
?>
<!-- end popup rating  -->
<div class="book-detail mt-5">
    <?php
    // author breadcrumb
    // name => url : href= "example.php"
    // name => class: class="active" this for the current items
    // note : the last element should be the active class element
    $items = array(
        lang("home") => 'href="index.php"',
        $row['cat_name'] => 'href="category_section.php?cat=' . $row['cat_id'] . '"',
        $row['author_name'] => 'href="author_section.php?auth=' . $row['author_id'] . '"',
        $row['title'] => 'class="active"'
    );
    breadcrumb($items);
    ?>
    <div class="container-fluid">

        <div class="detail-box m-4 ">
            <div class="row">
                <div class="col-lg-2 col-md-4">
                    <img class="img-fluid img-thumbnail rounded-2" src="./upload/books/<?php echo $row['photo'] ?>" alt=" <?php echo $row['title'] ?>">
                </div>
                <div class="col-lg-10 col-md-8">
                    <div class="row">
                        <div class="bookT mt-3  dark-blue-text fs-4">
                            <?php echo $row['title'] ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="bookAuth mt-3 blue-text fs-6 more-details ">
                            <a href="author_section.php?auth=<?php echo $row['author_id'] ?>"> <?php echo $row['author_name'] ?></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="bookCat  mt-3 blue-text fs-6 more-details ">
                            <a href="category_section.php?cat=<?php echo $row['cat_id'] ?>"> <?php echo $row['cat_name'] ?> </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="rating mt-3 mb-4">
                            <?php echo star_fill($row['rating']) ?>
                            <b></b>
                        </div>
                    </div>
                    <div class="all-detail text-center mb-3">
                        <div class="row">
                            <div class="col-lg-1 col-md-2 col-6 custom-border">
                                <p class="detail p-0"><?php echo lang('pages_no') ?></p>
                                <p class="dark-blue-text"> <?php echo $row['pages_no'] ?></p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-6  custom-border">
                                <p class="detail"><?php echo lang('language') ?></p>
                                <p class="dark-blue-text"> <?php echo $row['lang_name'] ?>
                                    <!-- i while get from another table  -->
                                </p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-6  custom-border">
                                <p class="detail"><?php echo lang('size') ?></p>
                                <p class="dark-blue-text"> <?php echo $row['size'] ?> </p>
                            </div>
                            <div class="col-lg-1  col-md-2  col-6 custom-border">
                                <p class="detail"><?php echo lang('file_type') ?></p>
                                <p class="dark-blue-text"><?php echo $row['format'] ?> </p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-6  custom-border">
                                <p class="detail"><?php echo lang('no_of_copy') ?></p>
                                <p class="dark-blue-text"> <?php echo $row['num_of_copies'] ?></p>
                            </div>
                            <div class="col-lg-2 col-md-6 col-6  custom-border">
                                <p class="detail"><?php echo lang('publisher') ?></p>
                                <p class="dark-blue-text"> <?php echo $row['pub_name'] ?> </p>
                            </div>
                            <div class="col-lg-2 col-md-6  col-6">
                                <p class="detail"><?php echo lang('isbn') ?></p>
                                <p class="dark-blue-text"> <?php echo $row['isbn'] ?> </p>
                            </div>
                        </div>
                    </div>
                    <div class="row  mt-4 mb-4 justify-content-center text-center">
                        <div class="col-lg-3  col-md-6 col-6 dark-blue-text">
                            <?php echo $row['publication_date'] ?>
                            <i class="fa-duotone fa-calendar blue-text"></i>
                        </div>
                        <div class="col-lg-3 col-md-6 col-6 dark-blue-text">
                            <?php echo $row['price'] ?>
                            <i class="fa-duotone fa-dollar-sign blue-text"></i>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="book-desc ">
                <h4 class="blue-text pe-4 p-3"><?php echo lang('book_description') ?> : </h4>
                <div class="description pe-5 pb-3 ps-5">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <?php
                    // Retrieve the full description from a database row
                    $full_desc = $row['description'];

                    // Truncate the full description to a maximum of 50 words
                    $truncated_desc = truncateText($full_desc, 50);

                    // Display the truncated description within a span with the 'short-text' class
                    echo "<span class='short-text'>" . $truncated_desc . "</span>";

                    // Check if the full description contains more than 50 words
                    if (countArabicWords($full_desc) > 50) {
                        // If so, display the full description within a span with the 'more-text' class
                        echo "<span class='more-text'>" . $full_desc . "</span>";

                        // Display 'Read More' and 'Read Less' buttons using language-specific labels
                        echo "<span class='more-btn'>" . lang('read_more') . "</span>";
                        echo "<span class='less-btn'>" . lang('read_less') . "</span>";
                    }

                    ?>
                </div>
            </div>
        </div>



        <!-- check if file is exist and it has a soft copy then show download and open btn or show upload btn  -->
        <div class="row align-content-center justify-content-center p-5  ">
            <?php
            //to get file name of the book that stored in DB 
            $results = get_data(SELECT_FILE_PATH, [$book_id]);
            $filename = $results['part_path'];

            if (!empty($filename)) {
                $filename = basename($filename);
                $filepath = 'upload/pdf/' . $filename;
                //check if file is a soft copy or not
                if (!empty($filename) && file_exists($filepath)) {
            ?>
                    <a href="download.php?id=<?php echo $row['book_id'] ?>&do=download" class="btn book-detail-btn col-sm-6 col-md-4  col-lg-2 text-center ">
                        <span><i class="fa-duotone fa-download ps-3" style=" padding-top: 5px;"></i></span>
                        <?php echo lang('download') ?>
                    </a>
                    <a href="download.php?id=<?php echo $row['book_id'] ?>&do=open" class="btn book-detail-btn col-sm-6 col-md-4  col-lg-2 ">
                        <span><i class="fa-duotone fa-book-open ps-3 " style=" padding-top: 5px;"></i> </span>
                        <?php echo lang('open') ?>
                    </a>
                <?php } else { ?>
                    <a href="upload.php?book_id=<?php echo $row['book_id'] ?>" class="btn book-detail-btn col-sm-6 col-md-4  col-lg-2 ">
                        <span><i class="fa-duotone fa-upload ps-3 " style=" padding-top: 5px;"></i></span>
                        <?php echo lang('upload') ?>
                    </a>
            <?php }
            }

            ?>

            <a href="#" class="btn book-detail-btn col-sm-6 col-md-4 col-lg-2 " data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <span><i class="fa-solid fa-star ps-3 " style=" padding-top: 5px ; color: #F9B13B;"></i></span>
                <?php echo lang('rating') ?>
            </a>

            <?php
            //check if the book not one copy or if it more than one check if all copy don't borrow
            $count = 0;
            $results = get_all_data(SELECT_PART, [$row['book_id']]);
            // var_dump($results);
            if ($results != 0) {
                foreach ($results as $result) {
                    $result_borrow = get_data(SELECT_BORROW, [$result['copy_no']]);
                    if ($result_borrow != 0) {
                        $count++;
                    }
                }
                $allow_parts = count($results) - 1;

                if ($count ==  $allow_parts) {
                    echo '<a href="#" class="btn book-detail-btn col-sm-6 col-md-4  col-lg-2 disabled">
                        <span><i class="fa-solid fa-hand-holding-box ps-3 " style=" padding-top: 5px;"></i></span>
                        ' . lang("borrows") . '
                     </a>';
                } else {
            ?>
                    <a href="loan_book.php?book_id=<?php echo $row['book_id'] ?>" class="btn book-detail-btn col-sm-6 col-md-4  col-lg-2">
                        <span><i class="fa-solid fa-hand-holding-box ps-3 " style=" padding-top: 5px;"></i></span>
                        <?php echo lang('borrows') ?>
                    </a>
            <?php }
            }
            ?>
        </div>

    </div>

</div>
<?php include $tmpl . 'footer.php'; ?>