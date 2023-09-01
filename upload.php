<?php
// include required file
require_once 'init.php';
require_once $tmpl . 'header.php';

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

// validate get param
if (!filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT)) {
    redirect_user("", 0, "index.php");
}

$book_id = filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT);

?>
<!-- template for upload file  -->
<div class="mt-5 d-flex align-items-center justify-content-center">
    <div class="upload-form ">
        <div class="container my-5 shadow d-flex align-items-center justify-content-center">
            <div class="row bg-white">
                <div class="col p-3">
                    <h4 class="text-uppercase text-center blue-text m-3 mb-2"><?php echo lang('upload_file'); ?></h4>
                    <hr class="mb-1" />
                    <main class="all-form m-4">
                        <label class="form-label fs-5 mt-3" for="fileToUpload"><?php echo lang('select_file'); ?></label>
                        <input type="file" onchange="validate_file(this.files[0].name)" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf" class="form-control m-3" name="fileToUpload" id="fileToUpload" />
                        <!-- to save project id to access it in JS  -->
                        <input type="hidden" id="book_id" value="<?= $book_id ?>">

                        <!-- upload btn -->
                        <div class="d-grid">
                            <button onclick="upload()" id="upload_btn" class="btn blue-btn rounded-3 mt-2 mb-2 fw-bold"><?php echo lang('upload') ?></button>
                        </div>
                        <!-- loading and cancel btn -->
                        <div class="d-grid">

                            <button class="btn btn-primary d-none" id="loading_btn" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <?php echo lang('load_btn'); ?>
                            </button>

                            <button class="btn btn-secondary mt-2 mb-2 d-none" id="cancel_btn">
                                <?php echo lang('cancel_btn'); ?>
                            </button>
                        </div>

                        <!-- progress bar -->
                        <div class="col-12 mt-2 mb-2 d-none" id="progress_contain">
                            <div class="progress">
                                <div id="progress_load" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    <label id="progress_status">50%</label>
                                </div>
                            </div>

                        </div>

                        <!-- alert section -->
                        <div class="col-12 mt-2 mb-2" id="alert_wrapper"></div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $tmpl . 'footer.php'; ?>