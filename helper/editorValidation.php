<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";
require_once __DIR__ . "/validation.php";

// Check password req has been fulfilled
function passwordReqSuccess($password)
{
    if (isAlphanumeric($password) && (strlen($password) >= 8 && strlen($password) <= 20)) {
        return true;
    } else {
        return false;
    }
}

// Check phone number req has been fulfilled
function phoneNumberReqSuccess($phoneNumber)
{
    if (strlen($phoneNumber) >= 12 && strlen($phoneNumber) <= 13) {
        return true;
    } else {
        return false;
    }
}

// Check is editor username was existed
function editorUsernameExist($username)
{
    $conn = getConnection();
    // Cek Ketersediaan Username
    $sql = "SELECT * FROM tb_editor WHERE username = :username";
    $request = $conn->prepare($sql);
    $request->bindParam('username', $username);
    $request->execute();

    if ($request->fetchAll()) {
        $conn = null;
        return true;
    } else {
        $conn = null;
        return false;
    }
}

// Check is editor phoneNumber was existed
function editorPhoneNumberExist($phoneNumber)
{
    $conn = getConnection();
    // Cek Ketersediaan phoneNumber
    $sql = "SELECT * FROM tb_editor WHERE phone_number = :phoneNumber";
    $request = $conn->prepare($sql);
    $request->bindParam('phoneNumber', $phoneNumber);
    $request->execute();

    if ($request->fetchAll()) {
        $conn = null;
        return true;
    } else {
        $conn = null;
        return false;
    }
}

// Check is editor email was existed
function editorEmailExist($email)
{
    $conn = getConnection();
    // Cek Ketersediaan email
    $sql = "SELECT * FROM tb_editor WHERE email = :email";
    $request = $conn->prepare($sql);
    $request->bindParam('email', $email);
    $request->execute();

    if ($request->fetchAll()) {
        $conn = null;
        return true;
    } else {
        $conn = null;
        return false;
    }
}

// Check if editor login is success
function editorLoginSuccess($email, $password)
{
    $conn = getConnection();

    $sql = "SELECT * FROM tb_editor where email = :email and banned = :unBanned";
    $unBanned = false;
    $request = $conn->prepare($sql);

    $request->bindParam('email', $email);
    $request->bindParam('unBanned', $unBanned);
    $request->execute();

    if ($result = $request->fetch()) {
        $editorId = $result['editor_id'];
        $passwordHashed = $result['password'];

        if (password_verify($password, $passwordHashed)) {
            $conn = null;
            return $editorId;
        } else {
            $conn = null;
            return false;
        }
    } else {
        $conn = null;
        return false;
    }
}
