<?php
require_once 'init.php';
$pageTitle =  lang('edit_book');
require_once $tmpl . 'header.php';

?>
<br><br><br><br><br>
<div class="col-12">
    <h1 class="display-6 mx-4 text-center" id="edit_book_title">
        <?= lang("edit_book_test") ?>
    </h1>
    <hr>
</div>
</div>

<?php
include $tmpl . 'footer.php';
?>