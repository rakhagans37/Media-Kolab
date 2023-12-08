<?php
include('helper/getConnectionMsqli.php');
require_once __DIR__ . '/helper/cloudinary.php';
require_once __DIR__ . '/helper/hash.php';
require_once __DIR__ . '/helper/tag.php';

function printPost($redirectToDetail, $redirectToList, $queryParameter, $valueParameter, $title, $categoryName, $dateRelease, $editorUsername, $image)
{
    echo <<<CREATE
    <div class="col-sm-6">
        <!-- post -->
        <div class="post post-grid rounded bordered">
            <div class="thumb top-rounded">
                <a href="$redirectToList?category=$categoryName" class="category-badge position-absolute">$categoryName</a>
                <a href="$redirectToDetail?$queryParameter=$valueParameter">
                    <div class="inner">
                        $image
                    </div>
                </a>
            </div>
            <div class="details">
                <ul class="meta list-inline mb-0">
                    <li class="list-inline-item"><a href="$redirectToDetail?$queryParameter=$valueParameter">$editorUsername</a></li>
                    <li class="list-inline-item">$dateRelease</li>
                </ul>
                <h5 class="post-title mb-3 mt-3"><a href="$redirectToDetail?$queryParameter=$valueParameter">$title</a></h5>
            </div>
            <div class="post-bottom clearfix d-flex align-items-center">
                <div class="social-share me-auto">
                    <button class="toggle-button icon-share"></button>
                    <ul class="icons list-unstyled list-inline mb-0">
                        <li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-telegram-plane"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="far fa-envelope"></i></a></li>
                    </ul>
                </div>
                <div class="more-button float-end">
                    <a href="$redirectToDetail?$queryParameter=$valueParameter"><span class="icon-options"></span></a>
                </div>
            </div>
        </div>
    </div>
    CREATE;
}

$conn = getConnectionMysqli();

if (!isset($_GET['tagId'])) {
    http_response_code(404);
}

$tagId = $_GET['tagId'];

//Check availibility of tag id
$queryCheckTag = "SELECT * FROM tb_tag WHERE tag_id = '$tagId'";
$reqCheckTag = mysqli_query($conn, $queryCheckTag);
$resultCheckTag = mysqli_fetch_all($reqCheckTag);

//Redirect If Not Found
if (count($resultCheckTag) < 1) {
    mysqli_close($conn);
    http_response_code(404);
}
if (http_response_code() === 404) {
    header("location:notfound.php");
}


$blogWithTag = getBlogWithTag($tagId);
$eventWithTag = getEventWithTag($tagId);
$mediaWithTag = getMediaWithTag($tagId);
$jobWithTag = getJobWithTag($tagId);


//Get all tag on tb_blog_tag
$queryExploreTag = "SELECT DISTINCT tb_tag.tag_name, tb_tag.tag_id FROM tb_blog_tag INNER JOIN tb_tag ON tb_blog_tag.tag_id = tb_tag.tag_id";
$reqTag = mysqli_query($conn, $queryExploreTag);
$resultExploreTag = mysqli_fetch_all($reqTag);



