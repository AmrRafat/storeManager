<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");

if ($_POST['no'] == 1) {
    // Catch variables
    $date = $_POST['date'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_STRING);
    $amount = floatval($amount); // Get float value of the amount
    // Insert data into DB
    $stmt = $con->prepare("INSERT INTO bills(date, seller, amount) VALUES(?,?,?)");
    $stmt->execute(array($date, $name, $amount));
    // Update the user interface
    $stmt1 = $con->prepare("SELECT * FROM bills ORDER BY date DESC");
    $stmt1->execute();
    $data = $stmt1->fetchAll();
    echo '<div class="row text-center mb-2">';
    echo '<div class="px-1 col-3 col-lg-2 fw-bold">تاريخ الفاتورة</div>';
    echo '<div class="px-1 col-4 fw-bold">اسم البائع</div>';
    echo '<div class="px-1 col-2 fw-bold">إجمالى الفاتورة</div>';
    echo '<div class="px-1 col-3 col-lg-4"></div>';
    echo '</div>';
    echo '<hr>';
    echo '<div class="dataInfo">';
    foreach ($data as $info) {
        echo '<div class="row text-center data align-items-center py-2" data-log="' . $info['bill_id'] . '">';
        echo '<div class="px-1 col-3 col-lg-2">' . $info['date'] . '</div>';
        echo '<div class="px-1 col-4">' . $info['seller'] . '</div>';
        echo '<div class="px-1 col-2">' . $info['amount'] . '</div>';
        echo '<div class="px-1 col-3 col-lg-4 delCol">';
        echo '<span class="btn btn-danger delBill">حذف</span>';
        echo '</div>';
        echo '</div>';
        echo '<hr>';
    }
    echo '</div>';
} elseif ($_POST['no'] == 0) {
    // Catch variables
    $billID = $_POST['billID'];
    $billID = intval($billID);
    // Delete data from DB
    $stmt = $con->prepare("DELETE FROM bills WHERE bill_id = ?");
    $stmt->execute(array($billID));
    // Update the user interface
    $stmt1 = $con->prepare("SELECT * FROM bills ORDER BY date DESC");
    $stmt1->execute();
    $check = $stmt1->rowCount();
    if ($check == 0) {
        echo '<div class="alert alert-info text-center">لم يتم تسجيل أى فاتورة بعد</div>';
    } else {
        $data = $stmt1->fetchAll();
    }
    if (isset($data)) {
        echo '<div class="row text-center mb-2">';
        echo '<div class="px-1 col-3 col-lg-2 fw-bold">تاريخ الفاتورة</div>';
        echo '<div class="px-1 col-4 fw-bold">اسم البائع</div>';
        echo '<div class="px-1 col-2 fw-bold">إجمالى الفاتورة</div>';
        echo '<div class="px-1 col-3 col-lg-4"></div>';
        echo '</div>';
        echo '<hr>';
        echo '<div class="dataInfo">';
        foreach ($data as $info) {
            echo '<div class="row text-center data align-items-center py-2" data-log="' . $info['bill_id'] . '">';
            echo '<div class="px-1 col-3 col-lg-2">' . $info['date'] . '</div>';
            echo '<div class="px-1 col-4">' . $info['seller'] . '</div>';
            echo '<div class="px-1 col-2">' . $info['amount'] . '</div>';
            echo '<div class="px-1 col-3 col-lg-4 delCol">';
            echo '<span class="btn btn-danger delBill">حذف</span>';
            echo '</div>';
            echo '</div>';
            echo '<hr>';
        }
    }
}
