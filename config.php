<?php
function getBaseUrl() {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $baseUrl = "https://";
    } else {
        $baseUrl = "http://";
    }
    $baseUrl .= $_SERVER['HTTP_HOST'] . '/college_notes_gallery/';
    return $baseUrl;
}
?>