//Close Connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Nguliah.id - Media Campus</title>
    <meta name="description" content="Nguliah.id - Media Campus">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="images/logoNgampus2.png">

    <!-- STYLES -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/all.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/slick.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/simple-line-icons.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/style.css?v=2" type="text/css" media="all">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- preloader -->
    <div id="preloader">
        <div class="book">
            <div class="inner">
                <div class="left"></div>
                <div class="middle"></div>
                <div class="right"></div>
            </div>
            <ul>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>

    <!-- site wrapper -->
    <div class="site-wrapper">

        <div class="main-overlay"></div>

        <!-- header -->
        <header class="header-personal">
            <div class="container-xl header-top">
                <div class="row align-items-center">

                    <div class="col-4 d-none d-md-block d-lg-block">
                        <!-- social icons -->
                        <ul class="social-icons list-unstyled list-inline mb-0">
                            <li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 text-center">
                        <!-- site logo -->
                        <a class="navbar-brand" href="index.php"><img src="images/logo-text.png" height="30" alt="logo" /></a>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <!-- header buttons -->
                        <div class="header-buttons float-md-end mt-4 mt-md-0">
                            <button class="search icon-button">
                                <i class="icon-magnifier"></i>
                            </button>
                            <button class="burger-menu icon-button ms-2 float-end float-md-none">
                                <span class="burger-icon"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <nav class="navbar navbar-expand-lg">
                <div class="container-xl">

                    <div class="collapse navbar-collapse justify-content-center centered-nav">
                        <!-- menus -->
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
        </header>

        <section class="page-header">
            <div class="container-xl">
                <div class="text-center">
                    <h1 class="mt-0 mb-2">Tag Posts</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tag Posts</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <!-- section main content -->
        <section class="main-content">
            <div class="container-xl">

                <div class="row gy-4">

                    <div class="col-lg-8">

                        <div class="row gy-4">
                            <?php
                            $totalPosts = max(count($blogWithTag), count($mediaWithTag), count($jobWithTag), count($eventWithTag)) - 1;
                            for ($i = 0; $i <= $totalPosts; $i++) {
                                if (isset($blogWithTag[$i])) {
                                    $blogId = $blogWithTag[$i]['blog_id'];
                                    $blogTitle = $blogWithTag[$i]['blog_title'];
                                    $blogRelease = $blogWithTag[$i]['date_release'];
                                    $blogEditorUsername = $blogWithTag[$i]['username'];
                                    $blogCategory = $blogWithTag[$i]['category_name'];
                                    $blogImage = getImageNews(decryptPhotoProfile($blogWithTag[$i]['image_url']));

                                    printPost("detailBlog.php", "listBlog.php", "blogId", $blogId, $blogTitle, $blogCategory, $blogRelease, $blogEditorUsername, $blogImage);
                                }

                                if (isset($eventWithTag[$i])) {
                                    $eventId = $eventWithTag[$i]['event_id'];
                                    $eventTitle = $eventWithTag[$i]['event_title'];
                                    $eventRelease = $eventWithTag[$i]['date_release'];
                                    $eventEditorUsername = $eventWithTag[$i]['username'];
                                    $eventCategory = $eventWithTag[$i]['category_name'];
                                    $eventImage = getImageNews(decryptPhotoProfile($eventWithTag[$i]['image_url']));

                                    printPost("detailEvent.php", "listEvent.php", "eventId", $eventId, $eventTitle, $eventCategory, $eventRelease, $eventEditorUsername, $eventImage);
                                }

                                if (isset($mediaWithTag[$i])) {
                                    $mediaId = $mediaWithTag[$i]['media_id'];
                                    $mediaTitle = $mediaWithTag[$i]['media_title'];
                                    $mediaRelease = $mediaWithTag[$i]['date_release'];
                                    $mediaEditorUsername = $mediaWithTag[$i]['username'];
                                    $mediaCategory = $mediaWithTag[$i]['category_name'];
                                    $mediaImage = getImageNews(decryptPhotoProfile($mediaWithTag[$i]['image_url']));

                                    printPost("detailMedia.php", "listMedia.php", "mediaId", $mediaId, $mediaTitle, $mediaCategory, $mediaRelease, $mediaEditorUsername, $mediaImage);
                                }

                                if (isset($jobWithTag[$i])) {
                                    $jobId = $jobWithTag[$i]['vacancy_id'];
                                    $jobTitle = $jobWithTag[$i]['vacancy_title'];
                                    $jobRelease = $jobWithTag[$i]['date_release'];
                                    $jobEditorUsername = $jobWithTag[$i]['username'];
                                    $jobCategory = $jobWithTag[$i]['category_name'];
                                    $jobImage = getImageNews(decryptPhotoProfile($jobWithTag[$i]['image_url']));

                                    printPost("detailJobVacancies.php", "listJobVacancies.php", "jobId", $jobId, $jobTitle, $jobCategory, $jobRelease, $jobEditorUsername, $jobImage);
                                }
                            }
                            ?>

                        </div>

                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                            </ul>
                        </nav>

                    </div>
                    <div class="col-lg-4">

                        <!-- sidebar -->
                        <div class="sidebar">
                            <!-- widget advertisement -->
                            <div class="widget no-container rounded text-md-center">
                                <span class="ads-title">- Sponsored Ad -</span>
                                <a href="#" class="widget-ads">
                                    <img src="images/ads/ad-360.png" alt="Advertisement" />
                                </a>
                            </div>

                            <!-- widget tags -->
                            <div class="widget rounded">
                                <div class="widget-header text-center">
                                    <h3 class="widget-title">Tag Clouds</h3>
                                    <img src="images/wave.svg" class="wave" alt="wave" />
                                </div>
                                <div class="widget-content">
                                    <?php
                                    foreach ($resultExploreTag as $data) {
                                        echo "<a href='listTag.php?tagId={$data[1]}' class='tag'>#{$data[0]}</a>";
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

        <!-- instagram feed -->
        <div class="instagram">
            <div class="container-xl">
                <!-- button -->
                <a href="https://www.instagram.com/kolabfit/" class="btn btn-default btn-instagram">@Ko+Lab on Instagram</a>
                <!-- images -->
                <div class="instagram-feed d-flex flex-wrap">
                    <div class="insta-item col-sm-2 col-6 col-md-2">
                        <a href="#">
                            <img src="images/instagram/instagram-content1.jpg" alt="insta-title" />
                        </a>
                    </div>
                    <div class="insta-item col-sm-2 col-6 col-md-2">
                        <a href="#">
                            <img src="images/instagram/instagram-content2.jpg" alt="insta-title" />
                        </a>
                    </div>
                    <div class="insta-item col-sm-2 col-6 col-md-2">
                        <a href="#">
                            <img src="images/instagram/instagram-content3.jpg" alt="insta-title" />
                        </a>
                    </div>
                    <div class="insta-item col-sm-2 col-6 col-md-2">
                        <a href="#">
                            <img src="images/instagram/instagram-content4.jpg" alt="insta-title" />
                        </a>
                    </div>
                    <div class="insta-item col-sm-2 col-6 col-md-2">
                        <a href="#">
                            <img src="images/instagram/instagram-content5.jpg" alt="insta-title" />
                        </a>
                    </div>
                    <div class="insta-item col-sm-2 col-6 col-md-2">
                        <a href="#">
                            <img src="images/instagram/instagram-content6.jpg" alt="insta-title" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <footer>
            <div class="container-xl">
                <div class="footer-inner">
                    <div class="row d-flex align-items-center gy-4">
                        <!-- copyright text -->
                        <div class="col-md-4">
                            <span class="copyright">© 2023 Nguliah.id</span>
                        </div>

                        <!-- social icons -->
                        <div class="col-md-4 text-center">
                            <ul class="social-icons list-unstyled list-inline mb-0">
                                <li class="list-inline-item"><a href="https://www.instagram.com/kolabfit/"><i class="fab fa-instagram"></i></a></li>
                                <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>

                        <!-- go to top button -->
                        <div class="col-md-4">
                            <a href="#" id="return-to-top" class="float-md-end"><i class="icon-arrow-up"></i>Back to Top</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div><!-- end site wrapper -->

    <!-- search popup area -->
    <div class="search-popup">
        <!-- close button -->
        <button type="button" class="btn-close" aria-label="Close"></button>
        <!-- content -->
        <div class="search-content">
            <div class="text-center">
                <h3 class="mb-4 mt-0">Press ESC to close</h3>
            </div>
            <!-- form -->
            <form class="d-flex search-form">
                <input class="form-control me-2" type="search" placeholder="Search and press enter ..." aria-label="Search">
                <button class="btn btn-default btn-lg" type="submit"><i class="icon-magnifier"></i></button>
            </form>
        </div>
    </div>

    <!-- canvas menu -->
    <div class="canvas-menu d-flex align-items-end flex-column">
        <!-- close button -->
        <button type="button" class="btn-close" aria-label="Close"></button>

        <!-- logo -->
        <div class="logo">
            <img src="images/logo-text.png" alt="Nguliah.id" />
        </div>

        <!-- menu -->
        <nav>
            <ul class="vertical-menu">
                <li>
                    <a href="index.html" class="active">Home</a>
                </li>
            </ul>
        </nav>

        <!-- social icons -->
        <ul class="social-icons list-unstyled list-inline mb-0 mt-auto w-100">
            <li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
            <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
        </ul>
    </div>

    <!-- JAVA SCRIPTS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/jquery.sticky-sidebar.min.js"></script>
    <script src="js/custom.js"></script>

</body>

</html>