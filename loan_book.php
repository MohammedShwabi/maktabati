<?php
require_once 'init.php';
$pageTitle =  lang('loan_book');
require_once $tmpl . 'header.php';
?>
<?php
//check if has privilege
if (!isset($_SESSION['UserEmail'])) {
    redirect_user();
}
// validate get param
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT)) {
        redirect_user(lang("No_ID"), 5, "index.php");
    }
    $book_id = filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT);
    //check if the book not one copy or if it more than one check if all copy don't borrow
    $count = 0;
    $rows = get_all_data(SELECT_PART, [$book_id]);
    // var_dump($results);
    foreach ($rows as $row) {
        $result_borrow = get_data(SELECT_BORROW, [$row['copy_no']]);
        if ($result_borrow != 0) {
            $count++;
        }
    }
    $allow_parts = count($rows) - 1;
    if ($count ==  $allow_parts) {
        //redirect user if book just have one copy
        go_back();
    }
}
//// validate post param
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //check the list is not empty
    if (isset($_POST['loan_name']) && isset($_POST['copy_number'])) {
        //filtering all file               
        $book_id            = strip_tags(filter_var($_POST['book_id'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE));
        $loan_name          = strip_tags(filter_var($_POST['loan_name'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE));
        $copy_number        = strip_tags(filter_var($_POST['copy_number'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE));
        $loan_date          = date('Y-m-d');
        $loan_return_date   = strip_tags(filter_var($_POST['loan_return_date'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE));
        //check if all element is not empty
        if (!empty($loan_name) && !empty($copy_number) && !empty($loan_date) && !empty($loan_return_date)) {
            $good_date = date_create($loan_return_date);
            if ($good_date) {
                if (date_format($good_date, "Y-m-d")) {
                    //insert data to DB
                    $results = insert_data(INSERT_LOAN, [$loan_date, $loan_return_date, $loan_name, $copy_number]);
                    //check if data inserted
                    if ($results) {
                        $result = lang('loan_success');
                        redirect_user(lang('loan_success'), 5, "book_details.php?book_id=$book_id", "info");
                    } else {
                        //check if data don't inserted
                        $result = lang('loan_empty');
                    }
                }
            } else {
                $result = lang('bad_date_format');
            }
        } else {
            $result = lang('loan_empty');
        }
    }
}
?>

<div class="mt-5  d-flex align-items-center justify-content-center">
    <div class="loan-form ">
        <div class="container my-5 shadow d-flex align-items-center justify-content-center">
            <div class="row bg-white">
                <div class="col p-3">
                    <h4 class="text-uppercase text-center blue-text m-3 mb-2"><?php echo lang('loan_book'); ?></h4>
                    <hr class="mb-1" />
                    <!-- print error msg -->
                    <?php if (!empty($result)) {
                        echo  '<div class="text-center alert alert-danger alert-dismissible fade show error-alert" role="alert" >
                            <span>' . $result . '</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button></div>';
                    } ?>

                    <main class="all-form m-4">
                        <form name="loan" id="loan" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input name="book_id" type="hidden" value="<?php echo $book_id ?>">

                            <div class="form-group abs-con mt-3">
                                <label for="loan_name" class="blue-text"><?= lang('loan_name'); ?></label>
                                <select required class="form-control" id="loan_name" name="loan_name" onchange="checkSelectData('user','#loan_name','#check_user')">
                                    <option value="" disabled selected><?= lang('select_loan_name'); ?></option>
                                    <?php
                                    $users = get_all_data("select * from user");
                                    foreach ($users as $user) {
                                        echo '<option value="' . $user['user_id'] . '">' . $user['user_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback" id="check_user"></div>
                            </div>

                            <div class="form-group abs-con mt-3">
                                <label for="copy_number" class="blue-text"><?= lang('copy_number'); ?></label>
                                <select required class="form-control" name="copy_number" id="copy_number" onchange="checkSelectData('part','#copy_number','#check_part')">
                                    <option value="" disabled selected><?= lang('select_copy_number'); ?></option>
                                    <?php
                                    $parts = get_all_data(SELECT_PART, [$book_id]);
                                    foreach ($parts as $part) {
                                        echo '<option value="' . $part['copy_no'] . '">' . $part['copy_no'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback" id="check_part"></div>
                            </div>

                            <div class="form-group abs-con mt-3">
                                <label for="loan_return_date" class="blue-text"><?= lang('loan_return_date'); ?></label>
                                <input type="date" class="form-control" id="loan_return_date" name="loan_return_date" onchange="checkDate('now','#loan_return_date')" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="modal-footer  mt-4  justify-content-evenly">
                                <button class="btn blue-btn add rounded-3 fw-bold" type="submit" id="submit"><?php echo lang('loan') ?></button>
                                <button class="btn blue-btn cancel me-auto rounded-3 fw-bold" type="button" onclick="window.location.href='book_details.php?book_id=<?php echo $book_id ?>'"><?php echo lang('cancel') ?></button>
                            </div>

                        </form>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $tmpl . 'footer.php'; ?>