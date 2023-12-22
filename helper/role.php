<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";
require_once __DIR__ . "/roleValidation.php";

function setNewRole($idRole, $roleName)
{
    $conn = getConnection();
    $sqlAdd = "INSERT INTO tb_role VALUES(?,?)";

    $request = $conn->prepare($sqlAdd);
    $request->bindParam(1, $idRole);
    $request->bindParam(2, $roleName);
    $request->execute();

    $conn = null;
}
