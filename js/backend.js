/* the custom jQuery code here */

// initialize var
var limit = 20;
var start = 0;
var load_status = 'inactive';
var currentFocus = -1;


//login.php  && signup.php
/**
 * Toggles the visibility of a password input field and updates an associated icon.
 *
 * @param {string} target - The ID of the password input element.
 */
function hidePassword(target) {
    var input = document.getElementById(target);

    if (input.type === 'password') {
        input.type = "text";
        $("#hide").addClass("fa-eye-slash").removeClass("fa-eye");
    } else {
        input.type = "password";
        $("#hide").addClass("fa-eye").removeClass("fa-eye-slash");
    }
}

/**
 * Checks the search form for input values and updates form action accordingly.
 */
function searchFormCheck() {
    var count = 0;
    //check from all input type of it is text
    $('#search_form input[type=text]').each(function () {
        if ($(this).val()) {
            count++;
            return false;
        }
    });
    if (count > 0) {
        $('#search_form').attr('action', 'search_result.php');
        $('#submit').attr('type', 'submit');
    } else {
        // check from the input of type date
        if ($('#search_publish_date').val()) {
            $('#search_form').attr('action', 'search_result.php');
            $('#submit').attr('type', 'submit');
        } else {
            window.location.href = "#advance_title";
            $('#all_field_empty').fadeIn(2500);
        }
    }
}

/**
 * Loads content from a specified URL into a container using AJAX.
 *
 * @param {string} url - The URL to fetch content from.
 * @param {string} container - The ID of the HTML element where the content will be loaded.
 * @param {Object} options - Additional options for the AJAX request.
 */
// { limit: limit, start: start, type: type , section_id: section_id}
function loadSection(url, container, options) {
    $.ajax({
        type: 'POST',
        url: url,
        data: Object.assign({ limit: limit, start: start }, options),
        cache: false,
        beforeSend: function () {
            // to show loading icon
            $('.load_more_scroll_loader').removeClass("d-none");
        },
        success: function (response) {
            // to add the response to the page
            $("#" + container).append(response);
            // to hide loading icon
            $('.load_more_scroll_loader').addClass("d-none");

            // check if there is more categories on not
            load_status = response == '' ? "active" : "inactive";

            // display message if no data
            if ((response == '') && (start == 0)) {
                errorMessage(container, options.section);
            }
        },
        error: function (response) {
            // do some thing
        }
    });
}

/**
 * Displays an error message in a specified container.
 *
 * @param {string} container - The ID of the HTML element where the error message will be displayed.
 * @param {string|number} param - The parameter indicating the type of error message to display.
 */
function errorMessage(container, param) {
    message = '<div class="container text-center" ><h1 class="mb-0 pb-0 pt-5 text-muted">';
    message += isNaN(param) ? 'لا يوجد نتائج فيما تبحث عنه !!' : 'لا يوجد كتب !!';
    message += '</h1><img src="img/No_data.svg" class="img-fluid w-75 w" /></div>';
    $("#" + container).html(message);
}

/**
 * Handles scrolling to trigger loading more content when the user is near the bottom of the page.
 *
 * @param {string} url - The URL to fetch more content from.
 * @param {string} container - The ID of the HTML element where the new content will be appended.
 * @param {Object} option - Additional options for loading more content.
 */
function scrollLoader(url, container, option) {

    const { scrollTop, clientHeight, scrollHeight } = document.documentElement;

    var isEndPage = scrollTop + clientHeight >= scrollHeight - 200;

    // Check if the user is near the bottom of the page
    if (isEndPage && load_status == 'inactive') {

        // if ($(window).scrollTop() >= $(document).height() - $(window).height() && load_status == 'inactive') {
        load_status = 'active';
        start = start + limit;
        loadSection(url, container, option);
    }
}

/**
 * Generates a delete confirmation pop-up with the appropriate URL and parameters.
 *
 * @param {string} url - The base URL for the delete action.
 * @param {Object} options - Additional options for the delete action.
 *   @property {string} id - The ID of the item to be deleted.
 *   @property {string} img - (Optional) The URL of an image associated with the item.
 */

