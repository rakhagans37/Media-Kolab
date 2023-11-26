<?php
require_once __DIR__ . "/../helper/getConnectionMsqli.php";
require_once __DIR__ . "/../helper/cloudinary.php";
require_once __DIR__ . "/../helper/hash.php";

$conn = getConnectionMysqli();
$sql = "SELECT tb_blog.blog_title,tb_blog.date_release,tb_editor.username,tb_category_blog.category_name, tb_blog.blog_id, tb_blog.image_url FROM tb_blog inner join tb_editor on tb_blog.editor_id = tb_editor.editor_id inner join tb_category_blog on tb_blog.category_id = tb_category_blog.category_id";
$req = mysqli_query($conn, $sql);
$result = mysqli_fetch_all($req);
$sql2 = "SELECT tb_blog.blog_title,tb_blog.date_release,tb_editor.username,tb_category_blog.category_name, tb_blog.blog_id  FROM tb_blog inner join tb_editor on tb_blog.editor_id = tb_editor.editor_id inner join tb_category_blog on tb_blog.category_id = tb_category_blog.category_id ORDER BY tb_blog.views desc limit 4";
$req2 = mysqli_query($conn, $sql2);
$result2 = mysqli_fetch_all($req2);
$sql3 = "SELECT tb_media.media_title,tb_media.date_release,tb_editor.username,tb_category_media.category_name, tb_media.media_id FROM tb_media inner join tb_editor on tb_media.editor_id = tb_editor.editor_id inner join tb_category_media on tb_media.category_id = tb_category_media.category_id limit 4";
$req3 = mysqli_query($conn, $sql3);
$result3 = mysqli_fetch_all($req3);
$sql4 = "SELECT tb_event.event_title,tb_event.date_release,tb_editor.username,tb_category_event.category_name, tb_event.image_url, tb_event.event_id FROM tb_event inner join tb_editor on tb_event.editor_id = tb_editor.editor_id inner join tb_category_event on tb_event.category_id = tb_category_event.category_id ORDER BY tb_event.views desc limit 6";
$req4 = mysqli_query($conn, $sql4);
$result4 = mysqli_fetch_all($req4);
?>


