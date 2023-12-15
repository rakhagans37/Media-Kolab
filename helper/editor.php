<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function setEditorUsername($editorId, $newUsername)
{
    $connMyqli = getConnectionMysqli();
    $sqlUpdateName = "UPDATE tb_editor SET username = '$newUsername' WHERE editor_id = $editorId ";
    mysqli_query($connMyqli, $sqlUpdateName);
    mysqli_close($connMyqli);
}

function updateEditorPhoto($editorId, $newPhotoHashed)
{
    $conn = getConnection();

    $sqlInputPhoto = "UPDATE tb_editor SET profile_photo = :newPhotoHashed WHERE editor_id = :editorId";
    $request = $conn->prepare($sqlInputPhoto);
    $request->bindParam("newPhotoHashed", $newPhotoHashed);
    $request->bindParam("editorId", $editorId);
    $request->execute();

    $conn = null;
}

function setEditorImageToNull($editorId)
{
    $conn = getConnection();
    $newImage = null;

    $sqlUpdate = "UPDATE tb_editor SET 
					profile_photo = :newImage
					WHERE editor_id = :editorId";

    $request = $conn->prepare($sqlUpdate);
    $request->bindParam("newImage", $newImage);
    $request->bindParam("editorId", $editorId);
    $request->execute();

    $conn = null;
}

function saveEditorPhoto($photoUrl)
{
    $imgtag = getImageProfile($photoUrl);
    if (isset($_COOKIE['editorLoginStatus'])) {
        setcookie('editorProfilePhoto', $imgtag, time() + (86400 * 7));
    } else {
        $_SESSION['editorProfilePhoto'] = $imgtag;
    }
}

function deleteEditorPhoto($editorId)
{
    $editorData = getEditorData($editorId);
    $photoEditor = $editorData['profile_photo'];
    if (!is_null($photoEditor)) {
        // Delete image from cloud
        deleteImage(decryptPhotoProfile($photoEditor));
    }
}

function setEditorPhone($editorId, $newPhoneNumber)
{
    $connMyqli = getConnectionMysqli();
    $sqlUpdateNoPhone = "UPDATE tb_editor SET phone_number = '$newPhoneNumber' WHERE editor_id = $editorId ";
    mysqli_query($connMyqli,  $sqlUpdateNoPhone);
    mysqli_close($connMyqli);
}

function setEditorRole($editorId, $roleId)
{
    $connMyqli = getConnectionMysqli();
    $sqlUpdateNoPhone = "UPDATE tb_editor SET role_id = '$roleId' WHERE editor_id = $editorId ";
    mysqli_query($connMyqli,  $sqlUpdateNoPhone);
    mysqli_close($connMyqli);
}

function setEditorPassword($editorId, $newPassword)
{
    $connMyqli = getConnectionMysqli();
    $newPasswordUser = hashPassword($newPassword);
    $sqlUpdatePassword = "UPDATE tb_editor SET password = $newPasswordUser WHERE editor_id = $editorId ";
    mysqli_query($connMyqli,   $sqlUpdatePassword);
    mysqli_close($connMyqli);
}

function setEditorEmail($editorId, $newEmail)
{
    $connMyqli = getConnectionMysqli();
    $sqlUpdateEmail = "UPDATE tb_editor SET email = '$newEmail' WHERE editor_id = $editorId ";
    mysqli_query($connMyqli,   $sqlUpdateEmail);
    mysqli_close($connMyqli);
}

function getEditorData($editorId)
{
    $conn = getConnection();
    $sql = "SELECT tb_editor.editor_id, tb_editor.username, tb_editor.email, tb_editor.description, tb_editor.phone_number, tb_editor.profile_photo, tb_role.role_name FROM tb_editor INNER JOIN tb_role ON tb_editor.role_id = tb_role.role_id WHERE editor_id = :idEditor";
    $request = $conn->prepare($sql);
    $request->bindParam(':idEditor', $editorId);
    $request->execute();

    if ($result = $request->fetch()) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function setNewEditor($username, $password, $email, $phoneNumber, $roleId)
{
    $conn = getConnection();

    $id = generateIdEditor();
    $passwordHashed = hashPassword($password);
    $sqlInsert = "INSERT INTO tb_editor(editor_id, username, password, email, phone_number, role_id) values(?, ?, ?, ?, ?, ?)";
    $requestInsert = $conn->prepare($sqlInsert);
    $requestInsert->bindParam(1, $id);
    $requestInsert->bindParam(2, $username);
    $requestInsert->bindParam(3, $passwordHashed);
    $requestInsert->bindParam(4, $email);
    $requestInsert->bindParam(5, $phoneNumber);
    $requestInsert->bindParam(6, $roleId);

    $requestInsert->execute();

    $conn = null;
    return true;
}