// {"id":"$cat_id", "img":"$auth_img"}
function deletePop(url, options) {
    // get the id
    delete_id = "id=" + options['id'];

    // get the img if exist
    if (options['img']) {
        delete_img = "&img=" + options['img'];
        $("#delete_btn").attr("onclick", "window.location.href='" + url + "?" + delete_id + delete_img + "'");
    } else {
        $("#delete_btn").attr("onclick", "window.location.href='" + url + "?" + delete_id + "'");
    }
}

/**
* Performs a search operation and updates the search results on the page.
*
* @param {string} searchText - The text to search for.
* @param {string} url - The URL for the search operation.
* @param {Object} options - Additional options for the search operation.
*   @property {string} key - (Optional) Additional key or identifier for the search.
*/

function search(searchText, url, options) {

    if (searchText != '') {
        $.ajax({
            type: 'POST',
            url: url,
            data: options,
            cache: false,
            beforeSend: function () {
                // to show loading icon
                // old icon : // fa-circle-notch
                $("#search_icon").addClass('fa-spinner-third fa-spin').removeClass('fa-magnifying-glass');
            },
            success: function (response) {
                // to add the response to the page
                $('#result_list').html(response);
                // to hide loading icon
                $("#search_icon").addClass('fa-magnifying-glass').removeClass('fa-spinner-third fa-spin');
                // to reset the current focus suggestion item
                currentFocus = -1;
            },
            error: function (response) {
                // handel error
            }

        });
    } else {
        $('#result_list').html('');
    }

}

/**
 * Decrement a timer and redirect to a specified URL when the timer reaches zero.
 *
 * @param {string} url - The URL to redirect to when the timer reaches zero.
 */
function myTimer(url) {
    --second;
    $('#second').text(second);
    if (second === 0) {
        clearInterval(redirectTime);
        if (url) window.location.href = url;
    }
}

/**
 * Document ready event handler for the entire page.
 */
$(document).ready(function () {
    // make the width of search result equal to search input
    $('#result_list').width($('.search').width());

    // hide search result
    $("#search_txt").blur(function () {
        setTimeout(function () {
            $('#result_list').hide();
        }, 200);
    });

    // show search result
    $("#search_txt").focus(function () {
        $('#result_list').show();
    });

    // for click rating star style
    $(".rate").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    });

    // to navigate search suggestion using arrow key
    $("#search_txt").keydown(function (e) {
        switch (e.key) {
            case "ArrowDown":
                Navigate(1);
                break;
            case "ArrowUp":
                Navigate(-1);
                break;
            case "Enter":
                if ($("#result_list .active").length) {
                    e.preventDefault();
                    $("#result_list .active")[0].click();
                }
                break;
            default:
                return; // exit this handler for other keys
        }
    });

    // for suggestion navigation
    var Navigate = function (diff) {
        currentFocus += diff;
        var listItems = $(".search-item");

        if (currentFocus >= listItems.length) {
            currentFocus = 0;
        }
        if (currentFocus < 0) {
            currentFocus = listItems.length - 1;
        }

        // not eq(index) start index form 0
        listItems.removeClass("active").eq(currentFocus).addClass("active");
    };

    // to fill input with value when click edit for category popup
    $(document).on("click", ".cat-edit", function (e) {

        // get cat name and id
        var cat_id = $(this).attr("cat-data");
        var cat_name = $(this).parents("ul").prev().attr("title");

        // set cat name and cat id to input filed in popup
        $("#cat_id").val(cat_id);
        $("#cat_name").val(cat_name);

        // for edit event
        $("#cat_name").attr("onkeyup", "checkName('check_cat.php', {cat_name: $(this).val()}, $(this), '" + cat_name + "')");

        // change popup title and save btn
        $("#add_category_label").text("تعديل قسم");
        $('#add_category input:submit').val("تعديل");
    });

    // to rest category edit filed when click (x) btn
    $("#add_category").on('hidden.bs.modal', function () {
        $("#catForm").get(0).reset();
        // change popup title and save btn
        $("#add_category_label").text("إضافة قسم");
        $('#add_category input:submit').val("إضافة");
        // enable disable btn
        $("#catForm input:submit").prop("disabled", false);

        // for remove edit event
        $("#cat_name").attr("onkeyup", "checkName('check_cat.php', {cat_name: $(this).val()}, $(this))");
    });

    // to rest all filed including hidden filed 
    $('#add_category').on('reset', function () {
        $("#add_category #cat_id").val('');
        $("#add_category #cat_name").removeClass("is-invalid");
    });

    //to check form checkbooks in signup 
    $("#checkbox").change(function () {
        if (this.checked) {
            $('#check_agree').text("");
        } else {
            $('#check_agree').text("not agree");
        }
    });

    // to check the error message 
    // create a MutationObserver instance
    var observer = new MutationObserver((mutation) => {

        var hasFeedback;
        // check if any invalid-feedback is visible
        $(".invalid-feedback:visible").each(function () {
            if ($(this).text().trim().length !== 0) {
                hasFeedback = true;
                return false; // exit the loop
            }
        });

        // enable or disable the submit button based on the count
        $('#submit').prop('disabled', hasFeedback);
    });
    
    // configuration of the observer
    var config = {
        childList: true, // observe child node changes
        subtree: true // observe descendant node changes
    }

    // Select the document element to observe
    var target = document.querySelector('form');

    // pass in the target node, as well as the observer options
    observer.observe(target, config);

    //to read more in book_details page
    $('.more-btn').click(function () {
        $('.more-btn').hide();
        $('.more-text').show();
        $('.short-text').css('display', 'none');
        $('.less-btn').show();
    });
    $('.less-btn').click(function () {
        $('.short-text').show();
        $('.more-text').hide();
        $('.more-btn').show();
        $('.less-btn').hide();
    });

    //add book page
    //to show series detail
    $("#checkbox_has_series").change(function () {
        if (this.checked) {
            $('.series-group').show(500);
        } else {
            $('.series-group').hide(500);
        }
    });
    //to show  attachment details

    $("#checkbox_has_attachment").change(function () {
        if (this.checked) {
            $('.attachment-group').show(500);
        } else {
            $('.attachment-group').hide(500);
        }
    });
    $('input[type=radio][name=chose_part]').change(function () {

        if (this.value == 'one') {
            $('.one-part-container').show(500);
            $('.multi-part-container').hide(500);
            $('#part_no').val("");
            $('#more_part').html("");
            $('.one-part-container input').each(function () {
                $(this).prop("disabled", false);
                $(this).prop("required", true);

            });
        } else {
            $('.multi-part-container').show(500);
            $('.one-part-container').hide(500);
            $('.one-part-container input').each(function () {
                $(this).prop("disabled", true);
                $(this).prop("required", false);
            });
        }
    });


});
// ************** upload page *********************

