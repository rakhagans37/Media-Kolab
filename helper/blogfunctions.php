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
    // Use the AdminAPI class for managing assets
    use Cloudinary\Transformation\Resize;
    use Cloudinary\Transformation\Gravity;
    use Cloudinary\Transformation\FocusOn;
    use Cloudinary\Transformation\RoundCorners;
    use Cloudinary\Transformation\Delivery;
    use Cloudinary\Transformation\Format;
    use Cloudinary\Tag\ImageTag;

    // Configure an instance of your Cloudinary cloud
    Configuration::instance('cloudinary://687349936855341:YYl-ARmSPNM0vXhBOL3SeY-bQcg@drmtgjbht');
    
    function dateFormatter($dateString)
    {
        $formattedDate = date('d F Y', strtotime($dateString));
        return $formattedDate;
    }

    function getTagNameFromId($idTag)
    {
        try {
            $conn = getConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT tag_name FROM tb_tag WHERE tag_id = :tagId";
            $request = $conn->prepare($sql);

            $request->bindParam('tagId', $idTag);
            $request->execute();

            if ($result = $request->fetchAll()) {
                return $result[0]['tag_name'];
            }
        } catch (PDOException $errorMessage) {
            $error = $errorMessage->getMessage();
            echo $error;
        }
    }

    function getCategoryBlogNameFromId($idcategory)
    {
        try {
            $conn = getConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT category_name FROM tb_category_blog WHERE category_id = :categoryId";
            $request = $conn->prepare($sql);

            $request->bindParam('categoryId', $idcategory);
            $request->execute();

            if ($result = $request->fetchAll()) {
                return $result[0]['category_name'];
            }
        } catch (PDOException $errorMessage) {
            $error = $errorMessage->getMessage();
            echo $error;
        }
    }
?>