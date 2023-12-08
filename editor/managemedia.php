<?php
require_once __DIR__ . "/../helper/hash.php";
require_once __DIR__ . "/../helper/getConnection.php";
require_once __DIR__ . "/../helper/getConnectionMsqli.php";
require_once __DIR__ . "/../helper/mediafunctions.php";
require_once __DIR__ . '/../helper/validateLoginEditor.php';
require_once __DIR__ . '/../helper/cloudinary.php';

// Get connections
$conn = getConnectionMysqli();
$dbConnection = getConnection();

// Fetching data from the tb_tag table
$query = $dbConnection->query("SELECT * FROM tb_tag");
$tags = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetching data from the tb_category table
$query = $dbConnection->query("SELECT * FROM tb_category_media");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

// Post Detection
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Script Create New Media
	if (isset($_POST['createMedia'])) {
		$mediaId = generateIdMedia();
		$thumbName = random_int(0, PHP_INT_MAX) . date("dmYHis") . $mediaId;
		$hashedThumbnail = hashPhotoProfile($thumbName);
		$createTitle = $_POST['createTitle'];
		$content = $_POST['createContent'];
		$currentDate = date("Y-m-d");
		if ($_POST['createImageUrl']) {
			$imageCreateUrl = $_POST['createImageUrl'];
		} else {
			$imageCreateUrl = "";
		}
		if ($_POST['createVideoUrl']) {
			$videoCreateUrl = $_POST['createVideoUrl'];
		} else {
			$videoCreateUrl = "";
		}
		$tagId = $_POST['taginput'];
		$categoryId = $_POST['catinput'];

		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sqlCreate = "INSERT INTO tb_media (media_id, thumbnail, media_title, media_content, date_release, image_url, video_url, views, tag_id, category_id, editor_id)
				VALUES (:mediaId, :thumbnail, :createTitle, :content, :currentDate, :image_url, :video_url, 0, :tagId, :categoryId, :editorId)";
		$request = $dbConnection->prepare($sqlCreate);

		$request->bindParam('mediaId', $mediaId);
		$request->bindParam('thumbnail', $hashedThumbnail);
		$request->bindParam('createTitle', $createTitle);
		$request->bindParam('content', $content);
		$request->bindParam('currentDate', $currentDate);
		$request->bindParam('image_url', $imageCreateUrl);
		$request->bindParam('video_url', $videoCreateUrl);
		$request->bindParam('tagId', $tagId);
		$request->bindParam('categoryId', $categoryId);
		$request->bindParam('editorId', $editorId);
		$request->execute();

		$success = uploadImageMedia($thumbName, $_FILES['thumbnail']);
		if ($success) {
			echo '<script>alert("Data berhasil ditambahkan!");</script>';
		} else {
			echo '<script>alert("Data gagal ditambahkan!");</script>';
		}
	}

	// Script Update Media
	if (isset($_POST['updateButton'])) {
		try {
			$mediaId = $_POST['mediaId'];
			if (isset($_FILES['thumbnailUpdate']) && $_FILES['thumbnailUpdate'] && !empty($_FILES['thumbnailUpdate']) && $_FILES['thumbnailUpdate']['error'] !== UPLOAD_ERR_NO_FILE) {
				try {
					$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$fetchThumbnailName = $dbConnection->prepare("SELECT thumbnail FROM tb_media WHERE media_id = :mediaId");
					$fetchThumbnailName->bindParam(':mediaId', $mediaId);
					$fetchThumbnailName->execute();
					$result = $fetchThumbnailName->fetch(PDO::FETCH_ASSOC);

					if ($result) {
						$thumbnailName = $result['thumbnail'];
					} else {
						echo '<script>alert("Tidak ada Thumbnail!");</script>';
					}
					$success = uploadImageMedia($thumbnailName, $_FILES['thumbnailUpdate']);
					if ($success) {
						echo '<script>alert("Data berhasil diupdate!");</script>';
					} else {
						echo '<script>alert("Data gagal diupdate!");</script>';
					}
				} catch (PDOException $e) {
					echo '<script>alert("Tidak ada koneksi!");</script>';
				}
			}
			$updateTitle = $_POST['updateTitle'];
			$updateContent = $_POST['updateContent'];
			$currentDate = date("Y-m-d");
			if ($_POST['updateImageUrl']) {
				$imageUpdateUrl = $_POST['updateImageUrl'];
			} else {
				$imageUpdateUrl = NULL;
			}
			if ($_POST['updateVideoUrl']) {
				$videoUpdateUrl = $_POST['updateVideoUrl'];
			} else {
				$videoUpdateUrl = NULL;
			}
			$categoryId = $_POST['catinput'];

			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlUpdate = "UPDATE tb_media SET 
					media_title = :updateTitle,
					category_id = :categoryId
					WHERE media_id = :mediaId";

			$request = $dbConnection->prepare($sqlUpdate);

			$request->bindParam('updateTitle', $updateTitle);
			$request->bindParam('categoryId', $categoryId);
			$request->bindParam('mediaId', $mediaId);
			$request->execute();
		} catch (PDOException $e) {
			echo "<script>alert('Error! $e');</script>";
		}
	}

	// Script Delete Media
	if (isset($_POST['deleteButton'])) {
		$mediaId = $_POST['mediaId'];
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$fetchThumbnailName = $dbConnection->prepare("SELECT thumbnail FROM tb_media WHERE media_id = :mediaId");
		$fetchThumbnailName->bindParam('mediaId', $mediaId);
		$fetchThumbnailName->execute();
		$result = $fetchThumbnailName->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			$thumbnailName = $result['thumbnail'];
			deleteImageMedia($thumbnailName);
		} else {
			echo '<script>alert("Tidak ada Thumbnail!");</script>';
		}
		$sqlDelete = "DELETE FROM tb_media_tag WHERE media_id = ?";
		$requestDelete = mysqli_prepare($conn, $sqlDelete);

		mysqli_stmt_bind_param($requestDelete, "s", $mediaId);
		mysqli_stmt_execute($requestDelete);
		mysqli_stmt_close($requestDelete);

		$sqlDelete = "DELETE FROM tb_media WHERE media_id = ?";
		$requestDelete = mysqli_prepare($conn, $sqlDelete);

		mysqli_stmt_bind_param($requestDelete, "s", $mediaId);
		mysqli_stmt_execute($requestDelete);
		mysqli_stmt_close($requestDelete);
	}

	header("location:manageMedia.php");
	exit;
}

