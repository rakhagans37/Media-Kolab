<?php
require_once __DIR__ . "/getConnectionMsqli.php";
function getRandomCat()
{
    //Get random number
    $number = random_int(1, 4);
    return $number;
}

function getBlogCategory()
{
    $conn = getConnectionMysqli();

    $queryBlogCat = "SELECT tb_blog.category_id, tb_category_blog.category_name, COUNT(tb_blog.category_id) AS jumlah_kategori FROM tb_category_blog INNER JOIN tb_blog ON tb_category_blog.category_id = tb_blog.category_id GROUP BY tb_blog.category_id";
    $requestBlogCat = mysqli_query($conn, $queryBlogCat);
    $resultBlogCat = mysqli_fetch_all($requestBlogCat);

    return $resultBlogCat;
}

function getMediaCategory()
{
    $conn = getConnectionMysqli();

    $queryMediaCat = "SELECT tb_media.category_id, tb_category_media.category_name, COUNT(tb_media.category_id) AS jumlah_kategori FROM tb_category_media INNER JOIN tb_media ON tb_category_media.category_id = tb_media.category_id GROUP BY tb_media.category_id";
    $requestMediaCat = mysqli_query($conn, $queryMediaCat);
    $resultMediaCat = mysqli_fetch_all($requestMediaCat);

    return $resultMediaCat;
}

function getJobCategory()
{
    $conn = getConnectionMysqli();

    $queryJobCat = "SELECT tb_job_vacancies.category_id, tb_category_job_vacancy.category_name, COUNT(tb_job_vacancies.category_id) AS jumlah_kategori FROM tb_category_job_vacancy INNER JOIN tb_job_vacancies ON tb_category_job_vacancy.category_id = tb_job_vacancies.category_id GROUP BY tb_job_vacancies.category_id";
    $requestJobCat = mysqli_query($conn, $queryJobCat);
    $resultJobCat = mysqli_fetch_all($requestJobCat);

    return $resultJobCat;
}

function getEventCategory()
{
    $conn = getConnectionMysqli();

    $queryEventCat = "SELECT tb_event.category_id, tb_category_event.category_name, COUNT(tb_event.category_id) AS jumlah_kategori FROM tb_category_event INNER JOIN tb_event ON tb_category_event.category_id = tb_event.category_id GROUP BY tb_event.category_id";
    $requestEventCat = mysqli_query($conn, $queryEventCat);
    $resultEventCat = mysqli_fetch_all($requestEventCat);

    return $resultEventCat;
}

function getCategoryBlog()
{
    $conn = getConnection();

    $sqlGet = "SELECT * FROM tb_category_blog";
    $request = $conn->query($sqlGet);

    if ($result = $request->fetchAll(PDO::FETCH_ASSOC)) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function getCategoryEvent()
{
    $conn = getConnection();

    $sqlGet = "SELECT * FROM tb_category_event";
    $request = $conn->query($sqlGet);

    if ($result = $request->fetchAll(PDO::FETCH_ASSOC)) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function getCategoryMedia()
{
    $conn = getConnection();

    $sqlGet = "SELECT * FROM tb_category_media";
    $request = $conn->query($sqlGet);

    if ($result = $request->fetchAll(PDO::FETCH_ASSOC)) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function getCategoryJob()
{
    $conn = getConnection();

    $sqlGet = "SELECT * FROM tb_category_job_vacancy";
    $request = $conn->query($sqlGet);

    if ($result = $request->fetchAll(PDO::FETCH_ASSOC)) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}
