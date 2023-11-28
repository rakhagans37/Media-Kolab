<?php
require_once __DIR__ . "/../helper/hash.php";
require_once __DIR__ . "/../helper/getConnection.php";
require_once __DIR__ . "/../helper/getConnectionMsqli.php";
require_once __DIR__ . "/../helper/blogfunctions.php";
require_once __DIR__ . "/../helper/validateLoginEditor.php";

// Nanti ganti pake sessuai session ya
$editor_id_cur_session = "1305b549-8";

// Get connections
$conn = getConnectionMysqli();
$dbConnection = getConnection();

// Fetching data from the tb_tag table
$query = $dbConnection->query("SELECT * FROM tb_tag");
$tags = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetching data from the tb_category table
$query = $dbConnection->query("SELECT * FROM tb_category_blog");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

// Post Detection
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Script Create New Blog
	if (isset($_POST['createBlog'])) {
		$blogId = generateIdBlog();
		$createTitle = $_POST['createTitle'];
		$content = $_POST['createContent'];
		$currentDate = date("Y-m-d");
		if ($_POST['createImageUrl']) {
			$imageCreateUrl = $_POST['createImageUrl'];
		} else {
			$imageCreateUrl = "";
		}
		$tagId = $_POST['taginput'];
		$categoryId = $_POST['catinput'];

		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sqlCreate = "INSERT INTO tb_blog (blog_id, blog_title, blog_content, date_release, image_url, views, tag_id, category_id, editor_id)
				VALUES (:blogId, :createTitle, :content, :currentDate, :image_url, 0, :tagId, :categoryId, :editor_id_cur_session)";
		$request = $dbConnection->prepare($sqlCreate);

		$request->bindParam('blogId', $blogId);
		$request->bindParam('createTitle', $createTitle);
		$request->bindParam('content', $content);
		$request->bindParam('currentDate', $currentDate);
		$request->bindParam('image_url', $imageCreateUrl);
		$request->bindParam('tagId', $tagId);
		$request->bindParam('categoryId', $categoryId);
		$request->bindParam('editor_id_cur_session', $editor_id_cur_session);
		$request->execute();

		echo '<script>alert("Data berhasil ditambahkan!");</script>';
		header("Location:manageblog.php");
	}

	// Script Update Blog
	if (isset($_POST['updateButton'])) {
		try {
			$blogId = $_POST['blogId'];
			$updateTitle = $_POST['updateTitle'];
			$updateContent = $_POST['updateContent'];
			$currentDate = date("Y-m-d");
			if ($_POST['updateImageUrl']) {
				$imageUpdateUrl = $_POST['updateImageUrl'];
			} else {
				$imageUpdateUrl = NULL;
			}
			$tagId = $_POST['taginput'];
			$categoryId = $_POST['catinput'];

			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlUpdate = "UPDATE tb_blog SET 
					blog_title = :updateTitle,
					blog_content = :updateContent,
					date_release = :currentDate,
					image_url = :image_url,
					tag_id = :tagId,
					category_id = :categoryId
					WHERE blog_id = :blogId";

			$request = $dbConnection->prepare($sqlUpdate);

			$request->bindParam('updateTitle', $updateTitle);
			$request->bindParam('updateContent', $updateContent);
			$request->bindParam('currentDate', $currentDate);
			$request->bindParam('image_url', $imageUpdateUrl);
			$request->bindParam('tagId', $tagId);
			$request->bindParam('categoryId', $categoryId);
			$request->bindParam('blogId', $blogId);
			$request->execute();

			header("Location:manageblog.php");
		} catch (PDOException $e) {
			echo "<script>alert('Error! $e');</script>";
		}
	}

	// Script Delete Blog
	if (isset($_POST['deleteButton'])) {
		$blogId = $_POST['blogId'];

		$sqlDelete = "DELETE FROM tb_blog WHERE blog_id = ?";
		$requestDelete = mysqli_prepare($conn, $sqlDelete);

		mysqli_stmt_bind_param($requestDelete, "s", $blogId);
		mysqli_stmt_execute($requestDelete);
		mysqli_stmt_close($requestDelete);
	}
}

if (isset($_GET['search-blog'])) {
	$searchBlog = $_GET['searchorders'];
	$sql = "SELECT * FROM tb_blog WHERE blog_title LIKE '%$searchBlog%'";
} else {
	$sql = "SELECT * FROM tb_blog";
}

// Setting Blog Datasets
$blogs = mysqli_query($conn, $sql);

if ($blogs) {
	$blogArray = [];
	while ($blog = mysqli_fetch_assoc($blogs)) {
		$blogArray[] = $blog;
	}
} else {
	echo "Error executing the query: " . mysqli_error($conn);
}