/**
 * Displays an alert message with the specified content and alert style.
 *
 * @param {string} message - The message content to display in the alert.
 * @param {string} alert - The style of the alert (e.g., 'success', 'warning', 'danger').
 */
function show_alert(message, alert) {
    $('#alert_wrapper').html(
        '<div class="alert alert-' + alert + ' alert-dismissible fade show" role="alert" >' +
        '<span>' + message + '</span>' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button></div>'
    );
};

/**
 * Validates the chosen file for upload, checking its file extension.
 *
 * @param {string} bookName - The name of the chosen file, including its extension.
 * @returns {boolean} Returns true if the file extension is allowed, otherwise returns false.
 */
function validate_file(bookName) {
    // allowed book extension
    var allowExtension = ['pdf', 'doc', 'docx'];
    // to get the book extension
    var bookExtension = bookName.split('.').pop().toLowerCase();

    //to check if the chosen file is in the allowed list or not
    if (!(allowExtension.includes(bookExtension))) {
        // display error message and disable upload button and rest input
        show_alert("عذراً, غير مسموح برفع ملفات عدا :" + allowExtension.join(', '), "warning");
        $("#upload_btn").prop("disabled", true);
        $('#fileToUpload').val('');

        // to exit from the method
        return false;
    }
    // remove error message and enable submit btn
    $("#alert_wrapper").html('');
    $("#upload_btn").prop("disabled", false);
    // go to check if file exist in server
    checkBookExist(bookName);
}

/**
 * Checks if a book with the given name exists on the server.
 *
 * @param {string} bookName - The name of the book file to check for existence.
 */
function checkBookExist(bookName) {
    $.ajax({
        type: "HEAD",
        url: "upload/pdf/" + bookName,
        error: function () {
            //file not exists
            $("#alert_wrapper").html('');
            $("#upload_btn").prop("disabled", false);
        },
        success: function () {
            //file exists
            show_alert("الكتاب موجود مسبقاً..!", "danger");
            $("#upload_btn").prop("disabled", true);
            $('#fileToUpload').val('');
        }
    });
}

