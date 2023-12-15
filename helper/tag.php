<?php
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/increasePopularity.php";
require_once __DIR__ . "/hash.php";

function insertBlogTag(array $arrayTag, $blogId)
{
    foreach ($arrayTag as $key => $value) {
        $conn = getConnection();
        $sql = "SELECT * FROM tb_tag WHERE tag_name LIKE ?";

        $request = $conn->prepare($sql);
        $request->bindParam(1, $value);
        $request->execute();

        if ($result = $request->fetch()) {
            $tagId = $result['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_blog_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $blogId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 1;

            $sqlInsert  = "INSERT INTO tb_tag VALUES(?,?,?)";
            $requestInsertTag = $conn->prepare($sqlInsert);
            $requestInsertTag->bindParam(1, $newIdTag);
            $requestInsertTag->bindParam(2, $value);
            $requestInsertTag->bindParam(3, $popularity);
            $requestInsertTag->execute();

            $sqlInsert = "INSERT INTO tb_blog_tag VALUES(?,?)";
            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $blogId);
            $requestInsert->bindParam(2, $newIdTag);
            $requestInsert->execute();
        }

        $conn = null;
    }
}


function insertMediaTag(array $arrayTag, $mediaId)
{
    foreach ($arrayTag as $key => $value) {
        $conn = getConnection();
        $sql = "SELECT * FROM tb_tag WHERE tag_name LIKE ?";

        $request = $conn->prepare($sql);
        $request->bindParam(1, $value);
        $request->execute();

        if ($result = $request->fetch()) {
            $tagId = $result['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_media_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $mediaId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 1;

            $sqlInsert  = "INSERT INTO tb_tag VALUES(?,?,?)";
            $requestInsertTag = $conn->prepare($sqlInsert);
            $requestInsertTag->bindParam(1, $newIdTag);
            $requestInsertTag->bindParam(2, $value);
            $requestInsertTag->bindParam(3, $popularity);
            $requestInsertTag->execute();

            $sqlInsert = "INSERT INTO tb_media_tag VALUES(?,?)";
            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $mediaId);
            $requestInsert->bindParam(2, $newIdTag);
            $requestInsert->execute();
        }

        $conn = null;
    }
}

function insertEventTag(array $arrayTag, $eventId)
{
    foreach ($arrayTag as $key => $value) {
        $conn = getConnection();
        $sql = "SELECT * FROM tb_tag WHERE tag_name LIKE ?";

        $request = $conn->prepare($sql);
        $request->bindParam(1, $value);
        $request->execute();

        if ($result = $request->fetch()) {
            $tagId = $result['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_event_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $eventId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 1;

            $sqlInsert  = "INSERT INTO tb_tag VALUES(?,?,?)";
            $requestInsertTag = $conn->prepare($sqlInsert);
            $requestInsertTag->bindParam(1, $newIdTag);
            $requestInsertTag->bindParam(2, $value);
            $requestInsertTag->bindParam(3, $popularity);
            $requestInsertTag->execute();

            $sqlInsert = "INSERT INTO tb_event_tag VALUES(?,?)";
            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $eventId);
            $requestInsert->bindParam(2, $newIdTag);
            $requestInsert->execute();
        }

        $conn = null;
    }
}

function insertJobTag(array $arrayTag, $jobId)
{
    foreach ($arrayTag as $key => $value) {
        $conn = getConnection();
        $sql = "SELECT * FROM tb_tag WHERE tag_name LIKE ?";

        $request = $conn->prepare($sql);
        $request->bindParam(1, $value);
        $request->execute();

        if ($result = $request->fetch()) {
            $tagId = $result['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_job_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $jobId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 1;

            $sqlInsert  = "INSERT INTO tb_tag VALUES(?,?,?)";
            $requestInsertTag = $conn->prepare($sqlInsert);
            $requestInsertTag->bindParam(1, $newIdTag);
            $requestInsertTag->bindParam(2, $value);
            $requestInsertTag->bindParam(3, $popularity);
            $requestInsertTag->execute();

            $sqlInsert = "INSERT INTO tb_job_tag VALUES(?,?)";
            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $jobId);
            $requestInsert->bindParam(2, $newIdTag);
            $requestInsert->execute();
        }

        $conn = null;
    }
}