if (isset($_GET['search-media'])) {
	$searchMedia = $_GET['searchorders'];
	$sql = "SELECT * FROM tb_media WHERE editor_id = '$editorId' AND media_title LIKE '%$searchMedia%'";
} else {
	$sql = "SELECT * FROM tb_media WHERE editor_id = '$editorId'";
}

// Setting Media Datasets
$medias = mysqli_query($conn, $sql);

if ($medias) {
	$mediaArray = [];
	while ($media = mysqli_fetch_assoc($medias)) {
		$mediaArray[] = $media;
	}
} else {
	echo "Error executing the query: " . mysqli_error($conn);
}

// Closing connections;
mysqli_close($conn);
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Nguliah.id - For Editor</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Nguliah.id - For Editor">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Blog">
	<link rel="shortcut icon" href="favicon.ico">

	<!-- Script -->
	<script defer src="../assets/plugins/fontawesome/js/all.min.js"></script>

	<!-- CSS -->
	<link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
	<link id="theme-style" rel="stylesheet" href="../assets\scss\portal.css">
</head>

<body class="app">
	<header class="app-header fixed-top">
		<div class="app-header-inner">
			<div class="container-fluid py-2">
				<div class="app-header-content">
					<div class="row justify-content-between align-items-center">

						<div class="col-auto">
							<a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
								<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
									<title>Menu</title>
									<path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
								</svg>
							</a>
						</div>
						<!--//col-->
						<div class="search-mobile-trigger d-sm-none col">
							<i class="search-mobile-trigger-icon fa-solid fa-magnifying-glass"></i>
						</div>

						<div class="app-utilities col-auto">
							<div class="app-utility-item app-notifications-dropdown dropdown">
								<div class="dropdown-menu p-0" aria-labelledby="notifications-dropdown-toggle">
									<!--//dropdown-menu-title-->
									<!--//dropdown-menu-content-->
								</div>
								<!--//dropdown-menu-->
							</div>

							<div class="app-utility-item app-user-dropdown dropdown">
								<a class="dropdown-toggle" id="user-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><?php echo $editorProfilePhoto ?></a>
								<ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
									<li><a class="dropdown-item" href="accountEditor.php">Account</a></li>
									<li>
										<hr class="dropdown-divider">
									</li>
									<li><a class="dropdown-item" href="logoutEditor.php" id="logout">Log Out</a></li>
								</ul>
							</div>
							<!--//app-user-dropdown-->
						</div>
						<!--//app-utilities-->
					</div>
					<!--//row-->
				</div>
				<!--//app-header-content-->
			</div>
			<!--//container-fluid-->
		</div>
		<div id="app-sidepanel" class="app-sidepanel">
			<div id="sidepanel-drop" class="sidepanel-drop"></div>
			<div class="sidepanel-inner d-flex flex-column">
				<a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
				<div class="app-branding">
					<a class="app-logo" href="indexEditor.php"><img class="logo-icon me-2" src="../assets/images//app-logo.png" alt="logo"><span class="logo-text">Nguliah.id</span></a>
				</div>
				<!--//app-branding-->
				<nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
					<ul class="app-menu list-unstyled accordion" id="menu-accordion">
						<li class="nav-item">
							<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
							<a class="nav-link" href="indexEditor.php">
								<span class="nav-icon">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5v-4H7v4a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v4h3.5V7.707L8 2.207l-5.5 5.5z" />
										<path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
									</svg>
								</span>
								<span class="nav-link-text">Home</span>
							</a>
							<!--//nav-link-->
						</li>
						<!--//nav-item-->
						<li class="nav-item has-submenu">
							<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
							<a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-2" aria-expanded="false" aria-controls="submenu-2">
								<span class="nav-icon">
									<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z" />
										<path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z" />
									</svg>
								</span>
								<span class="nav-link-text">News</span>
								<span class="submenu-arrow">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
									</svg>
								</span>
								<!--//submenu-arrow-->
							</a>
							<!--//nav-link-->
							<div id="submenu-2" class="collapse submenu submenu-2 show" data-bs-parent="#menu-accordion">
								<ul class="submenu-list list-unstyled">
									<li class="submenu-item"><a class="submenu-link" href="manageBlog.php">Blog</a></li>
									<li class="submenu-item"><a class="submenu-link active" href="manageMedia.php">Media</a></li>
								</ul>
							</div>
						</li>

						<!--//nav-item -->
						<li class="nav-item">
							<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
							<a class="nav-link" href="manageEvent.php">
								<span class="nav-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar4-event" viewBox="0 0 16 16">
										<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z" />
										<path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
										<circle cx="3.5" cy="5.5" r=".5" />
										<circle cx="3.5" cy="8" r=".5" />
										<circle cx="3.5" cy="10.5" r=".5" />
									</svg>
								</span>
								<span class="nav-link-text">Event</span>
							</a>
							<!--//nav-link-->
						</li>

						<!--//nav-item -->
						<li class="nav-item">
							<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
							<a class="nav-link" href="manageJob.php">
								<span class="nav-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
										<path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
									</svg>
								</span>
								<span class="nav-link-text">Job Vacancies</span>
							</a>
							<!--//nav-link-->
						</li>

						<!--//nav-item-->
						<li class="nav-item">
							<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
							<a class="nav-link" href="help.php">
								<span class="nav-icon">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-question-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
									</svg>
								</span>
								<span class="nav-link-text">Help</span>
							</a>
							<!--//nav-link-->
						</li>
						<!--//nav-item-->
					</ul>
					<!--//app-menu-->
				</nav>
				<div class="app-sidepanel-footer">
					<nav class="app-nav app-nav-footer">
						<ul class="app-menu footer-menu list-unstyled">
							<li class="nav-item">
								<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
								<a class="nav-link" href="accountEditor.php">
									<span class="nav-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
											<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
										</svg>
									</span>
									<span class="nav-link-text">Account</span>
								</a><!--//nav-link-->
							</li><!--//nav-item-->
						</ul><!--//footer-menu-->
					</nav>
				</div><!--//app-sidepanel-footer-->
			</div>
			<!--//sidepanel-inner-->
		</div>
		<!--//app-sidepanel-->
	</header>
	<!--//app-header-->

	<div class="app-wrapper">
		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<div class="row g-3 mb-4 align-items-center justify-content-between">
					<div class="col-auto">
						<h1 class="app-page-title mb-0">Media</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
								<div class="col-auto">
									<form class="table-search-form row gx-1 align-items-center" action="manageMedia.php" method="GET">
										<div class="col-auto">
											<input type="text" id="search-orders" name="searchorders" class="form-control search-orders" placeholder="Search">
										</div>
										<div class="col-auto">
											<button type="submit" class="btn app-btn-secondary" name="search-media">Search</button>
										</div>
									</form>
								</div>
								<div class="col-auto">
									<a class="btn app-btn-secondary" href="createMedia.php">
										<svg xmlns=" http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
										</svg>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-content" id="orders-table-tab-content">
					<div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
						<div class="app-card app-card-orders-table shadow-sm mb-5">
							<div class="app-card-body">
								<div class="table-responsive">
									<table class="table app-table-hover mb-0 text-center">
										<thead>
											<tr>
												<th class="cekk">Media Id</th>
												<th class="cell">Title</th>
												<th class="cell">Date Release</th>
												<th class="cell">Views</th>
												<th class="cell">Category</th>
												<th class="cell">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($mediaArray as $media) {
												$mediaId = $media['media_id'];
												$categname = getCategoryMediaNameFromId($media['category_id']);
												echo <<<TULIS
														<tr>
															<td class="cell"><strong>{$media['media_id']}</strong></td>
															<td class="cell">{$media['media_title']}</td>
															<td class="cell">{$media['date_release']}</td>
															<td class="cell">{$media['views']}</td>
															<td class="cell">{$categname}</td>
															<td class="cell">
																<a class="btn btn-light" href="../detailMedia.php?mediaId={$media['media_id']}">View</a>
																<a class="btn btn-secondary" data-toggle="modal" href="#update-media-{$media['media_id']}">Edit</a>
																<a class="btn btn-danger" data-toggle="modal" href="#delete-media" onclick="getDeleteMediaId('$mediaId')">Delete</a>
															</td>
														</tr>
													TULIS;
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<footer class="app-footer">
			<div class="container text-center py-3">
				<small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
			</div>
		</footer>
	</div>

	<!-- Update Media Modal Pop Up -->
	<?php foreach ($mediaArray as $media) {
		$categname = getCategoryMediaNameFromId($media['category_id']);
		$formattedDate = dateFormatter($media['date_release']);
		$categoryselections = "";
		echo <<<TULIS
			<div class="modal fade" id="update-media-{$media['media_id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
				<form action="" method="POST" id="formUpdateMedia" enctype="multipart/form-data">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h3>Update Media</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row d-flex justify-content-center align-items-center">
									<div class="card-body p-4">
										<input type="hidden" name="mediaId" value="{$media['media_id']}">
										<div class="form-outline mb-4">
											<label class="form-label">Judul Media</label>
											<input type="text" name="updateTitle" class="form-control form-control-lg" required value='{$media['media_title']}'>
										</div>
										<div class="form-outline mb-4">
											<label class="form-label">Categories</label>
											<select name="catinput" id="catinput" class="form-control">
											{$categoryselections}
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-secondary" name="updateButton" data-bs-dismiss="modal">Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			
		TULIS;
	} ?>

	<!-- Delete Media Modal Pop Up -->
	<div class="modal fade" id="delete-media" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Deletion Confirmation</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this media?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn app-btn-secondary" data-dismiss="modal">Close</button>
					<form action="" method="POST" id="formDeleteMedia">
						<input type="submit" id="submit" name="deleteButton" class="btn app-btn-confirmation" value="Yes">
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="../assets/plugins/popper.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="../assets/js/app.js"></script>
	<script>
		function getDeleteMediaId(mediaId) {
			console.log(mediaId)
			const formDelete = document.getElementById("formDeleteMedia");
			const deleteInput = document.createElement("input");

			deleteInput.setAttribute("type", "hidden");
			deleteInput.setAttribute("name", "mediaId");
			deleteInput.setAttribute("value", mediaId);

			formDelete.appendChild(deleteInput);
		}
	</script>
</body>

</html>