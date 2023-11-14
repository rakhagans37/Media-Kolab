<?php
require_once 'getConnection.php';
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

function uploadImage($idAdmin, $location)
{
    $newPhoto = $_FILES['new-photo']['tmp_name'];
    $photoName = random_int(0, PHP_INT_MAX) . date("dmYHis") . $idAdmin;
    $photoNameHashed = openssl_encrypt($photoName, 'AES-128-CTR', 'mediaKolab123', 0, '1234567891011121');

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
        $decrypt = openssl_decrypt($photoNameHashed, 'AES-128-CTR', 'mediaKolab123', 0, '1234567891011121');

        //Automatically getting the Photo
        $imgtag = getImage($decrypt);
        setcookie('profilePhoto', $imgtag, time() + (86400 * 7));

        $conn = null;
        header("Location:$location");
    } catch (PDOException $errorMessage) {
        $error = $errorMessage->getMessage();
        echo $error;
    }
}
