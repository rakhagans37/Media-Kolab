<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function setNewMedia($mediaId, $mediaTitle, $mediaContent, $dateRelease, $tagId, $categoryId, $videoUrl, $editorId, $imageUrl, $thumbnail)
{
  $conn = getConnection();

  // Menyimpan data ke database
  $sql = "INSERT INTO tb_media (media_id, media_title, media_content, date_release, category_id, editor_id, video_url, image_url, thumbnail) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $request = $conn->prepare($sql);
  $request->bindParam(1, $mediaId);
  $request->bindParam(2, $mediaTitle);
  $request->bindParam(3, $mediaContent);
  $request->bindParam(4, $dateRelease);
  $request->bindParam(5, $categoryId);
  $request->bindParam(6, $editorId);
  $request->bindParam(7, $videoUrl);
  $request->bindParam(8, $imageUrl);
  $request->bindParam(9, $thumbnail);
  $request->execute();

  $conn = null;
}

function getMediaById($mediaId)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_media WHERE media_id = :mediaId";

  $request = $conn->prepare($sql);
  $request->bindParam("mediaId", $mediaId);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function getMediaByEditor($editorId)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_media WHERE editor_id = :editorId";

  $request = $conn->prepare($sql);
  $request->bindParam("editorId", $editorId);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function getSearchMediaByEditor($editorId, $searchParam)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_media WHERE editor_id = :editorId AND media_title LIKE '%$searchParam%'";

  $request = $conn->prepare($sql);
  $request->bindParam("editorId", $editorId);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function deleteMedia($mediaId)
{
  $conn = getConnection();

  $sql = "DELETE FROM tb_media WHERE media_id = ?";
  $request = $conn->prepare($sql);
  $request->bindParam(1, $mediaId);
  $request->execute();

  $conn = null;
}

function deleteMediaTag($mediaId)
{
  $conn = getConnection();

  $sqlDelete = "DELETE FROM tb_media_tag WHERE media_id = ?";
  $request = $conn->prepare($sqlDelete);
  $request->bindParam(1, $mediaId);
  $request->execute();

  $conn = null;
}

function updateMediaTitle($mediaId, $newTitle)
{
  $conn = getConnection();

  $sqlUpdate = "UPDATE tb_media SET 
					media_title = :updateTitle
					WHERE media_id = :mediaId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("updateTitle", $newTitle);
  $request->bindParam("mediaId", $mediaId);
  $request->execute();

  $conn = null;
}

function updateMediaCategory($mediaId, $newCategory)
{
  $conn = getConnection();

  $sqlUpdate = "UPDATE tb_media SET 
					category_id = :categoryId
					WHERE media_id = :mediaId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("categoryId", $newCategory);
  $request->bindParam("mediaId", $mediaId);
  $request->execute();

  $conn = null;
}

function setMediaImageToNull($mediaId)
{
  $conn = getConnection();
  $newImage = null;

  $sqlUpdate = "UPDATE tb_media SET 
					image_url = :newImage
					WHERE media_id = :mediaId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("newImage", $newImage);
  $request->bindParam("mediaId", $mediaId);
  $request->execute();

  $conn = null;
}

function setMediaThumbnailToNull($mediaId)
{
  $conn = getConnection();
  $newImage = null;

  $sqlUpdate = "UPDATE tb_media SET 
					thumbnail = :newImage
					WHERE media_id = :mediaId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("newImage", $newImage);
  $request->bindParam("mediaId", $mediaId);
  $request->execute();

  $conn = null;
}

function deleteMediaImage($mediaId)
{
  $conn = getConnection();

  $sqlGetImage = "SELECT thumbnail,image_url FROM tb_media WHERE media_id = :mediaId";

  $request = $conn->prepare($sqlGetImage);
  $request->bindParam("mediaId", $mediaId);
  $request->execute();
  $result = $request->fetch();

  if (!is_null($result['image_url']) && !is_null($result['thumbnail'])) {
    deleteImage(decryptPhotoProfile($result['image_url']));
    deleteImage(decryptPhotoProfile($result['thumbnail']));
  }

  $conn = null;
}

function getAllSearchMedia($searchMedia)
{
  $conn = getConnection();

  $sql = $sql = "SELECT tb_media.media_id, tb_media.media_title, tb_category_media.category_name, tb_media.date_release, tb_editor.username, tb_media.views FROM ((tb_media INNER JOIN tb_category_media ON tb_media.category_id = tb_category_media.category_id) INNER JOIN tb_editor ON tb_media.editor_id = tb_editor.editor_id) WHERE tb_media.media_title LIKE '%$searchMedia%'";

  $request = $conn->prepare($sql);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function getAllMedia()
{
  $conn = getConnection();

  $sql = $sql = "SELECT tb_media.media_id, tb_media.media_title, tb_category_media.category_name, tb_media.date_release, tb_editor.username, tb_media.views FROM ((tb_media INNER JOIN tb_category_media ON tb_media.category_id = tb_category_media.category_id) INNER JOIN tb_editor ON tb_media.editor_id = tb_editor.editor_id)";

  $request = $conn->prepare($sql);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}
