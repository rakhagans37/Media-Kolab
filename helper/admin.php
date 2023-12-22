<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";
require_once __DIR__ . "/validation.php";

function getAdminData($adminId)
{
    $conn = getConnection();

    $sql = "SELECT * FROM tb_admin WHERE admin_id = :adminId";

    $request = $conn->prepare($sql);
    $request->bindParam("adminId", $adminId);
    $request->execute();

    if ($result = $request->fetch()) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function getAdminPhotoUrl($adminId)
{
    try {
        $conn = getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT profile_photo FROM tb_admin WHERE admin_id = :adminId";
        $request = $conn->prepare($sql);

        $request->bindParam('adminId', $adminId);
        $request->execute();

        if ($result = $request->fetchAll()) {
            $photoName = $result[0]['profile_photo'];
            if (is_null($photoName)) {
                $decryptPhotoName = null;
            } else {
                $decryptPhotoName = decryptPhotoProfile($photoName);
            }
        }
        $conn = null;
        return $decryptPhotoName;
    } catch (PDOException $errorMessage) {
        $error = $errorMessage->getMessage();
        echo $error;
    }
}

function deleteAdminPhoto($adminId)
{
    $photoAdmin = getAdminPhotoUrl($adminId);
    if (!is_null($photoAdmin)) {
        // Delete image from cloud
        deleteImage($photoAdmin);
    }
}

function setAdminPhoto($adminId, $newPhotoHashed)
{
    $conn = getConnection();

    $sqlInputPhoto = "UPDATE tb_admin SET profile_photo = :newPhotoHashed WHERE admin_id = :adminId";
    $request = $conn->prepare($sqlInputPhoto);
    $request->bindParam("newPhotoHashed", $newPhotoHashed);
    $request->bindParam("adminId", $adminId);
    $request->execute();

    $conn = null;
}

function saveAdminPhoto($photoName)
{
    $imgtag = getImageProfile($photoName);
    if (isset($_COOKIE['loginStatus'])) {
        setcookie('profilePhoto', $imgtag, time() + (86400 * 7));
    } else {
        $_SESSION['profilePhoto'] = $imgtag;
    }
}
