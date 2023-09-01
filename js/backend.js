/* the custom jQuery code here */

// initialize var
var limit = 20;
var start = 0;
var load_status = 'inactive';
var currentFocus = -1;


//login.php  && signup.php
// for Hide and Show the password by using the EYE icons
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
//search form 
//to check if at least one filed is not empty
function searchFormCheck() {
    var count = 0;
    //check from all input type of it is text
    $('#search_form input[type=text]').each(function() {
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

// to get more section from database and append it to the page
// { limit: limit, start: start, type: type , section_id: section_id}
function loadSection(url, container, options) {
    $.ajax({
        type: 'POST',
        url: url,
        data: Object.assign({ limit: limit, start: start }, options),
        cache: false,
        beforeSend: function() {
            // to show loading icon
            $('.load_more_scroll_loader').removeClass("d-none");
        },
        success: function(response) {
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
        error: function(response) {
            // do some thing
        }
    });
}

// display message if no data found
function errorMessage(container, param) {
    message = '<div class="container text-center" ><h1 class="mb-0 pb-0 pt-5 text-muted">';
    message += isNaN(param) ? 'لا يوجد نتائج فيما تبحث عنه !!' : 'لا يوجد كتب !!';
    message += '</h1><img src="img/No_data.svg" class="img-fluid w-75 w" /></div>';
    $("#" + container).html(message);
}

// to load more data on scroll event only if there is more category in database
function scrollLoader(url, container, option) {

    if ($(window).scrollTop() >= $(document).height() - $(window).height() && load_status == 'inactive') {
        load_status = 'active';
        start = start + limit;
        loadSection(url, container, option);
    }
}

// for delete popup param
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

// to search for any thing : author , publisher and category
function search(searchText, url, options) {

    if (searchText != '') {
        $.ajax({
            type: 'POST',
            url: url,
            data: options,
            cache: false,
            beforeSend: function() {
                // to show loading icon
                // old icon : // fa-circle-notch
                $("#search_icon").addClass('fa-spinner-third fa-spin').removeClass('fa-magnifying-glass');
            },
            success: function(response) {
                // to add the response to the page
                $('#result_list').html(response);
                // to hide loading icon
                $("#search_icon").addClass('fa-magnifying-glass').removeClass('fa-spinner-third fa-spin');
                // to reset the current focus suggestion item
                currentFocus = -1;
            },
            error: function(response) {
                // handel error
            }

        });
    } else {
        $('#result_list').html('');
    }

}

// to display redirect seconds
function myTimer(url) {
    --second;
    $('#second').text(second);
    if (second === 0) {
        clearInterval(redirectTime);
        if (url) window.location.href = url;
    }
}

// to call function only when page loaded
$(document).ready(function() {
    // make the width of search result equal to search input
    $('#result_list').width($('.search').width());

    // hide search result
    $("#search_txt").blur(function() {
        setTimeout(function() {
            $('#result_list').hide();
        }, 200);
    });

    // show search result
    $("#search_txt").focus(function() {
        $('#result_list').show();
    });

    // for click rating star style
    $(".rate").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
    });

    // to navigate search suggestion using arrow key
    $("#search_txt").keydown(function(e) {
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
    var Navigate = function(diff) {
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
    $(document).on("click", ".cat-edit", function(e) {

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
    $("#add_category").on('hidden.bs.modal', function() {
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
    $('#add_category').on('reset', function() {
        $("#add_category #cat_id").val('');
        $("#add_category #cat_name").removeClass("is-invalid");
    });

    //to check form checkbooks in signup 
    $("#checkbox").change(function() {
        if (this.checked) {
            $('#check_agree').text("");
        } else {
            $('#check_agree').text("not agree");
        }
    });
    //to check the error message
    $(document).on("DOMSubtreeModified", ".invalid-feedback", function() {
        var count = 0;
        $(".invalid-feedback").each(function() {
            if ($(this).text().length != 0) {
                count++;
                return;
            }
        });
        if (count > 0) {
            $('#submit').prop('disabled', true);
        } else {
            $('#submit').prop('disabled', false);
        }
    });

    //to read more in book_details page
    $('.more-btn').click(function() {
        $('.more-btn').hide();
        $('.more-text').show();
        $('.dots').css('display', 'none');
        $('.less-btn').show();
    });
    $('.less-btn').click(function() {
        $('.more-text').hide();
        $('.more-btn').show();
        $('.dots').fadeIn();
        $('.less-btn').hide();
    });

    //add book page
    //to show series detail
    $("#checkbox_has_series").change(function() {
        if (this.checked) {
            $('.series-group').show(500);
        } else {
            $('.series-group').hide(500);
        }
    });
    //to show  attachment details

    $("#checkbox_has_attachment").change(function() {
        if (this.checked) {
            $('.attachment-group').show(500);
        } else {
            $('.attachment-group').hide(500);
        }
    });
    $('input[type=radio][name=chose_part]').change(function() {

        if (this.value == 'one') {
            $('.one-part-container').show(500);
            $('.multi-part-container').hide(500);
            $('#part_no').val("");
            $('#more_part').html("");
            $('.one-part-container input').each(function() {
                $(this).prop("disabled", false);
                $(this).prop("required", true);

            });
        } else {
            $('.multi-part-container').show(500);
            $('.one-part-container').hide(500);
            $('.one-part-container input').each(function() {
                $(this).prop("disabled", true);
                $(this).prop("required", false);
            });
        }
    });


});
// ************** upload page *********************

//show alert message function
function show_alert(message, alert) {
    $('#alert_wrapper').html(
        '<div class="alert alert-' + alert + ' alert-dismissible fade show" role="alert" >' +
        '<span>' + message + '</span>' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button></div>'
    );
};

// to check file extension
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

//check book if exist in server before uploading
function checkBookExist(bookName) {
    $.ajax({
        type: "HEAD",
        url: "upload/pdf/" + bookName,
        error: function() {
            //file not exists
            $("#alert_wrapper").html('');
            $("#upload_btn").prop("disabled", false);
        },
        success: function() {
            //file exists
            show_alert("الكتاب موجود مسبقاً..!", "danger");
            $("#upload_btn").prop("disabled", true);
            $('#fileToUpload').val('');
        }
    });
}

//check author photo if exist in server before uploading
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
        error: function() {
            //file not exists
            $(filed).removeClass("is-invalid").next().text("");
            $("input:submit").prop("disabled", false);
            photoPreview(filed.files[0]);
        },
        success: function() {
            //file exists
            $(filed).addClass("is-invalid").next().text("الصورة موجودة مسبقاً..!");
            $("input:submit").prop("disabled", true);
            $(filed).val('');
        }
    });
}

// to change display author or book profile image after select a photo
function photoPreview(file) {

    if (file) {
        const reader = new FileReader();
        reader.onload = () => {
            $("#profile_img").attr("src", reader.result.toString());
        };
        reader.readAsDataURL(file);
    }
}

// to upload the book
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
        xhr: function() {
            var my_xhr = $.ajaxSettings.xhr();
            if (my_xhr) {
                my_xhr.upload.addEventListener("progress", function(event) {
                    Progress(event.loaded, event.total);
                });
            }
            return my_xhr;
        },
        beforeSend: beforeSend(),
        success: function(response) {
            reset_input();
            if (response.status == 1) {
                show_alert(response.message, "success");
                setTimeout(function() { window.history.back() }, 5000);
            } else {
                show_alert(response.message, "danger");
            }
        },
        error: function(response) {
            reset_input();
            show_alert("خطأ في رفع الملف", "danger");
            console.log(response);
        }

    });

    //cancel the upload when click btn
    $("#cancel_btn").click(
        function() {
            xhr_request.abort();
        }
    )
}

// progress bar
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

//run before upload is made
function beforeSend() {
    //to disable choose file, and sponsor list:: while uploading file
    $("#fileToUpload").prop("disabled", true);
    //hide the upload btn 
    $('#upload_btn').addClass("d-none");
    //show the hidden element (loading and cancel btn ,progress bar)
    $('#loading_btn').removeClass("d-none");
    $('#cancel_btn').removeClass("d-none");
}

//to rest input after uploading
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

// loan Validation
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
        success: function(response) {
            if (response.status == 0) {
                $(class_name).removeClass("is-invalid");
                $($div).text('');
            } else {
                $(class_name).addClass("is-invalid").removeClass("is-valid");
                $($div).text(response.message);
            }
        }
    }).done(function(response, textStatus, jqXHR) {
        // checkLoanError();
    });
}

