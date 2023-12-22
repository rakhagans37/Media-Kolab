<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function foundRole($roleName)
{
    $conn = getConnection();
    $sqlCheck = "SELECT * FROM tb_role WHERE role_name LIKE '$roleName'";

    $request = $conn->prepare($sqlCheck);
    $request->execute();
    if ($request->fetchAll()) {
        return true;
    } else {
        return false;
    }
}
