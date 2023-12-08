<?php
require_once __DIR__ . "/helper/getConnectionMsqli.php";
require_once __DIR__ . "/helper/increasePopularity.php";
require_once __DIR__ . "/helper/cloudinary.php";
require_once __DIR__ . "/helper/hash.php";

if (!isset($_GET['jobId'])) {
	http_response_code(404);
}
if (http_response_code() === 404) {
	header("location:notfound.php");
}

$jobId = $_GET["jobId"];
increaseJobVacancies($jobId);

$conn = getConnectionMysqli();

// Fetch content of the job vacancies
$query = "SELECT tb_job_vacancies.vacancy_id, tb_job_vacancies.vacancy_title, tb_job_vacancies.date_release, tb_job_vacancies.image_url, tb_job_vacancies.company_name, tb_editor.username, tb_job_vacancies.vacancy_content, tb_editor.profile_photo, tb_editor.description, tb_job_vacancies.logo, tb_job_vacancies.vacancy_requirement FROM tb_job_vacancies INNER JOIN tb_editor ON tb_job_vacancies.editor_id=tb_editor.editor_id WHERE tb_job_vacancies.vacancy_id='$jobId'";
$result = mysqli_query($conn, $query);
$request = mysqli_fetch_array($result);

//Redirect If Not Found
if (is_null($request)) {
	mysqli_close($conn);
	http_response_code(404);
}
if (http_response_code() === 404) {
	header("location:notfound.php");
}

// Fetch popular job vacancies
$query2 = "SELECT vacancy_id, vacancy_title, date_release, views, logo FROM tb_job_vacancies ORDER BY views desc limit 3";
$data = mysqli_query($conn, $query2);
$result2 = mysqli_fetch_all($data);

// Fetch category of job vacancies
$query3 = "SELECT tb_job_vacancies.category_id, tb_category_job_vacancy.category_name, COUNT(tb_job_vacancies.category_id) AS jumlah_kategori FROM tb_category_job_vacancy INNER JOIN tb_job_vacancies ON tb_category_job_vacancy.category_id = tb_job_vacancies.category_id GROUP BY tb_job_vacancies.category_id";
$data2 = mysqli_query($conn, $query3);
$result3 = mysqli_fetch_all($data2);

// Fetch amount of each category on job vacancies
$query4 = "SELECT COUNT(vacancy_id) AS jumlah_event FROM tb_job_vacancies";
$data3 = mysqli_query($conn, $query4);
$result4 = mysqli_fetch_all($data3);

// Fetch tag
$queryTag = "SELECT tb_job_tag.tag_id, tb_tag.tag_name FROM tb_job_tag INNER JOIN tb_tag ON tb_tag.tag_id = tb_job_tag.tag_id WHERE tb_job_tag.vacancy_id = '$jobId'";
$dataTag = mysqli_query($conn, $queryTag);
$resultTag = mysqli_fetch_all($dataTag);

//Get all tag on tb_job_tag
$queryExploreTag = "SELECT DISTINCT tb_tag.tag_name, tb_tag.tag_id FROM tb_job_tag INNER JOIN tb_tag ON tb_job_tag.tag_id = tb_tag.tag_id";
$reqTag = mysqli_query($conn, $queryExploreTag);
$resultExploreTag = mysqli_fetch_all($reqTag);

