<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");

$cat = $_POST['cat'];
$subcat = filter_var($_POST['subcat'], FILTER_SANITIZE_STRING);
$user_id = $_SESSION['id'];

if ($cat == 0) {
    echo '1';
} else {
    $stmt = $con->prepare("SELECT subcat_name FROM subcats WHERE subcat_name = ? AND cat_id = ?");
    $stmt->execute(array($subcat, $cat));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '0';
    } else {
        $stmt = $con->prepare("INSERT INTO subcats(subcat_name, adding_date, user_id, cat_id) VALUES(?,now(),?,?)");
        $stmt->execute(array($subcat, $user_id, $cat));
        $stmt1 = $con->prepare("SELECT
                        subcats.*,
                        users.fullname AS fullname,
                        cats.cat_name AS catName
                    FROM
                        subcats
                    INNER JOIN users ON
                    subcats.user_id = users.user_id
                    INNER JOIN cats ON
                    subcats.cat_id = cats.cat_id
                    WHERE subcats.del = 0
                    ORDER BY subcat_name");
        $stmt1->execute();
        $rows = $stmt1->fetchAll();
        $count = $stmt1->rowCount();
        foreach ($rows as $row) {
            echo '<tr class="align-middle">';
            echo '<td>' . $row['subcat_name'] . '</td>';
            echo '<td>' . $row['catName'] . '</td>';
            echo '<td>' . $row['adding_date'] . '</td>';
            echo '<td>' . $row['fullname'] . '</td>';
            echo '<td>';
            echo '<span class="btn btn-success ms-2"><a class="text-decoration-none text-reset" href="?application=edit&subcatid=' . $row['subcat_id'] . '">تعديل</a></span>';
            echo '<span class="btn btn-danger"><a class="text-decoration-none text-reset" href="?application=del&subcatid=' . $row['subcat_id'] . '">حذف</a></span>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}
