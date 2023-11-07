<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");

if ($_POST['way'] == 'asc') {
    $stmt = $con->prepare("SELECT
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
    $stmt->execute();
    $rows = $stmt->fetchAll();
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
} elseif ($_POST['way'] == 'desc') {
    $stmt = $con->prepare("SELECT
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
                    ORDER BY subcat_name DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll();
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
} elseif ($_POST['way'] == 'mainasc') {
    $stmt = $con->prepare("SELECT
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
                    ORDER BY catName");
    $stmt->execute();
    $rows = $stmt->fetchAll();
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
} elseif ($_POST['way'] == 'maindesc') {
    $stmt = $con->prepare("SELECT
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
                    ORDER BY catName DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll();
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
}
