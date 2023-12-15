<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function setNewBlog($blogId, $blogTitle, $blogContent, $date_release, $categoryId, $editorId, $image)
{
  $conn = getConnection();
  $sql = "INSERT INTO tb_blog (blog_id, blog_title, blog_content, date_release, 
		category_id, editor_id, image_url) 
		VALUES (?, ?, ?, ?, ?, ?, ?)";
  $request = $conn->prepare($sql);
  // Bind the values to the placeholders
  $request->bindParam(1, $blogId);
  $request->bindParam(2, $blogTitle);
  $request->bindParam(3, $blogContent);
  $request->bindParam(4, $date_release);
  $request->bindParam(5, $categoryId);
  $request->bindParam(6, $editorId);
  $request->bindParam(7, $image);
  // Execute the prepared statement
  $request->execute();

  $conn = null;
}

function getAllBlog()
{
  $conn = getConnection();

  $sql = "SELECT tb_blog.blog_id, tb_blog.blog_title, tb_category_blog.category_name, tb_blog.date_release, tb_editor.username, tb_blog.views FROM ((tb_blog INNER JOIN tb_category_blog ON tb_blog.category_id = tb_category_blog.category_id) INNER JOIN tb_editor ON tb_blog.editor_id = tb_editor.editor_id)";

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

function getAllSearchBlog($searchParam)
{
  $conn = getConnection();

  $sql = "SELECT tb_blog.blog_id, tb_blog.blog_title, tb_category_blog.category_name, tb_blog.date_release, tb_editor.username, tb_blog.views FROM ((tb_blog INNER JOIN tb_category_blog ON tb_blog.category_id = tb_category_blog.category_id) INNER JOIN tb_editor ON tb_blog.editor_id = tb_editor.editor_id) WHERE tb_blog.blog_title LIKE '%$searchParam%'";

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

function getBlogById($blogId)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_blog WHERE blog_id = :blogId";

  $request = $conn->prepare($sql);
  $request->bindParam("blogId", $blogId);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function getBlogByEditor($editorId)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_blog WHERE editor_id = :editorId";

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

function getSearchBlogByEditor($editorId, $searchParam)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_blog WHERE editor_id = :editorId AND blog_title LIKE '%$searchParam%'";

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

function deleteBlog($blogId)
{
  $conn = getConnection();

  $sql = "DELETE FROM tb_blog WHERE blog_id = ?";
  $request = $conn->prepare($sql);
  $request->bindParam(1, $blogId);
  $request->execute();

  $conn = null;
}

function deleteBlogTag($blogId)
{
  $conn = getConnection();

  $sqlDelete = "DELETE FROM tb_blog_tag WHERE blog_id = ?";
  $request = $conn->prepare($sqlDelete);
  $request->bindParam(1, $blogId);
  $request->execute();

  $conn = null;
}

function updateBlogTitle($blogId, $newTitle)
{
  $conn = getConnection();

  $sqlUpdate = "UPDATE tb_blog SET 
					blog_title = :updateTitle
					WHERE blog_id = :blogId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("updateTitle", $newTitle);
  $request->bindParam("blogId", $blogId);
  $request->execute();

  $conn = null;
}

function updateBlogCategory($blogId, $newCategory)
{
  $conn = getConnection();

  $sqlUpdate = "UPDATE tb_blog SET 
					category_id = :categoryId
					WHERE blog_id = :blogId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("categoryId", $newCategory);
  $request->bindParam("blogId", $blogId);
  $request->execute();

  $conn = null;
}

function setBlogImageToNull($blogId)
{
  $conn = getConnection();
  $newImage = null;

  $sqlUpdate = "UPDATE tb_blog SET 
					image_url = :newImage
					WHERE blog_id = :blogId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("newImage", $newImage);
  $request->bindParam("blogId", $blogId);
  $request->execute();

  $conn = null;
}

function deleteBlogImage($blogId)
{
  $conn = getConnection();

  $sqlGetImage = "SELECT image_url FROM tb_blog WHERE blog_id = :blogId";

  $request = $conn->prepare($sqlGetImage);
  $request->bindParam("blogId", $blogId);
  $request->execute();
  $result = $request->fetch();

  if (!is_null($result['image_url'])) {
    deleteImageNews($result['image_url']);

    // Set image to null
    setBlogImageToNull($blogId);
  }

  $conn = null;
}
