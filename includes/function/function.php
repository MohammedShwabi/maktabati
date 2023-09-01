<?php
//to redirect user 
function redirect_user($message = null, $second = 0, $url = 'login.php' ,$alert = "danger")
{
    if (!empty($message)) {
        echo ("
        <div class='d-flex justify-content-center align-items-center w-100 h-100 position-absolute'>
            <div class='alert alert-$alert p-5'>
                <p class='fw-bold'>" . $message . "</p>
                <p class='fs-6'>" . lang('redirect_after') . "<span id='second'></span>" . lang('second') . "</p>
            </div>
        </div>
        <script>
            var second = $second;
            $('#second').html(second);
            var redirectTime = setInterval(myTimer, 1000);
        </script>
        ");
    }

    header("refresh:$second;url=$url");
    exit();
}

// to redirect user to previous page
function go_back()
{
    $url = $_SERVER['HTTP_REFERER'] == NULL ? "index.php" : $_SERVER['HTTP_REFERER'];
    header("LOCATION: " . $url);
    exit();
}
