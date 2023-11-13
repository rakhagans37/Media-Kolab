<?php
/*
Validasi login untuk editor
*/
session_start();

if (!isset($_COOKIE['loginStatus']) && !isset($_SESSION['loginStatus'])) {
    header('Location:loginPublisher.php');
    exit;
}