/**
 * Checks if the selected file exists on the server and validates its file extension.
 *
 * @param {HTMLInputElement} filed - The input element for file selection.
 * @param {string} type - The type of file being checked (e.g., 'pdf', 'image').
 * @returns {boolean} Returns true if the file extension is allowed and the file doesn't exist on the server, otherwise returns false.
 */
function checkFileExist(filed, type) {
    // allowed book extension
    var allowExtension = ['png', 'jpg', 'jpeg'];
    var fileName = filed.files[0].name;
    // to get the book extension
    var fileExtension = fileName.split('.').pop().toLowerCase();

    //to check if the chosen file is in the allowed list or not
    if (!(allowExtension.includes(fileExtension))) {
        $(filed).addClass("is-invalid").next().text(`عذراً, غير مسموح برفع ملفات عدا : ${allowExtension.join(', ')}`);
        $("input:submit").prop("disabled", true);
        $(filed).val('');
        // to exit from the method
        return false;
    }

    $.ajax({
        type: "HEAD",
        url: `upload/${type}/${fileName}`,
        error: function () {
            //file not exists
            $(filed).removeClass("is-invalid").next().text("");
            $("input:submit").prop("disabled", false);
            photoPreview(filed.files[0]);
        },
        success: function () {
            //file exists
            $(filed).addClass("is-invalid").next().text("الصورة موجودة مسبقاً..!");
            $("input:submit").prop("disabled", true);
            $(filed).val('');
        }
    });
}

/**
 * Displays a preview of a selected photo in an HTML element.
 *
 * @param {File} file - The File object representing the selected photo.
 */
function photoPreview(file) {

    if (file) {
        const reader = new FileReader();
        reader.onload = () => {
            $("#profile_img").attr("src", reader.result.toString());
        };
        reader.readAsDataURL(file);
    }
}

/**
 * Handles the upload process for a book file.
 * Validates user inputs, prepares and sends the file data to the server, and displays progress and status messages.
 */
function upload() {
    //check if book id have value and it is a number
    if (!$('#book_id').val() || !$.isNumeric($('#book_id').val())) {
        show_alert("يجب إختيار كتاب", "warning");
        return;
    }
    //check if input have value
    if (!$('#fileToUpload').val()) {
        show_alert("يجب اختيار ملف لرفعة..!", "warning");
        $('#fileToUpload').focus();
        return;
    }
    //hide alert message if it is already exist
    $('#alert_wrapper').html("");

    //to make new form data to hold data to send it
    var formData = new FormData();
    formData.append('book_id', $('#book_id').val());
    formData.append("upload", $('#fileToUpload')[0].files[0]);

    var xhr_request = $.ajax({
        type: 'POST',
        url: 'upload_book.php',
        data: formData,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        xhr: function () {
            var my_xhr = $.ajaxSettings.xhr();
            if (my_xhr) {
                my_xhr.upload.addEventListener("progress", function (event) {
                    Progress(event.loaded, event.total);
                });
            }
            return my_xhr;
        },
        beforeSend: beforeSend(),
        success: function (response) {
            reset_input();
            if (response.status == 1) {
                show_alert(response.message, "success");
                setTimeout(function () { window.history.back() }, 5000);
            } else {
                show_alert(response.message, "danger");
            }
        },
        error: function (response) {
            reset_input();
            show_alert("خطأ في رفع الملف", "danger");
            console.log(response);
        }

    });

    //cancel the upload when click btn
    $("#cancel_btn").click(
        function () {
            xhr_request.abort();
        }
    )
}

/**
 * Updates the progress bar and status label during the file upload process.
 *
 * @param {number} current - The current amount of data uploaded.
 * @param {number} total - The total amount of data to be uploaded.
 */
function Progress(current, total) {
    // get the total uploaded percentage
    var percent = ((current / total) * 100).toFixed(0) + "%";

    $("#progress_contain").removeClass("d-none");
    //to change the status of progress bar and its label
    $("#progress_load").attr("style", "width:" + percent);
    $("#progress_status").text(percent);
    if (percent == "100%") {
        $("#progress_status").text("Processing");
    }
}

/**
 * Performs pre-upload tasks such as disabling UI elements and showing loading indicators.
 */
