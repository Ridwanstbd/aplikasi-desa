<?php
$target = $_SERVER['DOCUMENT_ROOT'] . '/../arjunafarm/laravel/storage/app/public';
$link = $_SERVER['DOCUMENT_ROOT'] . '/arjunafarm/storage';
if (symlink($target, $link)) {
    echo 'OK.';
} else {
    echo 'Gagal.';
}
?>