<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Katen - Minimal Blog & Magazine HTML Theme</title>
	<meta name="description" content="Katen - Minimal Blog & Magazine HTML Theme">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

	<!-- STYLES -->
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/all.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/slick.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/simple-line-icons.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all">

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
					<a class="navbar-brand" href="index.html"><img src="images/logo-text.png" width="130" alt="logo" /></a>

					<div class="collapse navbar-collapse">
						<!-- menus -->
						<ul class="navbar-nav mr-auto">
							<li class="nav-item active">
								<a class="nav-link" href="index.php">Home</a>
							</li>
							<li class="nav-item"><a class="nav-link" href="listEvent.php">Event</a></li>
							<li class="nav-item"><a class="nav-link" href="listBlog.php">Blog</a></li>
							<li class="nav-item">
								<a class="nav-link" href="listMedia.php">Media</a>
							</li>
							<li class="nav-item"><a class="nav-link" href="listJobVacancies">Loker/Magang</a></li>
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

		<!-- hero section -->
		<section id="hero">

			<div class="container-xl">

				<div class="row gy-4">

					<div class="col-lg-8">

						<!-- featured post large -->
						<div class="post featured-post-lg">
							<div class="details clearfix">
								<a href="category.html" class="category-badge"><?php echo $result[0][3] ?></a>
								<h2 class="post-title"><a href="detailBlog.php?blogId=<?php echo $result[0][4] ?>"><?php echo $result[0][0] ?></a></h2>
								<ul class="meta list-inline mb-0">
									<li class="list-inline-item"><a href="detailBlog.php?blogId=<?php echo $result[0][4] ?>"><?php echo $result[0][1] ?></a></li>
									<li class="list-inline-item"><?php echo $result[0][2] ?></li>
								</ul>
							</div>
							<a href="detailBlog.php?blogId=<?php echo $result[0][4] ?>">
								<div class="thumb rounded">
									<div class="inner data-bg-image" data-bg-image=<?php echo getImageDefault(decryptPhotoProfile($result[0][5])); ?>></div>
								</div>
							</a>
						</div>

					</div>

					<div class="col-lg-4">
						<?php
						foreach ($result as $home) {
						}
						?>
						<!-- post tabs -->
						<div class="post-tabs rounded bordered">
							<!-- tab navs -->
							<ul class="nav nav-tabs nav-pills nav-fill" id="postsTab" role="tablist">
								<li class="nav-item" role="presentation"><button aria-controls="popular" aria-selected="true" class="nav-link active" data-bs-target="#popular" data-bs-toggle="tab" id="popular-tab" role="tab" type="button">Popular</button></li>
								<li class="nav-item" role="presentation"><button aria-controls="recent" aria-selected="false" class="nav-link" data-bs-target="#recent" data-bs-toggle="tab" id="recent-tab" role="tab" type="button">Recent</button></li>
							</ul>
							<!-- tab contents -->
							<div class="tab-content" id="postsTabContent">
								<!-- loader -->
								<div class="lds-dual-ring"></div>
								<!-- popular posts -->
								<div aria-labelledby="popular-tab" class="tab-pane fade show active" id="popular" role="tabpanel">
									<!-- post -->
									<?php
									foreach ($result2 as $data) {
										$blog_title = $data[0];
										$date_release = $data[1];
										$blogId = $data[4];
										echo <<<Buat
									<div class="post post-list-sm circle">
									<div class="thumb circle">
										<a href="detailBlog.php?blogId=$blogId">
											<div class="inner">
												<img src="images/posts/tabs-2.jpg" alt="post-title" />
											</div>
										</a>
									</div>
									<div class="details clearfix">
										<h6 class="post-title my-0"><a href="detailBlog.php?blogId=$blogId">$blog_title</a></h6>
										<ul class="meta list-inline mt-1 mb-0">
											<li class="list-inline-item">$date_release</li>
										</ul>
									</div>
									</div>
									Buat;
									}
									?>
								</div>
								<!-- recent posts -->
								<div aria-labelledby="recent-tab" class="tab-pane fade" id="recent" role="tabpanel">
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-2.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">An Incredibly Easy Method That Works For All</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-1.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">3 Easy Ways To Make Your iPhone Faster</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-4.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">15 Unheard Ways To Achieve Greater Walker</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-3.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">10 Ways To Immediately Start Selling Furniture</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>

		</section>

		<!-- section main content -->
		<section class="main-content">
			<div class="container-xl">

				<div class="row gy-4">

					<div class="col-lg-8">

						<!-- section header -->
						<div class="section-header">
							<h3 class="section-title">Media</h3>
							<img src="images/wave.svg" class="wave" alt="wave" />
						</div>

						<div class="padding-30 rounded bordered">
							<div class="row gy-5">
								<div class="col-sm-6">
									<!-- post -->
									<div class="post">
										<div class="thumb rounded">
											<a href="category.html" class="category-badge position-absolute"><?php echo $result3[0][3] ?></a>
											<span class="post-format">
												<i class="icon-picture"></i>
											</span>
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/editor-lg.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<ul class="meta list-inline mt-4 mb-0">
											<li class="list-inline-item"><a href="#"><img src="images/other/author-sm.png" class="author" alt="author" /><?php echo $result3[0][2] ?></a></li>
											<li class="list-inline-item"><?php echo $result3[0][1] ?></li>
										</ul>
										<h5 class="post-title mb-3 mt-3"><a href="blog-single.html"><?php echo $result3[0][0] ?></a></h5>
									</div>
								</div>
								<div class="col-sm-6">
									<?php
									foreach ($result3 as $file) {
										echo <<<Buat
											<div class="post post-list-sm square">
											<div class="thumb rounded">
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/posts/editor-sm-1.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<div class="details clearfix">
												<h6 class="post-title my-0"><a href="blog-single.html">$file[0]</a></h6>
												<ul class="meta list-inline mt-1 mb-0">
													<li class="list-inline-item">$file[1]</li>
												</ul>
											</div>
											</div>
										Buat;
									}
									?>
								</div>
							</div>
						</div>

						<div class="spacer" data-height="50"></div>

						<!-- horizontal ads -->
						<div class="ads-horizontal text-md-center">
							<span class="ads-title">- Sponsored Ad -</span>
							<a href="#">
								<img src="images/ads/ad-750.png" alt="Advertisement" />
							</a>
						</div>

						<div class="spacer" data-height="50"></div>

						<!-- section header -->
						<div class="section-header">
							<h3 class="section-title">Event</h3>
							<img src="images/wave.svg" class="wave" alt="wave" />
						</div>

						<div class="padding-30 rounded bordered">
							<div class="row gy-5">
								<div class="col-sm-6">
									<!-- post -->
									<?php
									$index = 0;
									foreach ($result4 as $kunci) {
										if ($index == 0) {
											$image = getImageNews(decryptPhotoProfile($kunci[4]));
											echo <<<Buat
											<div class="post">
												<div class="thumb rounded">
													<a href="category.html" class="category-badge position-absolute">$kunci[3]</a>
													<span class="post-format">
														<i class="icon-picture"></i>
													</span>
													<a href="detailEvent.php?eventId=$kunci[5]">
														<div class="inner">
															$image
														</div>
													</a>
												</div>
												<ul class="meta list-inline mt-4 mb-0">
													<li class="list-inline-item"><a href="#">$kunci[2]</a></li>
													<li class="list-inline-item">$kunci[1]</li>
												</ul>
												<h5 class="post-title mb-3 mt-3"><a href="detailEvent.php?eventId=$kunci[5]">$kunci[0]</a></h5>
											</div>
											Buat;
										}
										if ($index > 0 and $index <= 2) {
											$image = getImageNews(decryptPhotoProfile($kunci[4]));
											echo <<<Buat
											<div class="post post-list-sm square before-seperator">
											<div class="thumb rounded">
												<a href="detailEvent.php?eventId=$kunci[5]">
													<div class="inner">
														$image
													</div>
												</a>
											</div>
											<div class="details clearfix">
												<h6 class="post-title my-0"><a href="detailEvent.php?eventId=$kunci[5]">$kunci[0]</a></h6>
												<ul class="meta list-inline mt-1 mb-0">
													<li class="list-inline-item">$kunci[1]</li>
												</ul>
											</div>
											</div>
											Buat;
										}
										$index = $index + 1;
									}
									?>
									<!-- post -->
								</div>
								<div class="col-sm-6">
									<!-- post -->
									<?php
									$number = 0;

									foreach ($result4 as $kode) {
										if ($number == 3) {
											$image = getImageNews(decryptPhotoProfile($kunci[4]));
											echo <<<Buat
											<div class="post">
												<div class="thumb rounded">
													<a href="category.html" class="category-badge position-absolute">$kode[3]</a>
													<span class="post-format">
														<i class="icon-earphones"></i>
													</span>
													<a href="blog-single.html">
														<div class="inner">
															$image
														</div>
													</a>
												</div>
												<ul class="meta list-inline mt-4 mb-0">
													<li class="list-inline-item"><a href="#">$kode[2]</a></li>
													<li class="list-inline-item">$kode[1]</li>
												</ul>
												<h5 class="post-title mb-3 mt-3"><a href="blog-single.html">$kode[0]</a></h5>
											</div>
											Buat;
										}
										if ($number > 3 and $number <= 5) {
											$image = getImageNews(decryptPhotoProfile($kunci[4]));
											echo <<<Buat
											<div class="post post-list-sm square before-seperator">
											<div class="thumb rounded">
												<a href="blog-single.html">
													<div class="inner">
														$image
													</div>
												</a>
											</div>
											<div class="details clearfix">
												<h6 class="post-title my-0"><a href="blog-single.html">$kode[0]</a></h6>
												<ul class="meta list-inline mt-1 mb-0">
													<li class="list-inline-item">$kode[1]</li>
												</ul>
											</div>
										</div>
										Buat;
										}
										$number++;
									}
									?>


								</div>
							</div>

							<div class="spacer" data-height="50"></div>

							<!-- section header -->
							<div class="section-header">
								<h3 class="section-title">Inspiration</h3>
								<img src="images/wave.svg" class="wave" alt="wave" />
								<div class="slick-arrows-top">
									<button type="button" data-role="none" class="carousel-topNav-prev slick-custom-buttons" aria-label="Previous"><i class="icon-arrow-left"></i></button>
									<button type="button" data-role="none" class="carousel-topNav-next slick-custom-buttons" aria-label="Next"><i class="icon-arrow-right"></i></button>
								</div>
							</div>

							<div class="row post-carousel-twoCol post-carousel">
								<!-- post -->
								<div class="post post-over-content col-md-6">
									<div class="details clearfix">
										<a href="category.html" class="category-badge">Inspiration</a>
										<h4 class="post-title"><a href="blog-single.html">Want To Have A More Appealing Tattoo?</a></h4>
										<ul class="meta list-inline mb-0">
											<li class="list-inline-item"><a href="#">Katen Doe</a></li>
											<li class="list-inline-item">29 March 2021</li>
										</ul>
									</div>
									<a href="blog-single.html">
										<div class="thumb rounded">
											<div class="inner">
												<img src="images/posts/inspiration-1.jpg" alt="thumb" />
											</div>
										</div>
									</a>
								</div>
								<!-- post -->
								<div class="post post-over-content col-md-6">
									<div class="details clearfix">
										<a href="category.html" class="category-badge">Inspiration</a>
										<h4 class="post-title"><a href="blog-single.html">Feel Like A Pro With The Help Of These 7 Tips</a></h4>
										<ul class="meta list-inline mb-0">
											<li class="list-inline-item"><a href="#">Katen Doe</a></li>
											<li class="list-inline-item">29 March 2021</li>
										</ul>
									</div>
									<a href="blog-single.html">
										<div class="thumb rounded">
											<div class="inner">
												<img src="images/posts/inspiration-2.jpg" alt="thumb" />
											</div>
										</div>
									</a>
								</div>
								<!-- post -->
								<div class="post post-over-content col-md-6">
									<div class="details clearfix">
										<a href="category.html" class="category-badge">Inspiration</a>
										<h4 class="post-title"><a href="blog-single.html">Your Light Is About To Stop Being Relevant</a></h4>
										<ul class="meta list-inline mb-0">
											<li class="list-inline-item"><a href="#">Katen Doe</a></li>
											<li class="list-inline-item">29 March 2021</li>
										</ul>
									</div>
									<a href="blog-single.html">
										<div class="thumb rounded">
											<div class="inner">
												<img src="images/posts/inspiration-3.jpg" alt="thumb" />
											</div>
										</div>
									</a>
								</div>
							</div>

							<div class="spacer" data-height="50"></div>

							<!-- section header -->
							<div class="section-header">
								<h3 class="section-title">Latest Posts</h3>
								<img src="images/wave.svg" class="wave" alt="wave" />
							</div>

							<div class="padding-30 rounded bordered">

								<div class="row">

									<div class="col-md-12 col-sm-6">
										<!-- post -->
										<div class="post post-list clearfix">
											<div class="thumb rounded">
												<span class="post-format-sm">
													<i class="icon-picture"></i>
												</span>
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/posts/latest-sm-1.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<div class="details">
												<ul class="meta list-inline mb-3">
													<li class="list-inline-item"><a href="#"><img src="images/other/author-sm.png" class="author" alt="author" />Katen Doe</a></li>
													<li class="list-inline-item"><a href="#">Trending</a></li>
													<li class="list-inline-item">29 March 2021</li>
												</ul>
												<h5 class="post-title"><a href="blog-single.html">The Next 60 Things To Immediately Do About Building</a></h5>
												<p class="excerpt mb-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings</p>
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
														<a href="blog-single.html"><span class="icon-options"></span></a>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-6">
										<!-- post -->
										<div class="post post-list clearfix">
											<div class="thumb rounded">
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/posts/latest-sm-2.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<div class="details">
												<ul class="meta list-inline mb-3">
													<li class="list-inline-item"><a href="#"><img src="images/other/author-sm.png" class="author" alt="author" />Katen Doe</a></li>
													<li class="list-inline-item"><a href="#">Lifestyle</a></li>
													<li class="list-inline-item">29 March 2021</li>
												</ul>
												<h5 class="post-title"><a href="blog-single.html">Master The Art Of Nature With These 7 Tips</a></h5>
												<p class="excerpt mb-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings</p>
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
														<a href="blog-single.html"><span class="icon-options"></span></a>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-6">
										<!-- post -->
										<div class="post post-list clearfix">
											<div class="thumb rounded">
												<span class="post-format-sm">
													<i class="icon-camrecorder"></i>
												</span>
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/posts/latest-sm-3.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<div class="details">
												<ul class="meta list-inline mb-3">
													<li class="list-inline-item"><a href="#"><img src="images/other/author-sm.png" class="author" alt="author" />Katen Doe</a></li>
													<li class="list-inline-item"><a href="#">Fashion</a></li>
													<li class="list-inline-item">29 March 2021</li>
												</ul>
												<h5 class="post-title"><a href="blog-single.html">Facts About Business That Will Help You Success</a></h5>
												<p class="excerpt mb-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings</p>
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
														<a href="blog-single.html"><span class="icon-options"></span></a>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-6">
										<!-- post -->
										<div class="post post-list clearfix">
											<div class="thumb rounded">
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/posts/latest-sm-4.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<div class="details">
												<ul class="meta list-inline mb-3">
													<li class="list-inline-item"><a href="#"><img src="images/other/author-sm.png" class="author" alt="author" />Katen Doe</a></li>
													<li class="list-inline-item"><a href="#">Politic</a></li>
													<li class="list-inline-item">29 March 2021</li>
												</ul>
												<h5 class="post-title"><a href="blog-single.html">Your Light Is About To Stop Being Relevant</a></h5>
												<p class="excerpt mb-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings</p>
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
														<a href="blog-single.html"><span class="icon-options"></span></a>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>
								<!-- load more button -->
								<div class="text-center">
									<button class="btn btn-simple">Load More</button>
								</div>

							</div>
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
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<span class="number">1</span>
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-1.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">3 Easy Ways To Make Your iPhone Faster</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<span class="number">2</span>
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-2.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">An Incredibly Easy Method That Works For All</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
									<!-- post -->
									<div class="post post-list-sm circle">
										<div class="thumb circle">
											<span class="number">3</span>
											<a href="blog-single.html">
												<div class="inner">
													<img src="images/posts/tabs-3.jpg" alt="post-title" />
												</div>
											</a>
										</div>
										<div class="details clearfix">
											<h6 class="post-title my-0"><a href="blog-single.html">10 Ways To Immediately Start Selling Furniture</a></h6>
											<ul class="meta list-inline mt-1 mb-0">
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
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
										<li><a href="#">Lifestyle</a><span>(5)</span></li>
										<li><a href="#">Inspiration</a><span>(2)</span></li>
										<li><a href="#">Fashion</a><span>(4)</span></li>
										<li><a href="#">Politic</a><span>(1)</span></li>
										<li><a href="#">Trending</a><span>(7)</span></li>
										<li><a href="#">Culture</a><span>(3)</span></li>
									</ul>
								</div>

							</div>

							<!-- widget post carousel -->
							<div class="widget rounded">
								<div class="widget-header text-center">
									<h3 class="widget-title">Celebration</h3>
									<img src="images/wave.svg" class="wave" alt="wave" />
								</div>
								<div class="widget-content">
									<div class="post-carousel-widget">
										<!-- post -->
										<div class="post post-carousel">
											<div class="thumb rounded">
												<a href="category.html" class="category-badge position-absolute">How to</a>
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/widgets/widget-carousel-1.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<h5 class="post-title mb-0 mt-4"><a href="blog-single.html">5 Easy Ways You Can Turn Future Into Success</a></h5>
											<ul class="meta list-inline mt-2 mb-0">
												<li class="list-inline-item"><a href="#">Katen Doe</a></li>
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
										<!-- post -->
										<div class="post post-carousel">
											<div class="thumb rounded">
												<a href="category.html" class="category-badge position-absolute">Trending</a>
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/widgets/widget-carousel-2.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<h5 class="post-title mb-0 mt-4"><a href="blog-single.html">Master The Art Of Nature With These 7 Tips</a></h5>
											<ul class="meta list-inline mt-2 mb-0">
												<li class="list-inline-item"><a href="#">Katen Doe</a></li>
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
										<!-- post -->
										<div class="post post-carousel">
											<div class="thumb rounded">
												<a href="category.html" class="category-badge position-absolute">How to</a>
												<a href="blog-single.html">
													<div class="inner">
														<img src="images/widgets/widget-carousel-1.jpg" alt="post-title" />
													</div>
												</a>
											</div>
											<h5 class="post-title mb-0 mt-4"><a href="blog-single.html">5 Easy Ways You Can Turn Future Into Success</a></h5>
											<ul class="meta list-inline mt-2 mb-0">
												<li class="list-inline-item"><a href="#">Katen Doe</a></li>
												<li class="list-inline-item">29 March 2021</li>
											</ul>
										</div>
									</div>
									<!-- carousel arrows -->
									<div class="slick-arrows-bot">
										<button type="button" data-role="none" class="carousel-botNav-prev slick-custom-buttons" aria-label="Previous"><i class="icon-arrow-left"></i></button>
										<button type="button" data-role="none" class="carousel-botNav-next slick-custom-buttons" aria-label="Next"><i class="icon-arrow-right"></i></button>
									</div>
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
									<a href="#" class="tag">#Trending</a>
									<a href="#" class="tag">#Video</a>
									<a href="#" class="tag">#Featured</a>
									<a href="#" class="tag">#Gallery</a>
									<a href="#" class="tag">#Celebrities</a>
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
				<a href="#" class="btn btn-default btn-instagram">@Katen on Instagram</a>
				<!-- images -->
				<div class="instagram-feed d-flex flex-wrap">
					<div class="insta-item col-sm-2 col-6 col-md-2">
						<a href="#">
							<img src="images/insta/insta-1.jpg" alt="insta-title" />
						</a>
					</div>
					<div class="insta-item col-sm-2 col-6 col-md-2">
						<a href="#">
							<img src="images/insta/insta-2.jpg" alt="insta-title" />
						</a>
					</div>
					<div class="insta-item col-sm-2 col-6 col-md-2">
						<a href="#">
							<img src="images/insta/insta-3.jpg" alt="insta-title" />
						</a>
					</div>
					<div class="insta-item col-sm-2 col-6 col-md-2">
						<a href="#">
							<img src="images/insta/insta-4.jpg" alt="insta-title" />
						</a>
					</div>
					<div class="insta-item col-sm-2 col-6 col-md-2">
						<a href="#">
							<img src="images/insta/insta-5.jpg" alt="insta-title" />
						</a>
					</div>
					<div class="insta-item col-sm-2 col-6 col-md-2">
						<a href="#">
							<img src="images/insta/insta-6.jpg" alt="insta-title" />
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
							<span class="copyright">© 2021 Katen. Template by ThemeGer.</span>
						</div>

						<!-- social icons -->
						<div class="col-md-4 text-center">
							<ul class="social-icons list-unstyled list-inline mb-0">
								<li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fab fa-medium"></i></a></li>
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
			<img src="images/logo.svg" alt="Katen" />
		</div>

		<!-- menu -->
		<nav>
			<ul class="vertical-menu">
				<li class="active">
					<a href="index.php">Home</a>
				</li>
				<li><a href="listEvent.php">Event</a></li>
				<li><a href="listBlog.php">Blog</a></li>
				<li>
					<a href="listMedia.php">Media</a>
				</li>
				<li><a href="listJobVacancies">Loker/Magang</a></li>
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