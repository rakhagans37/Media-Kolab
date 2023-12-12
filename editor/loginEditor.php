<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../helper/getConnection.php';
require_once __DIR__ . '/../helper/cloudinary.php';
require_once __DIR__ . '/../helper/hash.php';
require_once __DIR__ . '/../helper/editor.php';
require_once __DIR__ . '/../helper/editorValidation.php';
require_once __DIR__ . '/../helper/validation.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_COOKIE['editorLoginStatus']) || isset($_SESSION['editorLoginStatus'])) {
    header('Location:indexEditor.php');
    exit;
}

session_start();
$loginFail = false;
$loginFailByPassword = false;

if (isset($_POST['login'])) {
    $email = $_POST['signin-email'];
    $password = $_POST['signin-password'];
    $remember = isset($_POST['RememberPassword']);

    try {
        if ($editorId = editorLoginSuccess($email, $password)) {
            $editorData = getEditorData($editorId);
            $photoUrl = getEditorPhotoId($editorId);
            $loginStatus = true;

            if (is_null(getEditorPhotoId($editorId))) {
                $imgtag = "<img class='profile-image' src='../assets/images/profiles/profile-1.png' alt='Profile Photo'>";
            } else {
                //Saving profile photo into cookies
                $imgtag = getImageProfile($photoUrl);
            }

            if ($remember) {
                setcookie('editorId', $editorId, time() + (86400 * 7));
                setcookie('editorLoginStatus', $loginStatus, time() + (86400 * 7));
                setcookie('editorProfilePhoto', $imgtag, time() + (80400 * 7));
            } else {
                $_SESSION['editorLoginStatus'] = $loginStatus;
                $_SESSION['editorId'] = $editorId;
                $_SESSION['editorProfilePhoto'] = $imgtag;
            }
            header('Location:indexEditor.php');
            exit;
        } else {
            $loginFail = true;
        }

        $conn = null;
    } catch (PDOException $error) {
        $error = "Terjadi Error " . $error->getMessage();
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

    <!-- FontAwesome JS-->
    <script defer src="../assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="../assets/scss/portal.css">

</head>

<body class="app app-login p-0">
    <div class="row g-0 app-auth-wrapper">
        <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <?php
            if ($loginFail) {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo "<strong>Oh tidak!</strong> sepertinya email tidak terdaftar atau akun terkena banned, silahkan hubungi kolab@gmail.com</div>";
            } elseif ($loginFailByPassword) {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo "<strong>Oh tidak!</strong> sepertinya password yang anda masukkan salah :(</div>";
            }
            ?>
            <div class="d-flex flex-column align-content-end">
                <div class="app-auth-body mx-auto">
                    <div class="app-auth-branding mb-4"><a class="app-logo" href="indexEditor.php"><img class="logo-icon me-2" src="../assets/images/app-logo.png" alt="logo"></a></div>
                    <h2 class="auth-heading text-center mb-5">Log in to Nguliah.id</h2>
                    <div class="auth-form-container text-start">
                        <form class="auth-form login-form" method="POST">
                            <div class="email mb-3">
                                <label class="sr-only" for="signin-email">Email</label>
                                <input id="signin-email" name="signin-email" type="email" class="form-control signin-email" placeholder="Email address" required="required">
                            </div>
                            <!--//form-group-->
                            <div class="password mb-3">
                                <label class="sr-only" for="signin-password">Password</label>
                                <input id="signin-password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" required="required">
                                <div class="extra mt-3 row justify-content-between">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="RememberPassword" id="RememberPassword" name="RememberPassword">
                                            <label class="form-check-label" for="RememberPassword">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <!--//col-6-->
                                    <div class="col-6">
                                        <div class="forgot-password text-end">
                                            <a href="reset-password.php">Forgot password?</a>
                                        </div>
                                    </div>
                                    <!--//col-6-->
                                </div>
                                <!--//extra-->
                            </div>
                            <!--//form-group-->
                            <div class="text-center">
                                <button type="submit" name="login" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
                            </div>
                        </form>

                        <div class="auth-option text-center pt-5">No Account? Sign up <a class="text-link" href="signup.php">here</a>.</div>
                    </div>
                    <!--//auth-form-container-->

                </div>
                <!--//auth-body-->

                <footer class="app-auth-footer">
                    <div class="container text-center py-3">
                        <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
                        <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for
                            developers</small>

                    </div>
                </footer>
                <!--//app-auth-footer-->
            </div>
            <!--//flex-column-->
        </div>
        <!--//auth-main-col-->
        <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
            <div class="auth-background-holder">
            </div>
            <div class="auth-background-mask"></div>
            <div class="auth-background-overlay p-3 p-lg-5">
                <div class="d-flex flex-column align-content-end h-100">
                    <div class="h-100"></div>
                    <div class="overlay-content p-3 p-lg-4 rounded">
                        <h5 class="mb-3 overlay-title">Explore Portal Admin Template</h5>
                        <div>Portal is a free Bootstrap 5 admin dashboard template. You can download and view the
                            template license <a href="https://themes.3rdwavemedia.com/bootstrap-templates/admin-dashboard/portal-free-bootstrap-admin-dashboard-template-for-developers/">here</a>.
                        </div>
                    </div>
                </div>
            </div>
            <!--//auth-background-overlay-->
        </div>
        <!--//auth-background-col-->

    </div>
    <!--//row-->
</body>

</html>