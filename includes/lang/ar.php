<?php
function lang($phrase)
{
  static $lang = array(
    // this lang for index.php
    //global lang
    'NotAuthorize'                         => 'عذرا لايمكنك الوصول الى هذه الصفحة ',
    //lang of page title
    'book_details'                         => 'تفاصيل الكتاب',
    'advance_search_title'                 => 'بحث متقدم',
    'login_title'                          => 'تسجيل الدخول',
    'signup_title'                         => 'إتشاء حساب',

    //lang of header.php
    'Maktabati'                            => 'مكتبتي',
    'home'                                 => 'الرئيسية',
    'categories'                           => 'الأقسام',
    'authors'                              => 'المؤلفين',
    'publishers'                           => 'دور النشر',
    'search'                               => 'بحث',
    'login'                                => 'تسجيل الدخول',
    'logout'                               => 'تسجيل الخروج',
    'logout_Accept'                        => 'خروج',
    'logout_popup'                         => 'هل انت متاكد انك تريد الخروج ؟',
    //end of header.php

    //Start of login.php
    'email'                                => 'البريد الإلكتروني',
    'password'                             => 'كلمة المرور',
    'email_placeholder'                    => 'mohammeed@gmail.com',
    'password_placeholder'                 => 'Mohammed@123#$',
    'no_account'                           => 'ليس لديك حساب ؟  ',
    'new_account'                          => 'إنشاء حساب ',
    'login_error'                          => ' خطأ في البريد الإلكتروني أو كلمة المرور الرجاء المحاولة مرة اخرى',
    'login_empty'                          => 'الرجاء ملئ جميع الحقول ',
    //end of login.php

    //Start of signup.php
    'create_new_account'                   => 'إنشاء حساب جديد',
    'create_account'                       => 'إنشاء حساب',
    'username'                             => 'إسم المستخدم',
    'username_placeholder'                 => ' محمد نبيل شوابي',
    'agree'                                => 'الموافقة على',
    'condition_and_rule'                   => ' الشروط و الاحكام',
    'hav_account'                          => ' لديك حساب ؟ ',
    'signup_empty_field'                   => 'الرجاء ملئ كل الحقول المطلوبة والموافقة على الشروط والاحكام',
    'signup_success'                       => 'تم إنشاء حسابك بنجاح قم بتسجيل الدخول',
    'error_email_exist'                    => 'عذرا !! هذا البريد الإلكتروني موجود مسبقا',
    'error_email_invalid'                  => ' عذرا  !! البريد الإلكتروني غبر صحيح ',
    'success_email_valid'                  => 'هذا البريد الإلكتروني صحيح ',
    'error_invalid_pass'                   => 'كلمة المرور ضعيفة ',
    'success_valid_pass'                   => 'كلمة مرور قوية',
    //End  of signup.php
    //Start  of advance_search.php
    'advance_search'                       => 'بحث متقدم عن الكتب',
    'book_title'                           => 'عنوان الكتاب',
    'publish_date'                         => 'تاريخ النشر',
    'author'                               => 'المؤلف',
    'language'                             => 'اللغة',
    'publisher'                            => 'دار النشر',
    'isbn'                                 => 'الترقيم الدولي',
    'category'                             => 'القسم',
    'series_name'                          => 'اسم السلسة',
    'dewyNo'                               => 'التصنيف الديوي',

    'advance_search_place'                 => 'بحث متقدم عن الكتب',
    'book_title_place'                     => 'كتاب الرحيق المختوم',
    'publish_date_place'                   =>  '2007-09',
    'author_place'                         => ' صفي الرحمن المباركفوري',
    'language_place'                       => 'العربية',
    'publisher_place'                      => 'دار المعرفة',
    'isbn_place'                           => '769-867-9865-85-3',
    'category_place'                       => 'السيرة النبوية',
    'series_name_place'                    => 'عالم المعرفة',
    'dewy_no_place'                        => '225',
    'rest'                                 => 'إعادة تعين',
    'input_search_empty'                   => 'يجب ملئ الحقل للبحث',
    'search_empty'                         => ' للبحث يجب ملئ حقل واحد على الاقل',
    'no_match_result'                      =>'لا يوجد نتائج مطابقة لهذا الحقل ',
    //End  of advance_search.php

    //Start  of book_details.php
    'pages_no'                             => ' عدد الصفحات',
    'size'                                 => ' الحجم',
    'file_type'                            => ' نوع الملف ',
    'no_of_copy'                           => ' عدد النسخ',
    'book_description'                     => ' وصف الكتاب ',
    'read_more'                            => '   إقرأ المزيد ' ,
    'read_less'                            => '  إقرأ أقل  ',
    'download'                             => ' تحميل ',
    'open'                                 => ' تصفح ',
    'rating'                               => ' تقيم ',
    'upload'                               => ' رفع ',
    'borrows'                              => ' إعارة ',
    'No_ID'                                => ' عفوا لايوجد كتاب يحمل هذا الرقم',
    'add'                                  => 'إضافة',
    'cancel'                               => 'إلغاء',
    'rating_popup'                         => 'قم بتقيم الكتاب',
    //End  of book_details.php

    // start of sticky button template
    'add_category'                         => 'إضافة قسم',
    'add_publisher'                        => 'إضافة دار نشر',
    'add_book'                             => 'إضافة كتاب',
    'add_author'                           => 'إضافة مؤلف',
    'add_author'                           => 'إضافة مؤلف',

    //start of book template
    'edit_book'                            => 'تعديل الكتاب',
    'delete_book'                          => 'حذف الكتاب',
    'no_book'                              => 'لا يوجد اي كتب لعرضها...',

    //start of category template
    'edit_cat'                             => 'تعديل القسم',
    'delete_cat'                           => 'حذف القسم',
    'edit_pub'                             => 'تعديل دار النشر',
    'delete_pub'                           => 'حذف دار النشر',

    //start of author template
    'edit_auth'                            => 'تعديل المؤلف',
    'delete_auth'                          => 'حذف المؤلف',
    'book'                                 => 'كتاب',

    // start of feature template
    'feature_one_title'                    => '4,000 عملية بحث يومياً',
    'feature_one_subtitle'                 => 'أكثر من 4 ألف عملية بحث عن كتاب عربي تحدث يومياً على مكتبتي',
    'feature_two_title'                    => '9,835 كتاب',
    'feature_two_subtitle'                 => 'آلاف الكتب المنشورة على مكتبتي منها ما نشره المؤلف بنفسه أو فريق المكتبة',
    'feature_three_title'                  => '2,204 مؤلف',
    'feature_three_subtitle'               => 'تهدف مكتبتي إلى إنشاء أكبر قاعدة بيانات لمؤلفين الكتب العربية',
    'feature_four_title'                   => '6,000 زائر شهرياً',
    'feature_four_subtitle'                => 'يزور موقع مكتبتي اكثر من 6 ألف زائر مهتم بالكتب العربية شهرياً حول العالم',

    //start of search template
    'search_category'                      => 'ابحث عن قسم',
    'search_author'                        => 'ابحث عن مؤلف',
    'search_publisher'                     => 'ابحث عن دار نشر',
    'search_home'                          => 'ابحث عن كتاب أو مؤلف أو قسم كتاب أو دار نشر',
    'search_btn'                           => 'بحث',
    'no_result_section'                    => 'لا توجد نتائج',

    // start of index
    'home_title'                           => 'مكتبتي.... عالم من المعرفة بين يديك',

    // start of page title template
    'category_title'                       => 'أقسام الكتب',
    'publisher_title'                      => 'دور النشر',
    'author_title'                         => 'مؤلفو الكتب',

    // start of popup template
    'delete'                               => 'حذف',
    'close'                                => 'إغلاق',
    'no_delete'                            => 'عذراً!!',

    // book delete message
    'delete_book_popup_text'               => 'هل انت متأكد من حذف الكتاب',
    'no_delete_book_popup_text'            => 'عذرا ، لا يمكن حذف هذا الكتاب لأنه مُعار حاليا.',
    // publisher delete message
    'delete_publisher_popup_text'          => 'هل انت متأكد من حذف دار النشر',
    'no_delete_publisher_popup_text'       => 'عذرا ، لا يمكن حذف دار النشر هذه لأنها تحتوي على بعض الكتب.',
    // category delete message
    'delete_category_popup_text'           => 'هل انت متأكد من حذف القسم',
    'no_delete_category_popup_text'        => 'عذرا ، لا يمكن حذف هذا القسم لأنه يحتوي على بعض الكتب.',
    // author delete message
    'delete_author_popup_text'             => 'هل انت متأكد من حذف المؤلف',
    'no_delete_author_popup_text'          => 'عذرا ، لا يمكن حذف هذا المؤلف لأنه لديه بعض الكتب المتعلقه.',

    // for search resulat page
    'no_result'                            => 'لا يوجد أي كتاب بهذا العنوان. الرجاء التأكد من صحة العنوان.',
    'no_result_advance'                    => 'لا يوجد نتائج فيما تبحث عنه !!',
    'result_title'                         => 'نتيجة البحث :',
    'result_breadcrumb_title'              => 'نتائج البحث',

    // for publisher details
    'was_established'                      => 'تأسست ',
    'in_date'                              => ' في تاريخ ',
    'by'                                   => ' من قبل ',
    'sequential_deposit_no'                => ' وتحمل الرقم المتسلسل ',

    // for redirect message
    'redirect_after' => ' سيتم تحويلك بعد ',
    'second' => ' ثواني ...',
    'redirect_search_message'              => 'يجب عليك البحث عن كتاب للوصول الى هذه الصفحة',
    'redirect_book_message'                 => 'يجب إختيار كتاب للوصول الى هذه الصفحة',
    'redirect_pub_message'                 => 'يجب إختيار دار نشر للوصول الى هذه الصفحة',
    'redirect_cat_message'                 => 'يجب إختيار قسم للوصول الى هذه الصفحة',
    'redirect_auth_message'                => 'يجب إختيار مؤلف للوصول الى هذه الصفحة',

    // for upload book page
    'no_session'                           => 'يجب عليك تسجيل الدخول اولاً',
    'no_data'                              => 'يجب اختيار ملف لرفعة..!',
    'file_exists'                          => 'الكناب موجود مسبقاً..!',
    'not_allowed'                          => 'عذراً, غير مسموح برفع ملفات عدا :',
    // Sorry, there was an error uploading your file.
    'error_uploading'                      => 'عذراً, فشل رفع الملف الرجاء المحاوله لاحقاً',
    'error_insert'                         => 'عذراً, فشل إضافة الملف',
    'book_inserted'                        => 'تم رفع الكتاب بنجاح 😀',

    //start  of upload.php
    'upload_file'                          => ' رفع ملف الى السرفر',
    'select_file'                          => ' إختار الملف  لرفعه الى السرفر',
    'file_not_exist'                       => '!! عذرا الملف غير موجود ',
    'load_btn'                             => 'تحميل',
    'cancel_btn'                           => 'إلغاء',
    //End  of upload.php

    //start add category
    'category_name'                        => 'إسم القسم',
    'category_placeholder'                 => 'التاريخ والحضارة',

    // start of check_cat.php
    'valid_cat_name'                        => 'إسم القسم صحيح',
    'invalid_cat_name'                      => 'إسم القسم موجود مسبقا',
    'no_cat_name'                           => 'يجب كتابة اسم القسم..!',

    // start loaning Book
    'loan_book'                           => 'إعارة كتاب',
    'loan_name'                           => 'إسم  المستعير',
    'loan_name_placeholder'               => 'علي محمد صالح',
    'copy_number'                         => 'رقم النسخة',
    'loan_date'                           => 'تاريخ الاستعارة',
    'loan_return_date'                    => 'تاريخ الاعادة',
    'select_loan_name'                    => 'إختر اسم المستعير',
    'select_copy_number'                  => 'إختر رقم النسخة',
    'loan_success'                        => 'تم تسجيل الإعارة بنجاح ',
    'loan_empty'                          => 'الرجاء ملئ كل الحقول ',
    'user_have_loan'                      => ' لا يمكن إعارة هذا الشخص لأنه قد استعار كتاب اخر ',
    'part_have_loan'                      => 'هذة النسخة  معارة مسبقا ',
    'just_one_copy'                       => 'هذة النسخة غير قابلة للإعارة ',
    'loan'                                => 'اعارة',

    //add-publisher page
    'add_publisher'                       => 'إضافة دار نشر',
    'pub_name'                            => 'إسم دار النشر',
    'sequence_no'                        => 'رقم الإجازة المتسلسل',
    'establish_date'                      => 'تاريخ التأسيس',
    'pub_owner'                           => 'مؤسس دار النشر',
    'pub_lang'                            => 'لغات النشر',
    'select_pub_lang'                     => 'إختر اللغة',
    'bad_date_format'                     => 'تنسيق التاريخ غير مناسب',
    'save'                                => 'حفظ',

    'pub_name_place'                      => 'عصير الكتب للنشر والتوزيع',
    'sequence_no_place'                   => '8743612',
    'pub_owner_place'                     => 'محمد شوقي',

    'pub_exist'                           => 'دار النشر موجود مسبقا',
    'pub_seq_exist'                       => 'هذا الرقم موجود مسبقا',
    'publish_success'                     => 'تم حفظ دار النشر بنجاح',
    'publish_error'                       => 'حدث خطأ الرجاء المحاولة مرة اخرى',
    'publish_edit_success'                => 'تم تعديل دار النشر بنجاح',
    '' => '',
    //edit publisher
    'edit_publisher'  => 'تعديل دار نشر',
    'update'  => 'تحديث',

    // add author page
    'author_name'  => 'إسم المؤلف',
    'author_name_place'  => 'محمود درویش',
    'nationality'  => 'الجنسیة',
    'nationality_place'  => 'مصري',
    'profession'  => 'المھنة',
    'profession_place'  => 'مؤلف ومراجع',
    'birthday'  => 'تاریخ المیلاد',
    'deathday'  => 'تاریخ الوفاه',
    'author_description'  => 'وصف قصیر',
    'author_description_place'  => 'وصف قصیر عن المؤلف',

    // start of check_author.php
    'valid_author_name'                        => 'إسم المؤلف صحيح',
    'invalid_author_name'                      => 'إسم المؤلف موجود مسبقا',
    'no_author_name'                           => 'يجب كتابة اسم المؤلف..!',

    // add_author.php
    'author_error_uploading'                      => 'عذراً, فشل رفع الصورة الرجاء المحاوله لاحقاً',
    'author_photo_exists'                          => 'الصورة موجود مسبقاً..!',
    'add_author_success'                        => 'تم إضافة المؤلف بنجاح ',
    'add_author_fail'                        => 'فشل إضافة المؤلف',
    'fill_all_filled'                        => 'يجب ملى جميع الحقول الالزامية',
    'author_date_error'                        => 'لايمكن ان يكون تاريخ الوفاه قبل او يساوي تاريخ الميلاد',

    // edit_author.php
    'edit_author'                           => 'تعديل المؤلف',
    'edit_author_success'                        => 'تم تعديل المؤلف بنجاح ',
    'edit_author_fail'                        => 'فشل تعديل المؤلف',
    
    // add book page 
    '' => '',
    'book_sub_title'  => 'العنوان الفرعي',
    'work_on_book'  => 'العمل على الكتاب',
    'edition_no'  => 'رقم الطبعة',
    'depository_no'  => 'رقم الإيداع',
    'price'  => 'السعر',
    'book_location'  => 'موقع الكتاب',
    'part_number' => 'رقم الجزء',
    'no_in_series' => 'رقم الكتاب في السلسلة',
    'attachment_name' => 'اسم الملحق',
    'attachment_type' => 'نوع الملحق',
    'attachment_loc' => 'موقع الملحق',
    'book_sub_title_place' => 'شرح السيرة النبوية',
    'book_description_place' => 'ومن منهجي في هذا الكتاب ـ عدا ما جاء في إعلان الرابطة ـ أني قررت سلوك سبيل الاعتدال، متجنبًا التطويل الممل والإيجاز المخل، وقد وجدت المصادر تختلف فيما بينها حول كثير مما يتعلق بالأحداث اختلافًا لا يحتمل الجمع والتوفيق، فاخترت سبيل الترجيح، وأثبت في الكتاب ما ترجح لدي بعد التدقيق في الدراسة والنقد، إلا أني طويت ذكر الدلائل والوجوه؛ لأن ذلك يفضي إلى طول غير مطلوب‏.',
    'publisher_place' => 'دار العلم للملايين',
    'depository_no_place' => '2021/23',
    'book_location_place' => 'مكان الكتاب داخل المكتبة',
    'attachment_name_place' => 'شرح الكتاب كاملا',
    'attachment_type_place' => 'تسجيل صوتي ',
    'attachment_loc_place' => 'بجانب الكتاب',
    'is_book_has_series' => 'هل الكتاب ضمن سلسة',
    'is_book_has_attachment' => 'هل يوجد ملحقات للكتاب',
    'is_book' => 'هل الكتاب ',
    'one_part' => ' جزء واحد ',
    'multi_parts' => 'اكثر من جزء',
    'edition_desc' => 'وصف الطبعة',
    'part_no' => 'عدد الأجزاء',
    'publish_place' => 'مكان النشر',
    'publish_placeholder' => 'طرابلس ',
    'select_category' => 'اختر القسم | الموضوع',
    'select_author_name' => 'اختر  المؤلف',
    'select_work_on_book' => 'إختر العمل على الكتاب',
    'authoring' => 'تاليف',
    'translate' => 'ترجمة',
    'checking' => 'تحقيق',
    'reviewing' => 'مراجعة',
    'select_publisher' => 'إختر دار النشر',
    'select_series_name' => 'إختر السلسة',
    'still_one_choses_empty' => 'يجب تحديد عنصر من كل القوائم المطلوبة',
    'unexpected_error' => 'حدث خطأ غير متوقع الرجاء ادخال البيانات بشكل صحيح في حال استمرت المشكلة تواصل مع المطور ',
    'add_book_success'                        => 'تم إضافة الكتاب بنجاح ',
    'add_book_fail'                        => 'فشل إضافة الكتاب',
    'book_exist' => 'هذا الكتاب موجود مسبقا',
    'edition_desc_place' => 'منقحة',
    'book_photo_required' => 'يرجى إضافة صورة الكتاب',
    
    // edit book page 
    'edit_book_title' => 'منقحة',
    


    'no_connection_error' => 'عذرا تاكد من اتصالك بقواعد البيانات وحاول مرة اخرى',
    'search_about' => 'ابحث عن ',
    'search_use' => 'بحث بإستخدام',
    '' => '',

  );

  return  $lang[$phrase];
}
