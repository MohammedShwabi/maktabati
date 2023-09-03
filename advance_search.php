<?php
require_once 'init.php';
$pageTitle = lang("advance_search_title");
$activePage = "advance_search";
require_once $tmpl . 'header.php';
if (!isset($_SESSION['UserEmail'])) {
    //to allow only authorized user
    redirect_user();
}
?>

<div class="searchForm pt-3  mt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="advance_searchTitle text-center blue-text pt-4 pb-4" id="advance_title">
                <h1><?= lang('advance_search'); ?></h1>
            </div>
        </div>
        <div class="alert alert-danger text-center fw-bold fs-4" id="all_field_empty" style="display:none;"><?= lang('input_search_empty'); ?> </div>

        <form name="advance_search" action="search_result.php" method="POST">
            <div class="row align-content-center justify-content-center">
                <div class="form-group col-lg-8 col-md-8 col-sm-8">
                    <label for="search_input" class="form-label mb-2 pe-1 mt-2"><?= lang('search_about'); ?></label>
                    <input required type="text" autocomplete="off" class="form-control mb-3" id="search_input" name="search_input" placeholder="<?php echo lang('search') ?>" autofocus>
                    <!-- <span class="invalid-feedback" id="check_title"></span>
                    <datalist id="book_auto_complete"></datalist> -->
                </div>
                <div class="form-group col-lg-8 col-md-8 col-sm-8">
                    <label for="search_use" class="form-label mb-2 pe-1 mt-2"><?= lang('search_use'); ?></label><br>
                    
                    <input type="radio" id="search_option1" name="search_option" value="title" checked>
                    <label for="search_option1"><?= lang('book_title'); ?></label><br>
                    <input type="radio" id="search_option2" name="search_option" value="author_name">
                    <label for="search_option1"><?= lang('author'); ?></label><br>
                    <input type="radio" id="search_option3" name="search_option" value="isbn">
                    <label for="search_option1"><?= lang('isbn'); ?></label><br>
                    <input type="radio" id="search_option4" name="search_option" value="dewey_no">
                    <label for="search_option1"><?= lang('dewyNo'); ?></label><br>
                    <input type="radio" id="search_option5" name="search_option" value="publication_date">
                    <label for="search_option1"><?= lang('publish_date'); ?></label><br>
                    <input type="radio" id="search_option6" name="search_option" value="cat_name">
                    <label for="search_option1"><?= lang('category'); ?></label><br>
                    <input type="radio" id="search_option7" name="search_option" value="pub_name">
                    <label for="search_option1"><?= lang('publisher'); ?></label><br>
                    <input type="radio" id="search_option8" name="search_option" value="lang_name">
                    <label for="search_option1"><?= lang('language'); ?></label><br>

                </div>
            </div>

            <div class="row align-content-center justify-content-center mt-4 pb-5">
                <div class="form-group col-lg-2 col-md-6 col-sm-6">
                    <div class="d-grid gap-2">
                        <button class="btn blue-btn rounded-3 mt-2 mb-2 fw-bold" id="submit" type="submit"><i class="fa-duotone fa-magnifying-glass"></i> <?php echo lang('search') ?></button>
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-2   col-md-6 col-sm-6">
                    <div class="d-grid gap-2">
                        <button class="btn  rest-btn rounded-3 mt-2 mb-2 fw-bold" type="reset"><i class="fa-duotone fa-eraser"></i> <?php echo lang('rest') ?></button>
                    </div>
                </div>
            </div>

        </form>

        <script>
        // Add an event listener to the radio buttons
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Check if the "Publication Date" option is selected
                if (this.value === 'publication_date') {
                    // Change the input type to "date"
                    document.getElementById('search_input').type = 'month';
                } else {
                    // Change back to "text" for other options
                    document.getElementById('search_input').type = 'text';
                }
            });
        });
    </script>

    </div>

</div>

<?php include $tmpl . 'footer.php'; ?>