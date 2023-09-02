<?php
//to redirect user 
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

// to redirect user to previous page
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