function separateTag($stringTag)
{
    $arrayTag = explode(",", $stringTag);
    return $arrayTag;
}

function getBlogWithTag($tagId)
{
    $conn = getConnection();
    $sql = "SELECT tb_blog.blog_id, tb_blog.blog_title, tb_blog.date_release, tb_blog.image_url, tb_editor.username, tb_category_blog.category_name FROM tb_blog INNER JOIN tb_editor ON tb_blog.editor_id = tb_editor.editor_id INNER JOIN tb_category_blog ON tb_blog.category_id = tb_category_blog.category_id INNER JOIN tb_blog_tag ON tb_blog.blog_id = tb_blog_tag.blog_id WHERE tb_blog_tag.tag_id = ? ORDER BY RAND()";

    $request = $conn->prepare($sql);
    $request->bindParam(1, $tagId);
    $request->execute();

    $conn = null;

    $result = $request->fetchAll();
    return $result;
}

function getEventWithTag($tagId)
{
    $conn = getConnection();
    $sql = "SELECT tb_event.event_id, tb_event.event_title, tb_event.date_release, tb_event.image_url, tb_editor.username, tb_category_event.category_name FROM tb_event INNER JOIN tb_editor ON tb_event.editor_id = tb_editor.editor_id INNER JOIN tb_category_event ON tb_event.category_id = tb_category_event.category_id INNER JOIN tb_event_tag ON tb_event.event_id = tb_event_tag.event_id WHERE tb_event_tag.tag_id = ? ORDER BY RAND()";

    $request = $conn->prepare($sql);
    $request->bindParam(1, $tagId);
    $request->execute();

    $conn = null;

    $result = $request->fetchAll();
    return $result;
}

function getMediaWithTag($tagId)
{
    $conn = getConnection();
    $sql = "SELECT tb_media.media_id, tb_media.media_title, tb_media.date_release, tb_media.image_url, tb_editor.username, tb_category_media.category_name FROM tb_media INNER JOIN tb_editor ON tb_media.editor_id = tb_editor.editor_id INNER JOIN tb_category_media ON tb_media.category_id = tb_category_media.category_id INNER JOIN tb_media_tag ON tb_media.media_id = tb_media_tag.media_id WHERE tb_media_tag.tag_id = ? ORDER BY RAND()";

    $request = $conn->prepare($sql);
    $request->bindParam(1, $tagId);
    $request->execute();

    $conn = null;

    $result = $request->fetchAll();
    return $result;
}

function getJobWithTag($tagId)
{
    $conn = getConnection();
    $sql = "SELECT tb_job_vacancies.vacancy_id, tb_job_vacancies.vacancy_title, tb_job_vacancies.date_release, tb_job_vacancies.image_url, tb_editor.username, tb_category_job_vacancy.category_name FROM tb_job_vacancies INNER JOIN tb_editor ON tb_job_vacancies.editor_id = tb_editor.editor_id INNER JOIN tb_category_job_vacancy ON tb_job_vacancies.category_id = tb_category_job_vacancy.category_id INNER JOIN tb_job_tag ON tb_job_vacancies.vacancy_id = tb_job_tag.vacancy_id WHERE tb_job_tag.tag_id = ? ORDER BY RAND()";

    $request = $conn->prepare($sql);
    $request->bindParam(1, $tagId);
    $request->execute();

    $conn = null;

    $result = $request->fetchAll();
    return $result;
}

function getAllTag()
{
    $conn = getConnection();

    $sql = "SELECT * FROM tb_tag";
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
