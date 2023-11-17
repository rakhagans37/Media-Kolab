<?php
/*
Validasi login untuk editor
*/
session_start();

if (!isset($_COOKIE['editorLoginStatus']) && !isset($_SESSION['editorLoginStatus'])) {
    header('Location:loginEditor.php');
    exit;
}
