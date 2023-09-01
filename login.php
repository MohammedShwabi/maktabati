<?php
require_once 'init.php';
$pageTitle = lang("login_title");
require_once $tmpl . 'header.php';
?>
<!-- start login code  -->
<?php
//redirect if session already exist
if (isset($_SESSION['UserEmail'])) {
    redirect_user("", 0, "index.php");
}
//check if user coming from HTTP post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // redirect_user();
    //check if variables is not empty
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        $result =  lang('login_empty');
        // return;
    } else {
        //get coming element from HTTP post Request
        $user = $_POST['email'];
        $password = $_POST['password'];
        //filter email from any script 
        $userEmail = filter_var(strip_tags($user), FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
        // chick from data after filter
        if (!empty($userEmail) && !empty($password)) {
            //Check if the user Exist in Database 
            $results = get_data(USER_CHECK_LOGIN, [$userEmail]);
            if ($results != 0) {
                // print_r($row);
                $hashPass = password_verify($password, $results['user_password']);
                if ($hashPass) {
                    $_SESSION['UserEmail'] = $userEmail; //Register Session Name
                    //    $_SESSION['ID']=$row['user_id'];//Register Session ID
                    redirect_user("", 0, "index.php");
                } else {
                    $result = lang('login_error');
                }
            } else {
                $result = lang('login_error');
            }
        } else {
            $result = lang('login_error');
        }
    }
}

?>
<!-- end login code  -->

<div class="mt-5 login-page d-flex align-items-center justify-content-center">
    <div class="login-form ">
        <div class="container my-5 shadow d-flex align-items-center justify-content-center">
            <div class="row bg-white">
                <div class="col p-3">
                    <h4 class="text-uppercase text-center blue-text m-3 mb-2"><?php echo lang('login'); ?></h4>
                    <hr class="mb-2" />
                    <!-- print error message   -->

                        <?php if (!empty($result)) {
                            echo  '<div class="text-center alert alert-danger alert-dismissible fade show error-alert" role="alert" >
                            <span>' . $result . '</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button></div>';
                        } ?>
                    <main class="all-form m-4">
                        <form name="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <div class="form-group abs-con">
                                <label for="username" class="form-label"><?= lang('email'); ?></label>
                                <span class="start-icon"><i class="fa-duotone fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo lang('email_placeholder') ?>" required autofocus>
                            </div>
                            <small id="check_username"></small>
                            <div class="form-group abs-con mb-2">
                                <label for="password" class="form-label title-texts"><?= lang('password'); ?></label>
                                <span class="start-icon"><i class="fa-duotone fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo lang('password_placeholder') ?>" required>

                                <span class="eyeAdmin" onclick="hidePassword('password')">
                                    <i class="fa-duotone fa-eye" id="hide"></i>
                                </span>

                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn blue-btn  rounded-3 mt-2 mb-2 fw-bold" type="submit"><?php echo lang('login') ?></button>
                            </div>

                            <!-- Register buttons -->
                            <div class="text-center mb-3 ">
                                <p><span class="form-label"><?php echo lang('no_account') ?></span><a class=" fw-bold" href="signup.php"><?php echo lang('new_account') ?></a></p>
                            </div>
                        </form>
                    </main>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include $tmpl . 'footer.php'; ?>