//  all filed Validation
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
            success: function(response) {
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
// signup Validation
function checkSignupField($select, $filed, $div) {
    //get id of input
    var class_name = $filed;
    var $filed = $($filed).val().trim();
    $.ajax({
        url: "signup_validation.php",
        data: { filed: $filed, select: $select },
        type: "POST",
        dataType: 'json',
        success: function(response) {
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

//check if loan_date larger than loan_return_date
function checkDate(first_date, last_date) {
    // initialize date and message and condition
    var start_date = '';
    var end_date = new Date($(last_date).val());
    var error_msg = '';
    var condition = '';

    if (first_date === 'now') {
        start_date = new Date();
        condition = end_date.getTime() < start_date.getTime();
        error_msg = 'لايمكن ان يكون تاريخ الإعادة قبل او يساوي تاريخ الإعارة';
    } else if (first_date === 'just_one') {
        start_date = new Date();
        condition = end_date.getTime() >= start_date.getTime();
        error_msg = 'لا يمكن ان يكون تاريخ التأسيس بعد او يساوي تاريخ اليوم';
    } else if ($(first_date).val() && $(last_date).val()) {
        start_date = new Date($(first_date).val());
        condition = end_date.getTime() <= start_date.getTime();
        error_msg = 'لايمكن ان يكون تاريخ الوفاه قبل او يساوي تاريخ الميلاد';
    }

    // show and hide error message according to condition
    if (condition) $(last_date).addClass("is-invalid").next().text(error_msg);
    else $(last_date).removeClass("is-invalid").next().text('');
}

// to check the author name and category name is not repeated
function checkName(url, param, filed, oldAuthorName) {

    var condition = (oldAuthorName) ? (filed.val().trim() != oldAuthorName) : filed.val();

    if (condition) {
        $.ajax({
            type: "POST",
            url: url,
            data: param,
            dataType: 'json',
            success: function(response) {
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

//to read more function
function readMore() {
    $('.more-btn').slideToggle();
    $('.more-btn').html($('.more-btn').html == 'readMore' ? 'readLess' : 'readMore');
}

//auto complete of advance search page
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
        success: function(response) {
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

// to  make  multi part

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