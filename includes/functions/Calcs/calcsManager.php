<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");

if (isset($_POST['app'])) {
    $app = $_POST['app'];
    if ($app == 1) {
        // Get data
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $date = $_POST['date'];
        $amount = filter_var($_POST['amount'], FILTER_SANITIZE_STRING);
        // Insert into DB
        $stmt = $con->prepare("INSERT INTO spendings(spendingName, date, amount) VALUES(?,?,?)");
        $stmt->execute(array($name, $date, $amount));
        // Show in user interface
        $stmt1 = $con->prepare("SELECT * FROM spendings ORDER BY date DESC");
        $stmt1->execute();
        $data = $stmt1->fetchAll();
        echo '<table class="table table-striped spendingsTable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>المصاريف</th>';
        echo '<th>التاريخ</th>';
        echo '<th>المبلغ</th>';
        echo '<th></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($data as $info) {
            echo '<tr data-id="' . $info['spendingID'] . '"  class="align-middle overflow-hidden infoData">';
            echo '<td>' . $info['spendingName'] . '</td>';
            echo '<td>' . $info['date'] . '</td>';
            echo '<td>' . $info['amount'] . '</td>';
            echo '<td class="forth"><span class="btn btn-danger spendingDel py-1">حذف</span></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</form>';
    } elseif ($app == 2) {
        // Get data
        $id = $_POST['id'];
        // Del from DB
        $stmt = $con->prepare("DELETE FROM spendings WHERE spendingID = ?");
        $stmt->execute(array($id));
        // Show in user interface
        $stmt1 = $con->prepare("SELECT * FROM spendings ORDER BY date DESC");
        $stmt1->execute();
        $data = $stmt1->fetchAll();
        echo '<table class="table table-striped spendingsTable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>المصاريف</th>';
        echo '<th>التاريخ</th>';
        echo '<th>المبلغ</th>';
        echo '<th></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($data as $info) {
            echo '<tr data-id="' . $info['spendingID'] . '"  class="align-middle overflow-hidden infoData">';
            echo '<td>' . $info['spendingName'] . '</td>';
            echo '<td>' . $info['date'] . '</td>';
            echo '<td>' . $info['amount'] . '</td>';
            echo '<td class="forth"><span class="btn btn-danger spendingDel py-1">حذف</span></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</form>';
    }
}
