<?php
require_once 'init.php';
$pageTitle =  lang('add_publisher');
$activePage = "publishers";
require_once $tmpl . 'header.php';
?>
<?php
//check if has privilege
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['pub_lang'])) {
        $pub_name           = filter_var(strip_tags($_POST['pub_name']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $sequence_no        = filter_var(strip_tags($_POST['sequence_no']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $establish_date     = filter_var(strip_tags($_POST['establish_date']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $pub_owner          = filter_var(strip_tags($_POST['pub_owner']), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
        $pub_lang           = filter_var(strip_tags($_POST['pub_lang']), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
// echo "<br/><br/><br/><br/><br/>";
// echo $pub_name ."<br/>";
// echo $sequence_no ."<br/>";
// echo $establish_date ."<br/>";
// echo $pub_owner ."<br/>";
// echo $pub_lang ."<br/>";

        if (!empty($pub_name) && !empty($sequence_no) && !empty($establish_date) && !empty($pub_owner) && !empty($pub_lang)) {
            $good_date = date_create($establish_date);
            if ($good_date) {
                if (date_format($good_date, "Y-m-d")) {
                    //insert data to DB
                    $results = insert_data(INSERT_PUB, [$pub_name, $establish_date, $pub_owner, $sequence_no]);
                    $results1 = insert_data(INSERT_PUB_LANG, [$pub_lang]);
                    //check if data inserted
                    if ($results && $results1) {
                        redirect_user(lang('publish_success'), 5, "publishers.php", "info");
                    } else {
                        $result = lang('publish_error');
                    }
                }
            } else {
                $result = lang('bad_date_format');
            }
        } else {
        }
    } else {
        $result = lang('loan_empty');
    }
}
?>
<div class="mt-5 d-flex align-items-center justify-content-center">
    <div class="add-publisher-form ">
        <div class="container my-5 shadow d-flex align-items-center justify-content-center">
            <div class="row bg-white">
                <div class="col p-3">
                    <h4 class="text-uppercase text-center blue-text m-3 mb-2"><?php echo lang('add_publisher'); ?></h4>
                    <hr class="mb-1" />
                    <!-- print error msg -->
                    <?php if (!empty($result)) {
                        echo  '<div class="text-center alert alert-danger alert-dismissible fade show error-alert" role="alert" >
                            <span>' . $result . '</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button></div>';
                    } ?>
                    <main class="all-form m-4">
                        <form name="add-publisher" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

                            <div class="form-group abs-con mt-3">
                                <label for="pub_name" class="blue-text"><?= lang('pub_name'); ?></label>
                                <input class="form-control" type="text" id="pub_name" name="pub_name" onkeyup="checkField('publisher', '#pub_name', '#check_pub_name')" placeholder="<?php echo lang('pub_name_place') ?>" autofocus required/>
                                <span class="invalid-feedback" id="check_pub_name"></span>
                            </div>

                            <div class="form-group abs-con mt-3">
                                <label for="sequence_no" class="blue-text"><?= lang('sequence_no'); ?></label>
                                <input class="form-control" id="sequence_no" type="text" onkeyup="checkField('publisher_seq', '#sequence_no', '#check_sequence')" placeholder="<?php echo lang('sequence_no_place') ?>" name="sequence_no" required />
                                <span class="invalid-feedback" id="check_sequence"></span>
                            </div>

                            <div class="form-group abs-con mt-3">
                                <label for="establish_date" class="blue-text"><?= lang('establish_date'); ?></label>
                                <input type="date" class="form-control" id="establish_date" name="establish_date" onchange="checkDate('just_one','#establish_date')" required>
                                <span class="invalid-feedback" ></span>

                            </div>

                            <div class="form-group abs-con mt-3">
                                <label for="pub_owner" class="blue-text"><?= lang('pub_owner'); ?></label>
                                <input class="form-control" type="text" placeholder="<?php echo lang('pub_owner_place') ?>" name="pub_owner" required />
                            </div>

                            <div class="form-group abs-con mt-3">
                                <label for="pub_lang" class="blue-text"><?= lang('pub_lang'); ?></label>
                                <select required class="form-control" name="pub_lang" required>
                                    <option value="" disabled selected><?= lang('select_pub_lang'); ?></option>
                                    <?php
                                    $langs = get_all_data(SELECT_LANG);
                                    foreach ($langs as $lang) {
                                        echo '<option value="' . $lang['lang_id'] . '">' . $lang['lang_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="modal-footer  mt-4 mb-2 justify-content-evenly">
                                <button class="btn blue-btn add rounded-3 fw-bold" type="submit" id="submit"><?php echo lang('save') ?></button>
                                <button class="btn blue-btn cancel me-auto rounded-3 fw-bold" onclick="window.location.href='publishers.php'"><?php echo lang('cancel') ?></button>
                            </div>
                        </form>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $tmpl . 'footer.php'; ?>