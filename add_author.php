<?php
// include  file
require_once 'init.php';
$pageTitle =  lang('authors');
$activePage = "authors";
require_once $tmpl . 'header.php';

// insert and upload status code
$insert_status = -1;
$uploadImgStatus = -1;

//message
$statusMsg = "";
$msgContainer = "d-none";

// File upload path
$upload_dir = "upload/authors/";

// initialize all variable
$author_name = filter_input(INPUT_POST, 'author_name', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
$nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
$profession = filter_input(INPUT_POST, 'profession', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
$birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
$deathday = filter_input(INPUT_POST, 'deathday', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
$author_description = filter_input(INPUT_POST, 'author_description', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";

// validate session
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

// validate post param
if (
    filter_input(INPUT_POST, 'author_name', FILTER_SANITIZE_SPECIAL_CHARS) &&
    filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_SPECIAL_CHARS) &&
    filter_input(INPUT_POST, 'profession', FILTER_SANITIZE_SPECIAL_CHARS) &&
    filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_SPECIAL_CHARS) &&
    filter_input(INPUT_POST, 'author_description', FILTER_SANITIZE_SPECIAL_CHARS)
) {
    //check project start date not grate than end date
    if (filter_input(INPUT_POST, 'deathday', FILTER_SANITIZE_SPECIAL_CHARS)) {

        echo "<br><br><br><br><br>";
        echo "eroooooooooooooo";

        if (strtotime($deathday) <= strtotime($birthday)) {
            $insert_status = 0;
            $statusMsg = lang('author_date_error');
        }
    }
    else{
        echo "<br><br><br><br><br>";
        echo "okkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk";
    }

    // validate profile img
    if (
        $insert_status != 0 &&
        !empty($_FILES["author_profile"]["name"])
    ) {
        // File upload name
        $author_profile = basename($_FILES["author_profile"]["name"]);

        // File upload path 
        $target_file = $upload_dir . $author_profile;

        //check if the file name is not repeated
        if (!file_exists($target_file)) {
            //allowed file extension
            $allow_types = array('png', 'jpeg', 'jpg');
            // get uploaded file's extension
            $file_extension = strtolower(pathinfo($author_profile, PATHINFO_EXTENSION));

            // Check whether file type is valid  
            if (in_array($file_extension, $allow_types)) {

                // Upload file to server  
                if (move_uploaded_file($_FILES["author_profile"]["tmp_name"], $target_file)) {
                    $uploadImgStatus = 1;
                } else {
                    $uploadImgStatus = 0;
                    $statusMsg = lang('author_error_uploading');
                }
            } else {
                $uploadImgStatus = 0;
                $statusMsg = lang('not_allowed') . join(", ", $allow_types);
            }
        } else {
            $uploadImgStatus = 0;
            $statusMsg = lang('author_photo_exists');
        }
    }

    if ($uploadImgStatus != 0) {

        // initialize image
        if ($uploadImgStatus == -1) {
            $author_profile = 'auth_temp.svg';
        }
        // check deathday
        if (empty($deathday)) {
            $sql = INSERT_AUTHOR_NULL;
            $value = [$author_profile, $author_name, $author_description, $profession, $nationality, $birthday];
        } else {
            $sql = INSERT_AUTHOR;
            $value = [$author_profile, $author_name, $author_description, $profession, $nationality, $birthday, $deathday];
        }

        //insert data to DB
        $results = insert_data($sql, $value);

        //check if data inserted
        if ($results) {
            $insert_status = 1;
            $statusMsg = lang('add_author_success');
        } else {
            $insert_status = 0;
            $statusMsg = lang('add_author_fail');
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $insert_status = 0;
    $statusMsg = lang('fill_all_filled');
}

// to change color message if insert success
if ($insert_status == 1) {
    $msgContainer = "alert-success";
}
// if insert or upload fail
else if ($uploadImgStatus == 0 || $insert_status == 0) {
    $msgContainer = "alert-danger";
}

?>
<br><br><br><br>

<!-- // add author section -->
<div class="container rounded card shadow pb-5 mb-5" id="add_author">

    <!-- page title -->
    <div class="row my-4">
        <div class="col-12">
            <span class="display-6 mx-4" id="add_author_title">
                <svg enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="m490.685 314.751c-9.172-12.021-20.956-21.936-34.243-28.987 7.099-12.131 11.171-26.239 11.171-41.279v-38.456c0-45.216-36.798-82.002-82.028-82.002-16.235 0-31.712 4.818-44.771 13.29v-77.396c-.001-33.041-26.881-59.921-59.921-59.921h-135.527v60.527c0 20.297 10.144 38.269 25.628 49.113v27.552c-13.018-8.395-28.423-13.165-44.578-13.165-45.23 0-82.028 36.786-82.028 82.002v38.456c0 15.041 4.072 29.148 11.171 41.279-13.288 7.05-25.072 16.966-34.243 28.986-13.945 18.277-21.316 40.114-21.316 63.152v134.098h512v-134.098c0-23.038-7.371-44.875-21.315-63.151zm-315.319-284.751h105.526c16.498 0 29.921 13.422 29.921 29.921v30.527h-105.526c-16.498 0-29.921-13.422-29.921-29.921zm135.447 90.448v40.639c0 30.262-24.632 54.882-54.909 54.882s-54.91-24.62-54.91-54.882v-40.792c1.418.101 2.85.152 4.293.152h105.526zm-199.164 361.552h-81.649v-104.098c0-30.197 18.607-57.407 46.102-68.695 10.204 7.945 22.328 13.536 35.548 15.948v156.845zm0-189.941v2.296c-21.519-6.377-37.262-26.32-37.262-49.869v-38.456c0-28.674 23.34-52.002 52.029-52.002 21.794 0 41.433 13.643 48.952 33.976 2.281 6.802 5.401 13.223 9.238 19.138-41.241 6.228-72.957 41.935-72.957 84.917zm129.255 189.941h-99.255v-189.941c0-27.673 20.197-50.712 46.61-55.119.351 10.471 1.259 22.983 3.238 36.559 6.904 47.369 23.941 86.469 49.407 113.573zm-22.561-244.796c11.331 5.61 24.084 8.766 37.562 8.766 13.493 0 26.26-3.163 37.601-8.785-1.198 31.964-8.082 84.105-37.597 121.399-29.442-37.245-36.342-89.44-37.566-121.38zm151.816 244.796h-99.255v-94.928c25.467-27.108 42.504-66.235 49.408-113.65 1.972-13.54 2.881-26.023 3.234-36.483 26.414 4.406 46.613 27.446 46.613 55.12zm-42.958-274.859c3.804-5.865 6.904-12.226 9.179-18.963l.164.06c7.454-20.463 27.161-34.211 49.04-34.211 28.688 0 52.028 23.328 52.028 52.002v38.456c0 23.619-15.836 43.611-37.453 49.925v-2.352c0-42.981-31.716-78.688-72.958-84.917zm154.799 274.859h-81.841v-156.809c13.294-2.393 25.486-8 35.74-15.983 27.494 11.287 46.101 38.498 46.101 68.694z"></path>
                    </g>
                </svg>
                <?= lang("add_author") ?>
            </span>
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
                var second = 50;
                $('#second').text(second);
                var redirectTime = setInterval(myTimer, 1000, 'authors.php');
            </script>
        ");
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="row px-5">

            <!-- author photo -->
            <div class="col-md-4 text-center mt-4">
                <label for="author_profile" class="profile-upload">
                    <div class="profile-container">
                        <img src="upload/authors/auth_temp.svg" alt="author image" id="profile_img">
                    </div>
                    <div class="camera-icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-camera-circle"></i>
                    </div>
                </label>
                <input type="file" name="author_profile" id="author_profile" onchange="checkFileExist(this, 'authors')" hidden accept="image/png, image/jpeg">
                <div class="invalid-feedback"></div>
            </div>

            <div class="col-md-8 text-md-end text-sm-center">

                <div class="mt-3 text-end">
                    <label for="author_name" class="blue-text mb-2 me-4"><?= lang('author_name'); ?></label>
                    <input required class="form-control" type="text" id="author_name" name="author_name" onkeyup="checkName('check_author.php', {author_name: $(this).val()}, $(this)) " placeholder="<?php echo lang('author_name_place') ?>" value="<?= htmlspecialchars($author_name) ?>" autofocus />
                    <div class="invalid-feedback"></div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mt-3 text-end">
                            <label for="nationality" class="blue-text mb-2 me-4"><?= lang('nationality'); ?></label>
                            <input required class="form-control" type="text" id="nationality" name="nationality" placeholder="<?php echo lang('nationality_place') ?>" value="<?= htmlspecialchars($nationality) ?>" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mt-3 text-end">
                            <label for="profession" class="blue-text mb-2 me-4"><?= lang('profession'); ?></label>
                            <input required class="form-control" type="text" id="profession" name="profession" placeholder="<?php echo lang('profession_place') ?>" value="<?= htmlspecialchars($profession) ?>" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mt-3 text-end">
                            <label for="birthday" class="blue-text mb-2 me-4"><?= lang('birthday'); ?></label>
                            <input required class="form-control" type="date" id="birthday" onchange="checkDate('#birthday','#deathday')" name="birthday" value="<?= htmlspecialchars($birthday) ?>" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mt-3 text-end">
                            <label for="deathday" class="blue-text mb-2 me-4"><?= lang('deathday'); ?></label>
                            <input class="form-control" type="date" id="deathday" onchange="checkDate('#birthday','#deathday')" name="deathday" value="<?= htmlspecialchars($deathday) ?>" />
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- description -->
                <div class="mt-3 text-end">
                    <label for="author_description" class="blue-text mb-2 me-4"><?= lang('author_description'); ?></label>
                    <textarea required class="form-control" id="author_description" name="author_description" rows="3" placeholder="<?php echo lang('author_description_place') ?>"><?= htmlspecialchars($author_description) ?></textarea>
                </div>

                <!-- save and cancel btn -->
                <div class="row mt-4">
                    <div class="col-6 text-end">
                        <input type="submit" style="width: 80%;" class="btn blue-btn rounded-3 fw-bold add" id="submit" value="<?php echo lang('save') ?>">
                    </div>

                    <div class="col-6 text-start">
                        <button type="button" style="width: 80%;" class="btn blue-btn rounded-3 fw-bold cancel" onclick="window.location.href='authors.php'"><?php echo lang('cancel') ?></button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<?php
// footer section
include $tmpl . 'footer.php';
