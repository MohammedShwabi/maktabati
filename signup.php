<?php
require_once 'init.php';
$pageTitle = lang("signup_title");
require_once $tmpl . 'header.php';
?>

<!-- start signup code  -->
<?php
//redirect if session already exist
if (isset($_SESSION['UserEmail'])) {
    redirect_user("", 0, "index.php");
}
//check if user coming from HTTP post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //check if variables is not empty
    if (empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["username"]) || !isset($_POST['checkbox'])) {
        $result =lang('signup_empty_field');
    } else {
        //get coming element from HTTP post Request
        $usern      = $_POST['username'];
        $usere      = $_POST['email'];
        $password   = $_POST['password'];

        //filter user name from any script 
        $userName = filter_var(strip_tags($usern),FILTER_SANITIZE_SPECIAL_CHARS);
        // filter email from any script 
        $userEmail = filter_var(strip_tags($usere), FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
        if (!empty($userName) && !empty($userEmail) && !empty($password)) {
            //hash password using Hashing in php ;
            $hashPass = password_hash($password , PASSWORD_ARGON2I);

            // check if the email already exist in db or not
            $results = get_data(USER_CHECK, [$userEmail]);
            if ($results != 0) {
                $result =  lang('error_email_exist') ;
            } else {
                // Insert  new User 
                $results = get_data(INSERT_USER, [$userName, $userEmail, $hashPass]);
                //after user sign up redirect him to index.php
                $_SESSION['UserEmail'] = $userEmail; //Register Session Name
                redirect_user("", 0, "index.php");
            }
        }
    }
}

?>
<!-- end signup code  -->

<div class="mt-5 signup-page d-flex align-items-center justify-content-center">
    <div class="signup-form ">
        <div class="container my-5 shadow d-flex align-items-center justify-content-center">
            <div class="row bg-white">
                <div class="col text-primary p-3">
                    <h4 class="text-uppercase text-center  blue-text m-3 mb-2"><?php echo lang('create_new_account'); ?></h4>
                    <hr class="mb-2" />
                    <!-- print error msg -->
                    <?php if (!empty($result)) {
                            echo  '<div class="text-center alert alert-danger alert-dismissible fade show error-alert" role="alert" >
                            <span>' . $result . '</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button></div>';
                        } ?>
                    <main class="all-form m-4">
                        <form name="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <div class="form-group abs-con">
                                <label for="username" class="form-label"><?= lang('username'); ?></label>
                                <span class="start-icon"><i class="fa-duotone fa-user"></i></span>
                                <input type="text" class="form-control f-username" id="username" name="username" placeholder="<?php echo lang('username_placeholder') ?>" required autofocus>
                            </div>
                            <div class="form-group abs-con">
                                <label for="username" class="form-label"><?= lang('email'); ?></label>
                                <span class="start-icon"><i class="fa-duotone fa-envelope"></i></span>
                                <input type="text" class="form-control" id="email"  name="email" onkeyup="checkSignupField('email', '#email', '#check_email')" placeholder="<?php echo lang('email_placeholder') ?>" required >
                                <span class="invalid-feedback" id="check_email"></span>
                            </div>

                            <div class="form-group abs-con mb-2">
                                <label for="password" class="form-label"><?= lang('password'); ?></label>
                                <span class="start-icon"><i class="fa-duotone fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" onkeyup="checkSignupField('password', '#password', '#check_password')"  name="password" placeholder="<?php echo lang('password_placeholder') ?>" required>
                                <span class="eyeAdmin" onclick="hidePassword('password')">
                                    <i class="fa-duotone fa-eye" id="hide"></i>
                                </span>
                                <span class="invalid-feedback" id="check_password"></span>
                            </div>

                            <div class="form-group abs-con">
                                <input class="form-check-input" name="checkbox" type="checkbox" value="check" id="checkbox" required />
                                <label class="form-check-label" for="checkbox"><?php echo lang('agree') ?> <a href="#"><?php echo lang('condition_and_rule') ?></a></label>
                                <span class="invalid-feedback" id="check_agree" style="display:none ;">not agree</span>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn blue-btn rounded-3 mt-2 mb-2 fw-bold" type="submit" id="submit" disabled><?php echo lang('create_account') ?></button>
                            </div>

                            <!-- Register buttons -->
                            <div class="text-center mb-3 ">
                                <p><span class="form-label" ><?php echo lang('hav_account') ?></span><a class="fw-bold" href="login.php"><?php echo lang('login') ?></a></p>
                            </div>
                        </form>
                    </main>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include $tmpl . 'footer.php'; ?>