function beforeSend() {
    //to disable choose file, and sponsor list:: while uploading file
    $("#fileToUpload").prop("disabled", true);
    //hide the upload btn 
    $('#upload_btn').addClass("d-none");
    //show the hidden element (loading and cancel btn ,progress bar)
    $('#loading_btn').removeClass("d-none");
    $('#cancel_btn').removeClass("d-none");
}

/**
 * Resets input elements and UI elements to their initial state after completing the file upload process.
 */
function reset_input() {
    $('#fileToUpload').val('');
    //to enable choose file
    $("#fileToUpload").prop("disabled", false);

    //hide the same btn 
    $('#cancel_btn').addClass("d-none");
    $('#loading_btn').addClass("d-none");
    $('#progress_contain').addClass("d-none");
    //show upload btn again
    $('#upload_btn').removeClass("d-none");
    $('#progress_load').attr("style", "width: 0%")
}

/**
 * Performs validation on a selected option in a dropdown (select) element and displays an error message if necessary.
 *
 * @param {string} $select - The selector for the dropdown (select) element to validate against.
 * @param {string} $filed - The selector for the field containing the selected option.
 * @param {string} $div - The selector for the HTML element where the error message will be displayed.
 */
function checkSelectData($select, $filed, $div) {
    //get id of select
    var class_name = $filed;
    //get value of selected option
    var $filed = $($filed + " :selected").val();
    $.ajax({
        type: "POST",
        url: "field_validation.php",
        data: { filed: $filed, select: $select },
        dataType: 'json',
        success: function (response) {
            if (response.status == 0) {
                $(class_name).removeClass("is-invalid");
                $($div).text('');
            } else {
                $(class_name).addClass("is-invalid").removeClass("is-valid");
                $($div).text(response.message);
            }
        }
    }).done(function (response, textStatus, jqXHR) {
        // checkLoanError();
    });
}

/**
 * Performs validation on an input field and displays an error message if necessary.
 *
 * @param {string} $select - The selector for the select element related to the field.
 * @param {string} $filed - The selector for the input field to validate.
 * @param {string} $div - The selector for the HTML element where the error message will be displayed.
 * @param {string} [oldAuthorName=''] - (Optional) The old value of the input field, used for comparison to determine if validation is necessary.
 */
function checkField($select, $filed, $div, oldAuthorName = '') {
    //get id of input
    var class_name = $filed;
    var $filed = $($filed).val().trim();
    var condition = (oldAuthorName) ? ($filed != oldAuthorName) : $filed;
    if (condition) {
        $.ajax({
            url: "field_validation.php",
            data: { filed: $filed, select: $select },
            type: "POST",
            dataType: 'json',
            success: function (response) {
                if (response.status == 0) {
                    $(class_name).removeClass("is-invalid");
                    $($div).text('');
                } else {
                    $(class_name).addClass("is-invalid").removeClass("is-valid");
                    $($div).text(response.message);
                }
            }
        });
    }
}

/**
 * Performs validation on a signup input field and displays an error message if necessary.
 *
 * @param {string} $select - The selector for the select element related to the field.
 * @param {string} $filed - The selector for the signup input field to validate.
 * @param {string} $div - The selector for the HTML element where the error message will be displayed.
 */
function checkSignupField($select, $filed, $div) {
    //get id of input
    var class_name = $filed;
    var $filed = $($filed).val().trim();
    $.ajax({
        url: "signup_validation.php",
        data: { filed: $filed, select: $select },
        type: "POST",
        dataType: 'json',
        success: function (response) {
            if (response.status == 0) {
                $(class_name).removeClass("is-invalid");
                $($div).text('');
            } else {
                $(class_name).addClass("is-invalid").removeClass("is-valid");
                $($div).text(response.message);
            }
        }
    });
}

/**
 * Function to check date conditions and display error messages.
 *
 * @param {string} first_date - The type of date comparison ('now', 'just_one', or custom date).
 * @param {string} last_date - The end date for comparison.
 */
