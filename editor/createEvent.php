<?php
require_once "../helper/getConnection.php";
require_once "../helper/validateLoginEditor.php";
require_once "../helper/getConnectionMsqli.php";
require_once "../helper/cloudinary.php";
require_once "../helper/hash.php";
require_once "../helper/event.php";
require_once "../helper/validation.php";
require_once "../helper/increasePopularity.php";
require_once __DIR__ . "/../helper/tag.php";
require "../vendor/autoload.php";


$conn = getConnection();
$createSuccess = true;

if (isset($_POST['event-submit'])) {

	$eventId = generateIdEvent();
	$eventTitle = $_POST['eventtitle'];
	$eventContent = $_POST['eventcontent'];
	$eventUrl = $_POST['eventurl'];
	$dateRelease = date("Y-m-d");
	$dateEvent = $_POST['dateevent'];
	$videoUrl = $_POST['videourl'] == null || $_POST['videourl'] = "" ? null : getYoutubeID($_POST['videourl']);
	$tagId = $_POST['tagid'];
	$categoryId = $_POST['categoryid'];
	$linkGoogleMap = $_POST['linkgooglemap'];
	$editorId = $editorId;
	$imageUrl = uploadImageNews($_FILES['new-image']['tmp_name']);

	if (strlen($eventTitle) < 12) {
		$createSuccess = false;
		$createError = "title";
	} else if (!$imageUrl) {
		$createSuccess = false;
		$createError = "image";
	} else if (strlen($eventContent) < 120) {
		$createSuccess = false;
		$createError = "contentLength";
	} else {
		// Menyimpan data ke database
		setNewEvent($eventId, $eventTitle, $eventContent, $eventUrl, $dateRelease, $dateEvent, $videoUrl, $categoryId, $linkGoogleMap, $editorId, $imageUrl);

		//Increase the popularity of category
		increaseBlogCategory($categoryId);

		//Insert event tag
		insertEventTag(separateTag($tagId), $eventId);

		$conn = null;
		header("location:manageEvent.php");
		exit;
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Nguliah.id - For Editor</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="description" content="Nguliah.id - For Editor">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Media">
	<link rel="shortcut icon" href="favicon.ico">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />


	<!-- FontAwesome JS-->
	<script defer src="../assets/plugins/fontawesome/js/all.min.js"></script>

	<!-- App CSS -->
	<!-- <link id="theme-style" rel="stylesheet" href="assets/css/portal.css"> -->
	<link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

	<!-- Tiny MCE -->
	<script src="https://cdn.tiny.cloud/1/lhv9f0avcbklw7a4sdsja88fhk03p5b55kreb9wvfmt40mmf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

	<script>
		tinymce.init({
			selector: 'textarea#editor',
			paste_as_text: true,
			menu: {
				file: {
					title: 'File',
					items: 'newdocument restoredraft | preview | export print | deleteallconversations'
				},
				edit: {
					title: 'Edit',
					items: 'undo redo | cut copy paste | selectall | searchreplace'
				},
				view: {
					title: 'View',
					items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments'
				},
				insert: {
					title: 'Insert',
					items: 'image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime'
				},
				format: {
					title: 'Format',
					items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontstyle fontsize align lineheight | forecolor backcolor | language | removeformat'
				},
				tools: {
					title: 'Tools',
					items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount'
				},
				table: {
					title: 'Table',
					items: 'inserttable | cell row column | advtablesort | tableprops deletetable'
				},
				help: {
					title: 'Help',
					items: 'help'
				}
			},
			font_family_formats: 'Georgia=georgia,palatino',
			line_height_formats: '0.5 1 1.2 1.4 1.6 2',
			setup: (editor) => {
				// Apply the focus effect
				editor.on("init", () => {
					editor.getContainer().style.transition = "border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out";
				});
				editor.on("focus", () => {
					(editor.getContainer().style.boxShadow = "0 0 0 .2rem rgba(0, 123, 255, .25)"),
					(editor.getContainer().style.borderColor = "#80bdff");
				});
				editor.on("blur", () => {
					(editor.getContainer().style.boxShadow = ""),
					(editor.getContainer().style.borderColor = "");
				});
			}
		});
	</script>



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
							<div id="submenu-2" class="collapse submenu submenu-2" data-bs-parent="#menu-accordion">
								<ul class="submenu-list list-unstyled">
									<li class="submenu-item"><a class="submenu-link" href="manageBlog.php">Blog</a></li>
									<li class="submenu-item"><a class="submenu-link" href="manageMedia.php">Media</a></li>
								</ul>
							</div>
						</li>

						<!--//nav-item -->
						<li class="nav-item">
							<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
							<a class="nav-link active" href="manageEvent.php">
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
				<?php
				if ($createSuccess === false) {
					switch ($createError) {
						case 'title':
							$errorMsg = "Judul harus lebih dari 12 karakter";
							break;
						case 'image':
							$errorMsg = "Gambar yang di upload maximal memiliki ukuran 5 Mb";
							break;
						case 'contentLength':
							$errorMsg = "Konten blog harus memiliki lebih dari 120 karakter";
							break;
						default:
							break;
					}
					echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
					echo "<strong>Mohon maaf, </strong>$errorMsg</div>";
				}
				?>
				<div class="row g-3 mb-4 align-items-center justify-content-between">
					<div class="col-auto">
						<h1 class="app-page-title mb-0">Create Event</h1>
					</div>
				</div><!--//row-->

				<div class="container mt-4 mb-4">
					<div class="row justify-content-md-center">
						<form class="col-md-20 col-lg-10" enctype="multipart/form-data" id="event-submit" action="createEvent.php" method="post">
							<label>Event Title</label>

							<div class="input-group ">
								<input type="text" class="form-control" name="eventtitle" aria-label="EventTitle" aria-describedby="basic-addon2">
							</div>

							<label>Event URL</label>

							<div class="input-group">
								<input type="text" class="form-control" name="eventurl" id="basic-url" aria-describedby="basic-addon3">
							</div>

							<label>Date Event</label>

							<div class="input-group">
								<input type="text" class="date form-control" style="width: 200px" name="dateevent" />
								<script type="text/javascript">
									$(".date").datepicker({
										format: "yyyy-mm-dd",
									});
								</script>

							</div>



							<label>Image URL</label>
							<div class="input-group ">
								<input type="file" name="new-image" id="new-image" required>
							</div>


							<label>Youtube URL</label>

							<div class="input-group ">
								<input type="text" class="form-control" name="videourl" aria-label="EventID" aria-describedby="basic-addon1" placeholder="ex: https://www.youtube.com/watch?v=abcdefg">
							</div>

							<label>Tag</label>

							<div class="input-group ">
								<input type="text" class="form-control" name="tagid" aria-label="tagid" aria-describedby="basic-addon1" placeholder="tag1,tag2,tag3 (Separate by comma)">
							</div>

							<label>Category</label>

							<div class="input-group ">

								<select class="form-select" name="categoryid" aria-label="Default select example" required>
									<option value="" disabled selected hidden>Pilih Kategori Anda</option>
									<?php
									$sqlRole = "SELECT * from tb_category_event";

									$request = $conn->prepare($sqlRole);
									$request->execute();

									if ($result = $request->fetchAll()) {
										foreach ($result as $index) {
											$categoryId = $index['category_id'];
											$categoryName = $index['category_name'];

											echo "<option value='$categoryId'>$categoryName</option>";
										}
									}
									?>
								</select>
							</div>

							<label>Link Google Map</label>

							<div class="input-group ">
								<input type="text" class="form-control" name="linkgooglemap" aria-label="EventID" aria-describedby="basic-addon1">
							</div>

							<label>Event Content</label>
							<!--Bootstrap classes arrange web page components into columns and rows in a grid -->

							<div class="form-group">
								<textarea id="editor" name="eventcontent"></textarea>
							</div>
							<button type="submit" name="event-submit" class="btn btn-primary">Submit</button>
						</form>
					</div>
				</div>
			</div>


		</div><!--//app-content-->

		<footer class="app-footer">
			<div class="container text-center py-3">
				<!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
				<small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>

			</div>
		</footer><!--//app-footer-->

	</div><!--//app-wrapper-->


	<!-- Javascript -->
	<script defer src="../assets/plugins/popper.min.js"></script>
	<script defer src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>


	<!-- Page Specific JS -->
	<script defer src="../assets/js/app.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>