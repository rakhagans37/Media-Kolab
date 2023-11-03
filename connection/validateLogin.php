<?php
session_start();

if (!isset($_COOKIE['loginStatus']) && !isset($_SESSION['loginStatus'])) {
    header('Location:login.php');
    exit;
}