function checkDate(first_date, last_date) {
    // Initialize date and error message variables
    var start_date = '';
    var end_date = new Date($(last_date).val());
    var error_msg = '';

    // Check if first_date is 'now' (current date)
    if (first_date === 'now') {
        start_date = new Date();
        // Check if end_date is before or equal to start_date
        if (end_date.getTime() <= start_date.getTime()) {
            error_msg = 'لايمكن ان يكون تاريخ الإعادة قبل او يساوي تاريخ الإعارة';
        }
    }
    // Check if first_date is 'just_one' (current date)
    else if (first_date === 'just_one') {
        start_date = new Date();
        // Check if end_date is after or equal to start_date
        if (end_date.getTime() >= start_date.getTime()) {
            error_msg = 'لا يمكن ان يكون تاريخ التأسيس بعد او يساوي تاريخ اليوم';
        }
    }
    // Check if custom dates are provided
    else if ($(first_date).val() && $(last_date).val()) {
        const currentDate = new Date();
        const fiveYearsAgo = new Date(currentDate);
        fiveYearsAgo.setFullYear(currentDate.getFullYear() - 5);

        start_date = new Date($(first_date).val());

        // Check if end_date is before or equal to start_date
        if (end_date <= start_date) {
            error_msg = 'لايمكن ان يكون تاريخ الوفاه قبل او يساوي تاريخ الميلاد';
        }
        // Check if start_date is more than 5 years ago from today
        else if (start_date > fiveYearsAgo) {
            error_msg = 'تاريخ الميلاد يجب أن يكون قبل 5 سنوات من اليوم';
        }
    }

    // Show and hide error message based on conditions
    if (error_msg) {
        $(last_date).addClass("is-invalid").next().text(error_msg);
    } else {
        $(last_date).removeClass("is-invalid").next().text('');
    }
}


/**
 * Performs validation on a name input field and displays an error message if necessary.
 *
 * @param {string} url - The URL to send the validation request to.
 * @param {Object} param - The parameters to include in the validation request.
 * @param {jQuery} filed - The jQuery selector for the input field to validate.
 * @param {string} [oldAuthorName=''] - (Optional) The old value of the input field, used for comparison to determine if validation is necessary.
 */
function checkName(url, param, filed, oldAuthorName) {

    var condition = (oldAuthorName) ? (filed.val().trim() != oldAuthorName) : filed.val();

    if (condition) {
        $.ajax({
            type: "POST",
            url: url,
            data: param,
            dataType: 'json',
            success: function (response) {
                if (response.status == 0) {
                    filed.addClass("is-invalid").next().text(response.message);
                } else {
                    filed.removeClass("is-invalid").next().text('');
                }
            }
        });
    } else {
        filed.removeClass("is-invalid").next().text('');
    }
}

/**
 * Toggles the visibility of content and updates the text of a "Read More" button.
 */
function readMore() {
    $('.more-btn').slideToggle();
    $('.more-btn').html($('.more-btn').html == 'readMore' ? 'readLess' : 'readMore');
}

/**
 * Performs auto-completion and validation on an input field with a provided datalist.
 *
 * @param {string} $table - The name of the database table to search for suggestions.
 * @param {string} $filed - The name of the field in the database to search for suggestions.
 * @param {string} $value - The selector for the input field to autocomplete and validate.
 * @param {string} $datalist - The selector for the datalist element associated with the input field.
 * @param {string} $error - The selector for the HTML element where the error message will be displayed.
 */
function autoCompleteField($table, $filed, $value, $datalist, $error) {
    //get id of input
    var class_name = $value;
    var $value = $($value).val().trim();
    if ($value == '') {
        $(class_name).removeClass("is-invalid");
        $($datalist).html('');
        $($error).text("");
    }
    $.ajax({
        url: "search_auto_complete.php",
        data: { table: $table, filed: $filed, value: $value, datalist: $datalist },
        type: "POST",
        dataType: 'json',
        success: function (response) {
            if (response.status == 0) {
                $(class_name).addClass("is-invalid").removeClass("is-valid");
                $($error).text(response.message);
                $($datalist).html('');
            } else {
                $(class_name).removeClass("is-invalid");
                $($datalist).html(response.message);
                $($error).text("");
            }
        },

    });
}

/**
 * Generates a dynamic set of input fields for book parts based on the specified number of parts.
 * Validates the number of parts and displays error messages if necessary.
 */