// Closing connections;
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>CRUD Blog</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Blog">
	<link rel="shortcut icon" href="favicon.ico">

	<!-- Script -->
	<script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

	<!-- CSS -->
	<link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
	<link id="theme-style" rel="stylesheet" href="assets\scss\portal.css">
</head>

<body class="app">
	<div class="app-wrapper">
		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<div class="row g-3 mb-4 align-items-center justify-content-between">
					<div class="col-auto">
						<h1 class="app-page-title mb-0">Blog</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
								<div class="col-auto">
									<form class="table-search-form row gx-1 align-items-center" action="manageblog.php" method="GET">
										<div class="col-auto">
											<input type="text" id="search-orders" name="searchorders" class="form-control search-orders" placeholder="Search">
										</div>
										<div class="col-auto">
											<button type="submit" class="btn app-btn-secondary" name="search-blog">Search</button>
										</div>
									</form>
								</div>
								<div class="col-auto">
									<select class="form-select w-auto">
										<option selected value="option-1">All</option>
										<option value="option-2">This week</option>
										<option value="option-3">This month</option>
										<option value="option-4">Last 3 months</option>
									</select>
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
												<th class="cell">Title</th>
												<th class="cell">Content</th>
												<th class="cell">Date Release</th>
												<th class="cell">Views</th>
												<th class="cell">Category</th>
												<th class="cell">Tag</th>
												<th class="cell">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($blogArray as $blog) {
												$blogId = $blog['blog_id'];
												$tagname = getTagNameFromId($blog['tag_id']);
												$categname = getCategoryBlogNameFromId($blog['category_id']);
												echo <<<TULIS
														<tr>
															<td class="cell"><strong>{$blog['blog_title']}</strong></td>
															<td class="cell">{$blog['blog_content']}</td>
															<td class="cell">{$blog['date_release']}</td>
															<td class="cell">{$blog['views']}</td>
															<td class="cell">{$categname}</td>
															<td class="cell">{$tagname}</td>
															<td class="cell">
																<a class="btn btn-light" data-toggle="modal" href="#view-blog-{$blog['blog_id']}">View</a>
																<a class="btn btn-secondary" data-toggle="modal" href="#update-blog-{$blog['blog_id']}">Edit</a>
																<a class="btn btn-danger" data-toggle="modal" href="#delete-blog" onclick="getDeleteBlogId('$blogId')">Delete</a>
															</td>
														</tr>
													TULIS;
											}
											?>
											<tr>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>
													<button type="button" data-bs-toggle="modal" data-bs-target="#createnew" class="btn btn-primary align-items-center">Create New Blog</button>
												</td>
											</tr>
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
				<small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwaveblog.com" target="_blank">Xiaoying Riley</a> for developers</small>
			</div>
		</footer>
	</div>

	<!-- Create New Blog Modal Pop Up -->
	<form action="" method="POST" id="createBlog" enctype="multipart/form-data">
		<div class="modal fade" id="createnew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createnew" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h3>New Blog</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row d-flex justify-content-center align-items-center">
							<div class="card-body p-4">
								<div class="form-outline mb-4">
									<label class="form-label">Judul Blog</label>
									<input type="text" name="createTitle" class="form-control form-control-lg" required>
								</div>
								<div class="form-outline mb-4">
									<label class="form-label">Isi Blog</label>
									<input type="text" name="createContent" class="form-control form-control-lg" required>
								</div>
								<div class="form-outline mb-4">
									<label class="form-label">Url Media</label>
									<input type="text" name="createImageUrl" class="form-control form-control-lg">
								</div>
								<div class="form-outline mb-4">
									<label class="form-label">Tag</label>
									<select name="taginput" id="taginput" class="form-control">
										<?php foreach ($tags as $tag) { ?>
											<option value="<?php echo $tag['tag_id']; ?>"><?php echo $tag['tag_name']; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-outline mb-4">
									<label class="form-label">Categories</label>
									<select name="catinput" id="catinput" class="form-control">
										<?php foreach ($categories as $categ) { ?>
											<option value="<?php echo $categ['category_id']; ?>"><?php echo $categ['category_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" name="createBlog" data-bs-dismiss="modal">Publish</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<!-- View Blog Modal Pop Up -->
	<?php foreach ($blogArray as $blog) {
		$tagname = getTagNameFromId($blog['tag_id']);
		$categname = getCategoryBlogNameFromId($blog['category_id']);
		$formattedDate = dateFormatter($blog['date_release']);
		if ($blog['image_url'] != "") {
			$imageurl = "<div class='form-outline mb-4'><h3>Image</h3><p><a href='{$blog['image_url']}' target='_blank'><img src='{$blog['image_url']}' alt='Image'></a></div>";
		} else {
			$imageurl = "";
		}
		echo <<<TULIS
			<div class="modal fade" id="view-blog-{$blog['blog_id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h3>View Blog</h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row d-flex justify-content-center align-items-center">
								<div class="card-body p-4">
									<div class="form-outline mb-4">
										<h3>Blog ID</h3>
										<p>{$blog['blog_id']}</p>
									</div>
									<div class="form-outline mb-4">
										<h3>Title</h3>
										<p>{$blog['blog_title']}</p>
									</div>
									<div class="form-outline mb-4">
										<h3>Content</h3>
										<p>{$blog['blog_content']}</p>
									</div>
									<div class="form-outline mb-4">
										<h3>Date Release</h3>
										<p>{$formattedDate}</p>
									</div>
									{$imageurl}
									<div class="form-outline mb-4">
										<h3>Views</h3>
										<p>{$blog['views']}</p>
									</div>
									<div class="form-outline mb-4">
										<h3>Tag</h3>
										<p>{$tagname}</p>
									</div>
									<div class="form-outline mb-4">
										<h3>Category</h3>
										<p>{$categname}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

		TULIS;
	} ?>

	<!-- Update Blog Modal Pop Up -->
	<?php foreach ($blogArray as $blog) {
		$tagname = getTagNameFromId($blog['tag_id']);
		$categname = getCategoryBlogNameFromId($blog['category_id']);
		$formattedDate = dateFormatter($blog['date_release']);
		if ($blog['image_url'] != "") {
			$imageurl = "<div class='form-outline mb-4'><label class='form-label'>Image URL</label><input type='text' name='updateImageUrl' class='form-control form-control-lg' value='{$blog['image_url']}'></div>";
		} else {
			$imageurl = "<div class='form-outline mb-4'><label class='form-label'>Image URL</label><input type='text' name='updateImageUrl' class='form-control form-control-lg'></div>";
		}
		$tagselections = "";
		$categoryselections = "";
		foreach ($tags as $tag) {
			if ($tag['tag_id'] == $blog['tag_id']) {
				$tagselections .= "<option value='{$tag['tag_id']}' selected>{$tag['tag_name']}</option>";
			} else {
				$tagselections .= "<option value='{$tag['tag_id']}'>{$tag['tag_name']}</option>";
			}
		}
		foreach ($categories as $category) {
			if ($category['category_id'] == $blog['category_id']) {
				$categoryselections .= "<option value='{$category['category_id']}' selected>{$category['category_name']}</option>";
			} else {
				$categoryselections .= "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
			}
		}
		echo <<<TULIS
			<div class="modal fade" id="update-blog-{$blog['blog_id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
				<form action="" method="POST" id="formUpdateBlog" enctype="multipart/form-data">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h3>Update Blog</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row d-flex justify-content-center align-items-center">
									<div class="card-body p-4">
										<input type="hidden" name="blogId" value="{$blog['blog_id']}">
										<div class="form-outline mb-4">
											<label class="form-label">Judul Blog</label>
											<input type="text" name="updateTitle" class="form-control form-control-lg" required value='{$blog['blog_title']}'>
										</div>
										<div class="form-outline mb-4">
											<label class="form-label">Isi Blog</label>
											<input type="text" name="updateContent" class="form-control form-control-lg" required value='{$blog['blog_content']}'>
										</div>
										{$imageurl}
										<div class="form-outline mb-4">
											<label class="form-label">Tag</label>
											<select name="taginput" id="taginput" class="form-control">
											{$tagselections}
											</select>
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

	<!-- Delete Blog Modal Pop Up -->
	<div class="modal fade" id="delete-blog" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Deletion Confirmation</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this blog?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn app-btn-secondary" data-dismiss="modal">Close</button>
					<form action="" method="POST" id="formDeleteBlog">
						<input type="submit" id="submit" name="deleteButton" class="btn app-btn-confirmation" value="Yes">
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="assets/plugins/popper.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/app.js"></script>
	<script>
		function getDeleteBlogId(blogId) {
			console.log(blogId)
			const formDelete = document.getElementById("formDeleteBlog");
			const deleteInput = document.createElement("input");

			deleteInput.setAttribute("type", "hidden");
			deleteInput.setAttribute("name", "blogId");
			deleteInput.setAttribute("value", blogId);

			formDelete.appendChild(deleteInput);
		}
	</script>
</body>

</html>