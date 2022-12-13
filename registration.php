<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?>
<?php require_once "./admin/functions.php"; ?>

<?php
// Setting Language Variables
//session_start();
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];

    if (isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']) {
        echo "<script type='text/javascript'> location.reload(); </script>";
    }
}
if (isset($_SESSION['lang'])) {
    include "includes/languages/".$_SESSION['lang'].".php";
} else {
    include "includes/languages/en.php";
}
?>

<?php
// AUTHENTICATION
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = trim(escape($_POST['username']));
    $email = trim(escape($_POST['email']));
    $password = trim(escape($_POST['password']));

    $error = [
        'username' => '',
        'email' => '',
        'password' => ''
    ];
    if (strlen($username) < 4) {
        $error['username'] = 'Username needs to be longer';
    }
    if ($username == '') {
        $error['username'] = 'Username cannot be empty';
    }
    if (username_exists($username)) {
        $error['username'] = 'Username already exists, pick another one';
    }
    if ($email == '') {
        $error['email'] = 'Email field cannot be empty';
    }
    if (email_exists($email)) {
        $error['email'] = 'Email already exists, <a href="index.php">Please login.</a>';
    }
    if ($password == '') {
        $error['password'] = 'Password cannot be empty';
    }
    /**
     * @var  $key
     * @var  $value
     * We go through the array of errors and if it's empty we unset the key
     */
    foreach ($error as $key => $value) {
        if (empty($value)) {
            unset($error[$key]);
//            register_user($username, $error, $password);
//            login_user($username, $password);
        }
        if (empty($error)) {
            register_user($username, $email, $password);
            login_user($username, $password);
        }
    }
}
?>

<!-- Page Content -->
<div class="container">
<!--Multi-Language Implementation-->
    <form method="get" class="navbar-form navbar-right" action="" id="language_form">
        <div class="form-group">
            <select name="lang" class="form-control" onchange="changeLanguage()">
                <option value="en" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') { echo "selected"; } ?> >English</option>
                <option value="fr" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'fr') { echo "selected"; } ?> >French</option>
            </select>
        </div>
    </form>

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-wrap">
                        <h1><?php echo _REGISTER; ?></h1>
                        <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                            <div class="form-group">
                                <label for="username" class="sr-only">username</label>
                                <input type="text" name="username" id="username" class="form-control" autocomplete="on"
                                       value="<?php echo $username ?? '' ?>"
                                       placeholder="<?php echo _USERNAME; ?> ">
                                <p><?php echo isset($error['username']) ? $error['username'] : '' ?></p>
                            </div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" autocomplete="on"
                                       value="<?php echo isset($email) ? $email : '' ?>"
                                       placeholder="<?php echo _EMAIL; ?> ">
                                <p><?php echo isset($error['email']) ? $error['email'] : '' ?></p>
                            </div>
                            <div class="form-group">
                                <label for="password" class="sr-only">Password</label>
                                <input type="password" name="password" id="key" class="form-control"
                                       placeholder="<?php echo _PASSWORD; ?> ">
                                <p><?php echo isset($error['password']) ? $error['password'] : '' ?></p>
                            </div>

                            <input type="submit" name="register" id="btn-login" class="btn btn-primary btn-lg btn-block"
                                   value="<?php echo _REGISTER; ?> ">
                        </form>

                    </div>
                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>


    <hr>
    <script>
        function changeLanguage() {
            document.getElementById('language_form').submit();
        }
    </script>

    <?php include "includes/footer.php"; ?>
