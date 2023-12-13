<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");

$cat_name = filter_var($_POST['cat'], FILTER_SANITIZE_STRING);
$user_id = $_SESSION['id'];
$stmt = $con->prepare("SELECT cat_name FROM cats WHERE cat_name = ?");
$stmt->execute(array($cat_name));
$count = $stmt->rowCount();
if ($count > 0) {
    echo '0';
} else {
    $stmt = $con->prepare("INSERT INTO cats(cat_name, adding_date, user_id) VALUES(?,now(),?)");
    $stmt->execute(array($cat_name, $user_id));
    $stmt1 = $con->prepare("SELECT cats.*, users.fullname AS fullname FROM cats INNER JOIN users ON cats.user_id = users.user_id WHERE DEL = 0 ORDER BY cat_name");
    $stmt1->execute();
    $rows = $stmt1->fetchAll();
    $count = $stmt1->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أقسام بعد... يرجى إضافة قسم جديد</div>';
    } else {
        echo '<table class="table table-striped text-center">';
        echo '<tr>';
        echo '<th>اسم القسم</th>';
        echo '<th>تاريخ الإنشاء</th>';
        echo '<th>الشخص الذى أنشأه</th>';
        echo '<th>التحكم</th>';
        echo '</tr>';
        foreach ($rows as $row) {
            echo '<tr class="align-middle">';
            echo '<td>';
            echo $row['cat_name'];
            echo '</td>';
            echo '<td>';
            echo $row['adding_date'];
            echo '</td>';
            echo '<td>';
            echo $row['fullname'];
            echo '</td>';
            echo '<td>';
            echo '<span class="btn btn-success ms-0 ms-lg-2 mb-2 mb-lg-0"><a class="text-decoration-none text-reset" href="?application=edit&catid=';
            echo $row['cat_id'];
            echo '">تعديل</a></span>';
            echo '<span class="btn btn-danger"><a class="text-decoration-none text-reset" href="?application=del&catid=';
            echo $row['cat_id'];
            echo '">حذف</a></span>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}
