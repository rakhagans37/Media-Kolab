<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";
require_once __DIR__ . "/validation.php";

function adminLoginSuccess($email, $password)
{
    $conn = getConnection();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM tb_admin where email = :email and password = :password";
    $request = $conn->prepare($sql);

    $request->bindParam('email', $email);
    $request->bindParam('password', $password);
    $request->execute();

    if ($result = $request->fetch()) {
        $conn = null;
        return $result['admin_id'];
    } else {
        $conn = null;
        return false;
    }
}
