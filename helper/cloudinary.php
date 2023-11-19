<?php
require_once 'getConnection.php';
require_once 'hash.php';
require __DIR__ . '\../vendor/autoload.php';

// Use the Configuration class 
use Cloudinary\Configuration\Configuration;
// Use the UploadApi class for uploading assets
use Cloudinary\Api\Upload\UploadApi;
//Get Detailed Photo
use Cloudinary\Api\Admin\AdminApi;
// Use the AdminApi class for managing assets
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\FocusOn;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Tag\ImageTag;

// Configure an instance of your Cloudinary cloud
Configuration::instance('cloudinary://687349936855341:YYl-ARmSPNM0vXhBOL3SeY-bQcg@drmtgjbht');

function getAdminPhotoId($idAdmin)
{
    try {
        $conn = getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT profile_photo FROM tb_admin WHERE admin_id = :adminId";
        $request = $conn->prepare($sql);

        $request->bindParam('adminId', $idAdmin);
        $request->execute();

        if ($result = $request->fetchAll()) {
            $photoName = $result[0]['profile_photo'];
            if (is_null($photoName)) {
                $decryptPhotoName = null;
            } else {
                $decryptPhotoName = decryptPhotoProfile($photoName);
            }
        }
        return $decryptPhotoName;
    } catch (PDOException $errorMessage) {
        $error = $errorMessage->getMessage();
        echo $error;
    }
}

function getImage($urlPhoto)
{
    $admin = new AdminApi();
    $assetData = $admin->asset($urlPhoto, [
        'colors' => TRUE
    ]);
    $assetWidth = $assetData['width'];
    $assetHeight = $assetData['height'];
    $cropSize = $assetHeight <= $assetWidth ? $assetHeight : $assetWidth;
    //Get Photo
    $imgtag = (new ImageTag($urlPhoto))
        ->resize(
            Resize::crop()->width($cropSize)
                ->height($cropSize)
                ->gravity(
                    Gravity::focusOn(
                        FocusOn::face()
                    )
                )
        )
        ->roundCorners(RoundCorners::max())
        ->resize(Resize::scale()->width(60))
        ->delivery(Delivery::format(
            Format::auto()
        ));

    return (string)$imgtag;
}

function uploadImageAdmin($idAdmin, $locationRedirect)
{
    $newPhoto = $_FILES['new-photo']['tmp_name'];
    $newPhotoSize = filesize($newPhoto);
    $newPhotoType = mime_content_type($newPhoto);

    if ($newPhotoSize <= 6000000 && ($newPhotoType == 'image/jpg' || $newPhotoType == 'image/png' || $newPhotoType == 'image/jpeg')) {
        $photoName = random_int(0, PHP_INT_MAX) . date("dmYHis") . $idAdmin;
        $photoNameHashed = hashPhotoProfile($photoName);

        //Delete exPhoto
        deleteImageAdmin($idAdmin, $locationRedirect);

        //Upload into cloudinary process
        $upload = new UploadApi();
        $upload->upload($newPhoto, [
            'public_id' => $photoName,
            'use_filename' => TRUE,
            'overwrite' => TRUE
        ]);

        try {
            $conn = getConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE tb_admin SET profile_photo = :newPhoto WHERE admin_id = :idAdmin";
            $request = $conn->prepare($sql);

            $request->bindParam('idAdmin', $idAdmin);
            $request->bindParam('newPhoto', $photoNameHashed);
            $request->execute();

            //Saving profile photo into cookies
            $decrypt = decryptPhotoProfile($photoNameHashed);

            //Automatically getting the Photo
            $imgtag = getImage($decrypt);
            if (isset($_COOKIE['loginStatus'])) {
                setcookie('profilePhoto', $imgtag, time() + (86400 * 7));
            } else {
                $_SESSION['profilePhoto'] = $imgtag;
            }

            $conn = null;
            header("Location:$locationRedirect");
        } catch (PDOException $errorMessage) {
            $error = $errorMessage->getMessage();
            echo $error;
        }
    } else {
        echo "Gabisa cuy";
    }
}

function deleteImageAdmin($idAdmin, $locationRedirect)
{
    $api = new UploadApi();
    $photoId = getAdminPhotoId($idAdmin);

    if (!is_null($photoId)) {
        $api->destroy($photoId);

        //Update table
        try {
            $conn = getConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE tb_admin SET profile_photo = :setToNull WHERE admin_id = :idAdmin";
            $request = $conn->prepare($sql);
            $setToNull = null;

            //Set into null
            $request->bindParam('idAdmin', $idAdmin);
            $request->bindParam('setToNull', $setToNull);
            $request->execute();

            //Set cookie or session into default image
            $imgtag = "<img class='profile-image' src='assets/images/profiles/profile-1.png' alt='Profile Photo'>";
            if (isset($_COOKIE['loginStatus'])) {
                setcookie('profilePhoto', $imgtag, time() + (86400 * 7));
            } else {
                $_SESSION['profilePhoto'] = $imgtag;
            }

            $conn = null;
            header("Location:$locationRedirect");
        } catch (PDOException $errorMessage) {
            $error = $errorMessage->getMessage();
            echo $error;
        }
    }
}