//Get Editor Profile Photo
if (!is_null($companyLogoUrl = $request['logo'])) {
	$companyLogo = getImageProfile(decryptPhotoProfile($companyLogoUrl), 35);
	$companyLogo =  substr_replace($companyLogo, " class='author'", 4, 0);
} else {
	$companyLogo = "<img src='../assets/images/profiles/profile-1.png' class='author' width='35' height='35' alt='author' />";
}

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
		<header class="header-default">
			<nav class="navbar navbar-expand-lg">
				<div class="container-xl">
					<!-- site logo -->
					<!-- site logo -->
					<a class="navbar-brand" href="index.php">
						<img src="images/logo-text.png" alt="logo" width="160" />
					</a>

					<div class="collapse navbar-collapse">
						<!-- menus -->
						<ul class="navbar-nav mr-auto">
							<li class="nav-item">
								<a class="nav-link" href="index.php">Home</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="listEvent.php">Event</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="listBlog.php">Blog</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="listMedia.php">Media</a>
							</li>
							<li class="nav-item active">
								<a class="nav-link" href="listJobVacancies.php">Loker/Magang</a>
							</li>
						</ul>
					</div>

					<!-- header right section -->
					<div class="header-right">
						<!-- social icons -->
						<ul class="social-icons list-unstyled list-inline mb-0">
							<li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fab fa-medium"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
						</ul>
						<!-- header buttons -->
						<div class="header-buttons">
							<button class="search icon-button">
								<i class="icon-magnifier"></i>
							</button>
							<button class="burger-menu icon-button">
								<span class="burger-icon"></span>
							</button>
						</div>
					</div>
				</div>
			</nav>
		</header>

		<!-- section main content -->
		<section class="main-content mt-3">
			<div class="container-xl">

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Loker/Magang</a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $request["vacancy_title"] ?></li>
					</ol>
				</nav>

				<div class="row gy-4">

					<div class="col-lg-8">
						<!-- post single -->
						<div class="post post-single">
							<!-- post header -->
							<div class="post-header">
								<h1 class="title mt-0 mb-3"><?php echo $request["vacancy_title"] ?></h1>
								<ul class="meta list-inline mb-0">
									<li class="list-inline-item">
										<a href="#">
											<?php
											echo $companyLogo;
											echo $request["company_name"];
											?>
										</a>
									</li>
									<li class="list-inline-item"><a href="#"><?php echo $request["username"] ?></a></li>
									<li class="list-inline-item"><?php echo $request["date_release"] ?></li>
								</ul>
							</div>
							<!-- featured image -->
							<div class="featured-image">
								<?php
								$image = getImageNews(decryptPhotoProfile($request['image_url']));
								echo $image;
								?>
							</div>
							<!-- post content -->
							<div class="post-content clearfix">
								<?= $request['vacancy_content'] ?>

								<blockquote>
									<?= $request['vacancy_requirement'] ?>
								</blockquote>
							</div>
							<!-- post bottom section -->
							<div class="post-bottom">
								<div class="row d-flex align-items-center">
									<div class="col-md-6 col-12 text-center text-md-start">
										<!-- tags -->
										<?php
										foreach ($resultTag as $data) {
											echo "<a href='listTag.php?tagId={$data[0]}' class='tag'>#{$data[1]}</a>";
										}
										?>
									</div>
									<div class="col-md-6 col-12">
										<!-- social icons -->
										<ul class="social-icons list-unstyled list-inline mb-0 float-md-end">
											<li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
											<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
											<li class="list-inline-item"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
											<li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
											<li class="list-inline-item"><a href="#"><i class="fab fa-telegram-plane"></i></a></li>
											<li class="list-inline-item"><a href="#"><i class="far fa-envelope"></i></a></li>
										</ul>
									</div>
								</div>
							</div>

						</div>

						<div class="spacer" data-height="50"></div>

						<div class="about-author padding-30 rounded">
							<div>
								<h4 class="name"><?php echo $request['username'] ?></h4>
								<p><?php echo $request['description'] ?></p>
								<!-- social icons -->
								<ul class="social-icons list-unstyled list-inline mb-0">
									<li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
									<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
									<li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
									<li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
									<li class="list-inline-item"><a href="#"><i class="fab fa-medium"></i></a></li>
									<li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
								</ul>
							</div>
						</div>

						<div class="spacer" data-height="50"></div>

						<!-- section header -->
						<div class="section-header">
							<h3 class="section-title">Comments (3)</h3>
							<img src="images/wave.svg" class="wave" alt="wave" />
						</div>
						<!-- post comments -->
						<div class="comments bordered padding-30 rounded">

							<ul class="comments">
								<!-- comment item -->
								<li class="comment rounded">
									<div class="thumb">
										<img src="images/other/comment-1.png" alt="John Doe" />
									</div>
									<div class="details">
										<h4 class="name"><a href="#">John Doe</a></h4>
										<span class="date">Jan 08, 2021 14:41 pm</span>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vitae odio ut tortor fringilla cursus sed quis odio.</p>
										<a href="#" class="btn btn-default btn-sm">Reply</a>
									</div>
								</li>
								<!-- comment item -->
								<li class="comment child rounded">
									<div class="thumb">
										<img src="images/other/comment-2.png" alt="John Doe" />
									</div>
									<div class="details">
										<h4 class="name"><a href="#">Helen Doe</a></h4>
										<span class="date">Jan 08, 2021 14:41 pm</span>
										<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>
										<a href="#" class="btn btn-default btn-sm">Reply</a>
									</div>
								</li>
								<!-- comment item -->
								<li class="comment rounded">
									<div class="thumb">
										<img src="images/other/comment-3.png" alt="John Doe" />
									</div>
									<div class="details">
										<h4 class="name"><a href="#">Anna Doe</a></h4>
										<span class="date">Jan 08, 2021 14:41 pm</span>
										<p>Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia.</p>
										<a href="#" class="btn btn-default btn-sm">Reply</a>
									</div>
								</li>
							</ul>
						</div>

						<div class="spacer" data-height="50"></div>

						<!-- section header -->
						<div class="section-header">
							<h3 class="section-title">Leave Comment</h3>
							<img src="images/wave.svg" class="wave" alt="wave" />
						</div>
						<!-- comment form -->
						<div class="comment-form rounded bordered padding-30">

							<form id="comment-form" class="comment-form" method="post">

								<div class="messages"></div>

								<div class="row">

									<div class="column col-md-12">
										<!-- Comment textarea -->
										<div class="form-group">
											<textarea name="InputComment" id="InputComment" class="form-control" rows="4" placeholder="Your comment here..." required="required"></textarea>
										</div>
									</div>

									<div class="column col-md-6">
										<!-- Email input -->
										<div class="form-group">
											<input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Email address" required="required">
										</div>
									</div>

									<div class="column col-md-6">
										<!-- Name input -->
										<div class="form-group">
											<input type="text" class="form-control" name="InputWeb" id="InputWeb" placeholder="Website" required="required">
										</div>
									</div>

									<div class="column col-md-12">
										<!-- Email input -->
										<div class="form-group">
											<input type="text" class="form-control" id="InputName" name="InputName" placeholder="Your name" required="required">
										</div>
									</div>

								</div>

								<button type="submit" name="submit" id="submit" value="Submit" class="btn btn-default">Submit</button><!-- Submit Button -->

							</form>
						</div>
					</div>

					<div class="col-lg-4">

						<!-- sidebar -->
						<div class="sidebar">
							<!-- widget popular posts -->
							<div class="widget rounded">
								<div class="widget-header text-center">
									<h3 class="widget-title">Popular Posts</h3>
									<img src="images/wave.svg" class="wave" alt="wave" />
								</div>
								<div class="widget-content">
									<?php
									$number = 1;
									foreach ($result2 as $data) {
										$popularJobId = $data[0];
										$popularJobTitle = $data[1];
										$popularJobDate = $data[2];
										echo <<<Buat
											<div class="post post-list-sm circle">
											<div class="thumb circle">
												<span class="number">$number</span>
												<a href="detailJobVacancies.php?jobId=$popularJobId">
													<div class="inner">
														<img src="images/posts/tabs-1.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<div class="details clearfix">
												<h6 class="post-title my-0"><a href="detailJobVacancies.php?jobId=$popularJobId">$popularJobTitle</a></h6>
												<ul class="meta list-inline mt-1 mb-0">
													<li class="list-inline-item">$popularJobDate</li>
												</ul>
											</div>
										</div>
										Buat;
										$number++;
									}
									?>
								</div>
							</div>

							<!-- widget categories -->
							<div class="widget rounded">
								<div class="widget-header text-center">
									<h3 class="widget-title">Explore Topics</h3>
									<img src="images/wave.svg" class="wave" alt="wave" />
								</div>
								<div class="widget-content">
									<ul class="list">
										<?php
										$jumlah = $result4[0][0];
										echo "<li><a href='listJobVacancies.php'>ALL</a><span>($jumlah)</span></li>";
										foreach ($result3 as $isi) {
											$categoryName = $isi[1];
											$jumlah = $isi[2];
											echo "<li><a href='listJobVacancies.php?category=$categoryName'>$categoryName</a><span>($jumlah)</span></li>";
										}
										?>

									</ul>
								</div>

							</div>
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
					<a href="index.php">Home</a>
				</li>
				<li>
					<a href="listEvent.php">Event</a>
				</li>
				<li>
					<a href="listBlog.php">Blog</a>
				</li>
				<li>
					<a href="listMedia.php">Media</a>
				</li>
				<li class="active">
					<a href="listJobVacancies.php">Loker/Magang</a>
				</li>
			</ul>
		</nav>

		<!-- social icons -->
		<ul class="social-icons list-unstyled list-inline mb-0 mt-auto w-100">
			<li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
			<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
			<li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
			<li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
			<li class="list-inline-item"><a href="#"><i class="fab fa-medium"></i></a></li>
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