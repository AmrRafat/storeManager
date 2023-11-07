<?php
session_start();
use function PHPSTORM_META\type;
include_once "../../../connect.php";
$today = date("Y-m-d");
$instaUser = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$stmt1 = $con->prepare("SELECT username FROM installments_users WHERE username = ?");
$stmt1->execute(array($instaUser));
$rows = $stmt1->fetchAll();
$check = $stmt1->rowCount();
if ($check > 0) {
    echo '<div class="text-center alert alert-danger used">هذا الاسم موجود بالفعل</div>';
} else {
    $stmt2 = $con->prepare("INSERT INTO installments_users(username, adding_date) VALUES(?,now())");
    $stmt2->execute(array($instaUser));
}
$stmt = $con->prepare("SELECT * FROM installments_users ORDER BY username");
$stmt->execute();
$rows = $stmt->fetchAll();
$count = $stmt->rowCount();
if (empty($rows) || empty($rows[0]['username'])) {
    echo '<div class="text-center alert alert-info">لا أحد عليه أقساط</div>';
} else {
    ?>
<div class="row row-cols-5 gap-2 justify-content-center">
    <?php
foreach ($rows as $row) {?>
    <a href="?application=info&userid=<?php echo $row['user_id'] ?>" class="col text-center instaName rounded-4 text-decoration-none btn btn-primary">
    <div>
        <h3><?php echo $row['username'] ?></h3>
        <span>المتبقى: <?php echo $row['remain'] ?></span>
    </div>
</a>
<?php }?>
</div>
<?php
}