function howParts() {
    var part_no = $('#part_no').val();
    if (part_no == '') {
        $("#part_no").removeClass("is-invalid ");
        $("#no_part_check").text("");
        $("#more_part").html("");
    } else if (part_no > 20) {
        $("#part_no").addClass("is-invalid").removeClass("is-valid");
        $("#no_part_check").text("لايمكن ان يكون عدد الأجزاء اكبر من 20");
        $("#more_part").html("");
    } else if (part_no < 2) {
        $("#part_no").addClass("is-invalid").removeClass("is-valid");
        $("#no_part_check").text("لايمكن ان يكون عدد الأجزاء اصغر من 2");
        $("#more_part").html("");
    } else if (part_no >= 2 && part_no <= 20) {
        $("#part_no").removeClass("is-invalid ");
        $("#no_part_check").text("");
        $("#more_part").html("");
        for (let i = 0; i < part_no; i++) {
            $input = $(' <div class="accordion accordion-flush" id="accordionFlushExample">' +
                '<div class="accordion-item mt-3">' +
                '<h2 class="accordion-header" id="flush-heading' + (i + 1) + '">' +
                '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse' + (i + 1) + '" aria-expanded="false" aria-controls="flush-collapse' + (i + 1) + '">' +
                '<p class="blue-text">   بيانات الجزء  ' + (i + 1) + '</p>' +
                ' </button>' +
                '</h2>' +
                '<div id="flush-collapse' + (i + 1) + '" class="accordion-collapse collapse" aria-labelledby="flush-heading' + (i + 1) + '" data-bs-parent="#accordionFlushExample">' +
                '<div class="accordion-body">' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="mt-3 text-end">' +
                '<label for="edition_no" class="blue-text mb-2 me-4">رقم الطبعة</label>' +
                '<input class="form-control" type="text" id="edition_no" name="edition_no[]" placeholder="1" />' +
                '<div class="invalid-feedback"></div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="mt-3 text-end">' +
                '<label for="edition_desc" class="blue-text mb-2 me-4">وصف الظبعة</label>' +
                '<input class="form-control" type="text" id="edition_desc" name="edition_desc[]" placeholder="1"  />' +
                '<div class="invalid-feedback"></div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="mt-3 text-end">' +
                ' <label for="publish_date" class="blue-text mb-2 me-4">تاريخ النشر</label>' +
                '<input class="form-control" type="month" id="publish_date" name="publish_date[]" />' +
                '<div class="invalid-feedback"></div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="mt-3 text-end">' +
                '<label for="pages_no" class="blue-text mb-2 me-4">عدد الصفحات</label>' +
                '<input class="form-control" type="text" id="pages_no" name="pages_no[]" placeholder="512" ' +
                '<div class="invalid-feedback"></div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<input  class="form-control" type="hidden" id="part_number" name="part_number[]" value="' + (i + 1) + '"  />' +
                '<div class="col-md-6">' +
                '<div class="mt-3 text-end">' +
                '<label for="price" class="blue-text mb-2 me-4">السعر</label>' +
                '<input  class="form-control" type="text" id="price" name="price[]" placeholder="20 $" ' +
                '<div class="invalid-feedback"></div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="mt-3 text-end">' +
                '<label for="no_of_copy" class="blue-text mb-2 me-4">عدد النسخ</label>' +
                '<input class="form-control" type="text" id="no_of_copy" name="no_of_copy[]" placeholder="20"/>' +
                '<div class="invalid-feedback"></div>' +
                '</div> ' +
                '</div > ' +
                '</div>');
            $input.fadeIn(1000).appendTo('#more_part');
        }
        //here to add dynamic inputs
    } else {
        $("#part_no").addClass("is-invalid").removeClass("is-valid");
        $("#no_part_check").text("فقط الارقام من 1 الى 20 ممكنه لعدد الاجزاء ");
        $("#more_part").html("");
    }
    // alert();
}

/**
 * Event listener for handling broken or missing images before they are unveiled (lazy-loaded).
 * It sets appropriate fallback images based on the context specified in the data-image-context attribute.
 *
 * @param {Event} e - The lazybeforeunveil event.
 */
document.addEventListener("lazybeforeunveil", function (e) {
    var img = e.target;

    // Determine the context of the image
    var imageContext = img.getAttribute("data-image-context");

    // Define different fallback images based on context
    var fallbackImagePaths = {
        book: "img/book_upload.svg",
        author: "upload/authors/auth_temp.svg",
    };

    // Handle the broken image here
    img.addEventListener("error", function () {
        // Set the src attribute to the appropriate fallback image path
        img.src = fallbackImagePaths[imageContext];
    });
});