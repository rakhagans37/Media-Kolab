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

        if ($result = $request->fetchAll()) {
            $tagId = $result['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_blog_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $blogId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 0;

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

        if ($result = $request->fetchAll()) {
            $tagId = $result[0]['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_media_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $mediaId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 0;

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

        if ($result = $request->fetchAll()) {
            $tagId = $result[0]['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_event_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $eventId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 0;

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

        if ($result = $request->fetchAll()) {
            $tagId = $result[0]['tag_id'];
            increaseTag($tagId);
            $sqlInsert = "INSERT INTO tb_job_tag VALUES(?,?)";

            $requestInsert = $conn->prepare($sqlInsert);
            $requestInsert->bindParam(1, $jobId);
            $requestInsert->bindParam(2, $tagId);
            $requestInsert->execute();
        } else {
            $newIdTag = generateIdTag();
            $popularity = 0;

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
    }
}

function separateTag($stringTag)
{
    $arrayTag = explode(",", $stringTag);
    return $arrayTag;
}
