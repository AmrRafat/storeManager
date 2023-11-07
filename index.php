<?php
session_start();
$noNavbar = '';
$pageTitle = 'قصر الملكة';
if (isset($_SESSION['Username'])) {
    header('location: records.php'); // Redirect to dashboard page
    exit();
}
include "init.php";

// Check if User comming from HTTP Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $hashedPass = sha1($password);
    // Check if user is in database
    $stmt = $con->prepare(
        "SELECT * FROM users WHERE username = ? AND password = ? Limit 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    // if count > 0, Database contains record about this username
    if ($count > 0) {
        $_SESSION['username'] = $username; // Register Session Name
        $_SESSION['id'] = $row['user_id']; // Register Session ID
        $_SESSION['access'] = $row['access']; // Registering User Access
        header('location: records.php'); // Redirect to dashboard page
        exit();
    }
}
?>
<div class="position-relative bg-dark bg-gradient login-bg">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login block">
        <div class="row">
            <img src="layout/imgs/logo.png" alt="">
        </div>
        <div class="row">
            <div class="col align-self-center">
                <h2 class="text-center mb-4 text-white">مرحبا بكم فى قصر الملكة</h2>
                <h2 class="text-center mb-4 text-white">للأدوات المنزلية</h2>
            </div>
            <div class="col">
                <input class="form-control form-control-lg text-center" type="text" name="user" placeholder="اسم المستخدم" autocomplete="off">
                <input class="form-control form-control-lg text-center" type="password" name="pass" placeholder="كلمة المرور" autocomplete="new-password">
                <input class="btn btn-primary btn-block" type="submit" value="دخول">
            </div>
        </div>
    </form>
</div>


<?php include $tpl . "footer.php";?>