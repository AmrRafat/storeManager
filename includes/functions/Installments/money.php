<?php
session_start();
use function PHPSTORM_META\type;
include_once "../../../connect.php";
$today = date("Y-m-d");
if (isset($_POST['money'])) {
    // Insert amount into DB
    $money = filter_var($_POST['money'], FILTER_SANITIZE_NUMBER_FLOAT);
    $stmt = $con->prepare("INSERT INTO installments_money(user_id, amount, date) VALUES(?,?,now())");
    $stmt->execute(array($_POST['ID'], $money));
    // Update table interface
    $stmt2 = $con->prepare("SELECT * FROM installments_money WHERE user_id = ? ORDER BY date DESC");
    $stmt2->execute(array($_POST['ID']));
    $times = $stmt2->fetchAll();
    if (empty($times)) {
        echo '<tr class="align-middle">';
        echo '<td colspan="3"><div class="text-center alert alert-info my-0">لم يتم استلام أى مبلغ</div></td>';
        echo '</tr>';
    } else {
        foreach ($times as $time) {
            echo '<tr>';
            echo '<td>';
            echo $time['date'];
            echo '</td>';
            echo '<td>';
            echo $time['amount'];
            echo '</td>';
            echo '<td>';
            echo '<span class="btn btn-primary editM ' . $time['id'] . '">تعديل المبلغ</span>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="editM" id="' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="edit form-control justify-content-between">';
            echo '<span class="btn edit-money-close btn-danger text-center ' . $time['id'] . '">إنهاء</span>';
            echo '<input type="hidden" class="userID" value="' . $_POST['ID'] . '">';
            echo '<span class="userID" style="display: none;">' . $_POST['ID'] . '</span>';
            echo '<input type="hidden" min="0" class="oldMoney" value="' . $time['amount'] . '">';
            echo '<input type="number" min="0" step="0.01" class="col-4 ps-0 form-contorl new" name="money" value="' . $time['amount'] . '" placeholder="المبلغ">';
            echo '<span class="btn btn-success money-done-edit ' . $time['id'] . '">تعديل المبلغ</span>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="bad ' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="alert alert-danger text-center">لم يتم أى تعديل</div>';
            echo '</td>';
            echo '</tr>';
        }
    }
    // Update user table in DB
    $stmt3 = $con->prepare('SELECT SUM(amount) AS DONE FROM installments_money WHERE user_id = ? GROUP BY user_id');
    $stmt3->execute(array($_POST['ID']));
    $total = $stmt3->fetch();
    $totalDone = $total['DONE'];
    $totalDone = floatval($totalDone);
    $stmt4 = $con->prepare('UPDATE installments_users SET done = ? WHERE user_id = ?');
    $stmt4->execute(array($totalDone, $_POST['ID']));
    // Check items to make a line over the items fully paid
    // Get info from table where items are paid
    $stmt5 = $con->prepare("SELECT SUM(total_insta_price) AS totalPaid FROM installments_items WHERE paid = 1 && user_id = ?");
    $stmt5->execute(array($_POST['ID']));
    $info = $stmt5->fetch();
    $totalPaid = empty($info['totalPaid']) ? 0 : $info['totalPaid'];
    // Get info of items not paid yet
    $stmt6 = $con->prepare("SELECT * FROM installments_items WHERE paid = 0 && user_id = ? ORDER BY selling_date DESC");
    $stmt6->execute(array($_POST['ID']));
    $itemsData = $stmt6->fetchAll();
    $itemData = $itemsData[0]; // Checking the oldest unpaid item only
    if (($totalDone - $totalPaid) >= $itemData['total_insta_price']) {
        $id = $itemData['log_id'];
        // Updating the item to be paid and apply the line on it
        $stmt7 = $con->prepare("UPDATE installments_items SET paid = 1, paid_date = now() WHERE log_id = ?");
        $stmt7->execute(array($id));
    }
} elseif ($_POST['MiD']) {

    $stmt = $con->prepare('UPDATE installments_money SET amount = ? WHERE id = ?');
    $stmt->execute(array($_POST['newMoney'], $_POST['MiD']));
    $stmt2 = $con->prepare("SELECT * FROM installments_money WHERE user_id = ? ORDER BY date DESC");
    $stmt2->execute(array($_POST['ID']));
    $times = $stmt2->fetchAll();
    if (empty($times)) {
        echo '<tr class="align-middle">';
        echo '<td colspan="3"><div class="text-center alert alert-info my-0">لم يتم استلام أى مبلغ</div></td>';
        echo '</tr>';
    } else {
        foreach ($times as $time) {
            echo '<tr>';
            echo '<td>';
            echo $time['date'];
            echo '</td>';
            echo '<td>';
            echo $time['amount'];
            echo '</td>';
            echo '<td>';
            echo '<span class="btn btn-primary editM ' . $time['id'] . '">تعديل المبلغ</span>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="editM" id="' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="edit form-control justify-content-between">';
            echo '<span class="btn edit-money-close btn-danger text-center ' . $time['id'] . '">إنهاء</span>';
            echo '<input type="hidden" class="userID" value="' . $_POST['ID'] . '">';
            echo '<span class="userID" style="display: none;">' . $_POST['ID'] . '</span>';
            echo '<input type="hidden" min="0" class="oldMoney" value="' . $time['amount'] . '">';
            echo '<input type="number" min="0" step="0.01" class="col-4 ps-0 form-contorl new" name="money" value="' . $time['amount'] . '" placeholder="المبلغ">';
            echo '<span class="btn btn-success money-done-edit ' . $time['id'] . '">تعديل المبلغ</span>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="bad ' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="alert alert-danger text-center">لم يتم أى تعديل</div>';
            echo '</td>';
            echo '</tr>';
        }
    }
    $stmt3 = $con->prepare('SELECT SUM(amount) AS DONE FROM installments_money WHERE user_id = ? GROUP BY user_id');
    $stmt3->execute(array($_POST['ID']));
    $total = $stmt3->fetch();
    $totalDone = $total['DONE'];
    $totalDone = floatval($totalDone);
    $stmt4 = $con->prepare('UPDATE installments_users SET done = ? WHERE user_id = ?');
    $stmt4->execute(array($totalDone, $_POST['ID']));
    $stmt5 = $con->prepare("SELECT SUM(total_insta_price) AS totalPaid FROM installments_items WHERE paid = 1 && user_id = ?");
    $stmt5->execute(array($_POST['ID']));
    $info = $stmt5->fetch();
    $totalPaid = empty($info['totalPaid']) ? floatval(0) : floatval($info['totalPaid']);
    $stmt6 = $con->prepare("SELECT * FROM installments_items WHERE paid = 0 && user_id = ? ORDER BY selling_date DESC");
    $stmt6->execute(array($_POST['ID']));
    $itemsData = $stmt6->fetchAll();
    $itemData = $itemsData[0];
    if (($totalDone - $totalPaid) >= floatval($itemData['total_insta_price'])) {
        $id = $itemData['log_id'];
        $stmt7 = $con->prepare("UPDATE installments_items SET paid = 1, paid_date = now() WHERE log_id = ?");
        $stmt7->execute(array($id));
    }
}
