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
        <!-- page title -->
        <div class="row">
            <div class="advance_searchTitle text-center blue-text pt-4 pb-4" id="advance_title">
                <h1><?= lang('advance_search'); ?></h1>
            </div>
        </div>

        <form name="advance_search" action="search_result.php" method="POST">
            <!-- search text -->
            <div class="row align-content-center justify-content-center mt-5 mb-2">
                <div class="form-group col-lg-8 col-md-8 col-sm-8">
                    <div class="search">
                        <!-- <label for="search_input" class="form-label mb-2 pe-1 mt-2"><?= lang('search_about'); ?></label> -->
                        <i class="fa-duotone fa-magnifying-glass" id="search_icon" style="--fa-animation-duration: 1s;"></i>
                        <!-- id="search_input" -->
                        <input style="padding-left: 100px;" required type="text" name="search_input" id="search_txt" autocomplete="off" class="form-control" placeholder="<?php echo lang('search_about') ?>" autofocus>
                        <input type="submit" id="search_btn" value="<?= lang('search_btn'); ?>" class="btn btn-primary">
                    </div>
                </div>
            </div>
            <!-- search option -->
            <div class="row align-content-center justify-content-center pb-5">
                <!-- option tile  -->
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <label for="search_use" class="form-label mb-2 pe-1 mt-2"><?= lang('search_use'); ?></label>
                </div>

                <!-- option radio -->
                <div class="col-lg-8 col-md-8 col-sm-8">

                    <div class="radio-tile-group row">

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option1" name="search_option" value="title" checked>
                                <label class="radio-tile" for="search_option1">
                                    <i class="fas fa-book"></i>
                                    <span><?= lang('book_title'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option2" name="search_option" value="author_name">
                                <label class="radio-tile" for="search_option2">
                                    <i class="fas fa-user"></i>
                                    <span><?= lang('author'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option3" name="search_option" value="isbn">
                                <label class="radio-tile" for="search_option3">
                                    <i class="fas fa-barcode"></i>
                                    <span><?= lang('isbn'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option4" name="search_option" value="dewey_no">
                                <label class="radio-tile" for="search_option4">
                                    <i class="fas fa-bookmark"></i>
                                    <span><?= lang('dewyNo'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option5" name="search_option" value="publication_date">
                                <label class="radio-tile" for="search_option5">
                                    <i class="far fa-calendar-alt"></i>
                                    <span> <?= lang('publish_date'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option6" name="search_option" value="cat_name">
                                <label class="radio-tile" for="search_option6">
                                    <i class="fas fa-list"></i>
                                    <span><?= lang('category'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option7" name="search_option" value="pub_name">
                                <label class="radio-tile" for="search_option7">
                                    <i class="fas fa-building"></i>
                                    <span><?= lang('publisher'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 col-md-4 col-sm-4">
                            <div class="input-container">
                                <input type="radio" id="search_option8" name="search_option" value="lang_name">
                                <label class="radio-tile" for="search_option8">
                                    <i class="fas fa-language"></i>
                                    <span><?= lang('language'); ?></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </form>
    </div>

</div>

<script>
    // Add an event listener to the radio buttons
    document.querySelectorAll(' input[type="radio" ]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Check if the "Publication Date" option is selected 
            if (this.value === 'publication_date') {
                // Change the input type to "date" 
                document.getElementById('search_txt').type = 'month';
            } else {
                // Change back to "text" for other options 
                document.getElementById('search_txt').type = 'text';
            }
        });
    });
</script>

<?php include $tmpl . 'footer.php'; ?>