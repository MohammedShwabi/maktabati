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