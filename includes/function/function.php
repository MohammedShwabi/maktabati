<?php

/**
 * Redirects the user to a specified URL with an optional message and delay.
 *
 * This function redirects the user to the specified URL using the "refresh" header.
 * It can also display a message to the user with an optional delay before the redirection.
 *
 * @param string|null $message Optional message to display to the user (default is null).
 * @param int $second Optional delay in seconds before redirection (default is 0).
 * @param string $url Optional URL to redirect to (default is 'login.php').
 * @param string $alert Optional CSS class for the alert message (default is 'danger').
 * @return void
 */
function redirect_user($message = null, $second = 0, $url = 'login.php', $alert = "danger")
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

/**
 * Redirects the user to the previous page.
 *
 * This function checks the HTTP_REFERER header to determine the previous page
 * and redirects the user to that page using a header(Location) redirect.
 * If the HTTP_REFERER is not set, it redirects the user to "index.php" by default.
 *
 * @return void
 */
function go_back()
{
    $url = $_SERVER['HTTP_REFERER'] == NULL ? "index.php" : $_SERVER['HTTP_REFERER'];
    header("LOCATION: " . $url);
    exit();
}



/**
 * Truncate a given text to a specified number of words.
 *
 * @param string $text The input text to be truncated.
 * @param int $numWords The maximum number of words to retain in the truncated text.
 * @return string The truncated text with an optional ellipsis indicator.
 */
function truncateText($text, $numWords)
{
    // Split the input text into an array of words
    $words = explode(' ', $text);

    // Check if the word count exceeds the specified limit
    if (count($words) > $numWords) {
        // Truncate the text to the specified number of words
        $text = implode(' ', array_slice($words, 0, $numWords));

        // Optionally, add an ellipsis or any other indicator here
        $text .= ' ...';
    }

    // Return the truncated text
    return $text;
}

/**
 * Count the number of words in a given Arabic text based on space delimiters.
 *
 * @param string $text The input Arabic text in which to count words.
 * @return int The count of words in the input text.
 */
function countArabicWords($text)
{
    // Split the input text into words based on spaces
    $words = explode(' ', $text);

    // Return the count of words
    return count($words);
}
