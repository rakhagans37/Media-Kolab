<?php
require_once 'getConnection.php';
require_once 'hash.php';
require __DIR__ . '/../vendor/autoload.php';

// Use the Configuration class 
use Cloudinary\Cloudinary;
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
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dx57frg2b',
        'api_key' => '777535978957269',
        'api_secret' => 'y0t83iNgf32i4tHEVI21t5kLiFM'
    ],
    'url' => [
        'secure' => true
    ]
]);

function addAltAttributeToImg($htmlCode, $message)
{
    // Check if the <img> tag is present in the HTML code
    if (strpos($htmlCode, '<img') !== false) {
        // Add alt attribute if <img> tag is found
        $modifiedCode = str_replace('<img', "<img alt='$message'", $htmlCode);
        return $modifiedCode;
    } else {
        // Return the original code if <img> tag is not found
        return $htmlCode;
    }
}

function imageTagToURL($imgtag)
{
    // Use regular expression to extract the source URL
    $pattern = '/<img src="([^"]+)"/';
    preg_match($pattern, $imgtag, $matches);

    if (isset($matches[1])) {
        $sourceUrl = $matches[1];
        return $sourceUrl;
    } else {
        return "";
    }
}

function getImageCircle($urlPhoto, $width = 60, $altMessage = "Profile Photo")
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
        ->resize(Resize::scale()->width($width))
        ->delivery(Delivery::format(
            Format::auto()
        ));

    $finalImgTag = addAltAttributeToImg($imgtag, $altMessage);

    return (string)$finalImgTag;
}

function getImageDefault($urlPhoto)
{
    //Get Photo
    $imgtag = (new ImageTag($urlPhoto))
        ->resize(Resize::limitFit()->width(1000)->height(520))
        ->delivery(Delivery::format(
            Format::auto()
        ))->delivery(Delivery::quality(60));

    return imageTagToURL($imgtag);
}

function getImageAds($urlPhoto)
{
    //Get Photo
    $imgtag = (new ImageTag($urlPhoto))
        ->roundCorners(RoundCorners::byRadius(12))
        ->delivery(Delivery::quality(60));

    return imageTagToURL($imgtag);
}

function getImageNews($urlPhoto, $altMessage = "Post Thumbnail")
{
    $admin = new AdminApi();
    $assetData = $admin->asset($urlPhoto, [
        'colors' => TRUE
    ]);
    $assetTotal = $assetData['width'] + $assetData['height'];
    $assetWidth = 16 / 26.7 * $assetTotal;
    $assetHeight = 10.7 / 26.7 * $assetTotal;
    //Get Photo
    $imgtag = (new ImageTag($urlPhoto))
        ->resize(
            Resize::crop()->width($assetWidth)
                ->height($assetHeight)
        )
        ->resize(Resize::scale()->width(999)->height(668))
        ->delivery(Delivery::format(
            Format::auto()
        ))->delivery(Delivery::quality(60));

    $imgtag = addAltAttributeToImg($imgtag, $altMessage);
    return (string)$imgtag;
}

function uploadImage($photoTemp, $photoName)
{
    //Upload into cloudinary process
    $upload = new UploadApi();
    $upload->upload($photoTemp, [
        'public_id' => $photoName,
        'use_filename' => TRUE,
        'overwrite' => TRUE
    ]);
}

//Function for uploading image for blog, event, job-vacancies, and media
function uploadImageNews($newImageTemp)
{
    $newPhotoType = mime_content_type($newImageTemp);

    if ($newPhotoType == 'image/jpg' || $newPhotoType == 'image/png' || $newPhotoType == 'image/jpeg') {
        $photoName = random_int(0, PHP_INT_MAX) . date("dmYHis");
        $photoNameHashed = hashPhotoProfile($photoName);

        //Upload into cloudinary process
        $upload = new UploadApi();
        $upload->upload($newImageTemp, [
            'public_id' => $photoName,
            'use_filename' => TRUE,
            'overwrite' => TRUE
        ]);

        return $photoNameHashed;
    } else {
        return false;
    }
}

function deleteImage($imageUrl)
{
    $api = new UploadApi();
    try {
        $api->destroy($imageUrl);
    } catch (Exception $error) {
        return $error->getMessage();
    }
}
