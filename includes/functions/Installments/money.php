<?php
session_start();
use function PHPSTORM_META\type;
include_once "../../../connect.php";
$today = date("Y-m-d");
if (isset($_POST['money'])) { // Adding money
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
            echo '<span class="btn btn-primary editM ' . $time['id'] . '">تعديل</span>';
            echo '<span class="btn btn-danger delM me-1" data-moneyid = " ' . $time['id'] . '">استرجاع</span>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="editM" id="' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="edit form-control d-flex justify-content-between">';
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
    $lastItemUnpaid = $itemData['total_insta_price'];
    $id = $itemData['log_id'];
    while (($totalDone - $totalPaid) >= $lastItemUnpaid) {
        // Updating the item to be paid and apply the line on it
        $stmt7 = $con->prepare("UPDATE installments_items SET paid = 1, paid_date = now() WHERE log_id = ?");
        $stmt7->execute(array($id));
        $totalPaid = $totalPaid + $lastItemUnpaid;
        $stmt8 = $con->prepare("SELECT * FROM installments_items WHERE paid = 0 && user_id = ? ORDER BY selling_date DESC");
        $stmt8->execute(array($_POST['ID']));
        $itemsData = $stmt8->fetchAll();
        $lastItemUnpaid = $itemsData[0]['total_insta_price'];
        $id = $itemsData[0]['log_id'];
    }
} elseif (isset($_POST['MiD'])) { // Editing money taken
    // Upadating money db
    $stmt = $con->prepare('UPDATE installments_money SET amount = ? WHERE id = ?');
    $stmt->execute(array($_POST['newMoney'], $_POST['MiD']));
    // Updating user interface
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
            echo '<span class="btn btn-primary editM ' . $time['id'] . '">تعديل</span>';
            echo '<span class="btn btn-danger delM me-1" data-moneyid = " ' . $time['id'] . '">استرجاع</span>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="editM" id="' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="edit form-control d-flex justify-content-between">';
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
    // Getting data from money db
    $stmt3 = $con->prepare('SELECT SUM(amount) AS DONE FROM installments_money WHERE user_id = ? GROUP BY user_id');
    $stmt3->execute(array($_POST['ID']));
    $total = $stmt3->fetch();
    $totalDone = $total['DONE'];
    $totalDone = floatval($totalDone);
    // Updating data in user db
    $stmt4 = $con->prepare('UPDATE installments_users SET done = ? WHERE user_id = ?');
    $stmt4->execute(array($totalDone, $_POST['ID']));
    // Getting paid items data from items db
    $stmt5 = $con->prepare("SELECT SUM(total_insta_price) AS totalPaid FROM installments_items WHERE paid = 1 && user_id = ?");
    $stmt5->execute(array($_POST['ID']));
    $info = $stmt5->fetch();
    $totalPaid = empty($info['totalPaid']) ? floatval(0) : floatval($info['totalPaid']);
    // Getting unpaid items data from items db
    $stmt6 = $con->prepare("SELECT * FROM installments_items WHERE paid = 0 && user_id = ? ORDER BY selling_date DESC");
    $stmt6->execute(array($_POST['ID']));
    $itemsData = $stmt6->fetchAll();
    $itemData = $itemsData[0];
    $lastItmePaid = $itemData['total_insta_price'];
    $id = $itemData['log_id'];
    // If difference is +ve that means it might pay one item, if -ve it might be an input error and an item need to be unpaid again
    if (($totalDone - $totalPaid) > 0) {
        while (($totalDone - $totalPaid) >= $lastItmePaid) {
            // Updating the item to be paid and apply the line on it
            $stmt7 = $con->prepare("UPDATE installments_items SET paid = 1, paid_date = now() WHERE log_id = ?");
            $stmt7->execute(array($id));
            $totalPaid = $totalPaid + $lastItmePaid;
            $stmt6->execute(array($_POST['ID']));
            $itemsData = $stmt6->fetchAll();
            $lastItemPaid = $itemsData[0]['total_insta_price'];
            $id = $itemsData[0]['log_id'];
        }
    } elseif (($totalDone - $totalPaid) < 0) {
        while ($totalDone < $totalPaid) {
            // Getting the data of the last item paid
            $stmt8 = $con->prepare("SELECT * FROM installments_items WHERE paid = 1 && user_id = ? ORDER BY paid_date DESC, log_id DESC");
            $stmt8->execute(array($_POST['ID']));
            $paidItemData = $stmt8->fetchAll();
            $neededItem = $paidItemData[0]['log_id'];
            // Update that item to be unpaid
            $stmt9 = $con->prepare("UPDATE installments_items SET paid = 0, paid_date = NULL WHERE log_id = ?");
            $stmt9->execute(array($neededItem));
            // Updating totalPaid variable
            $totalPaid = $totalPaid - floatval($paidItemData[0]['total_insta_price']);
        }
    }
} elseif (isset($_POST['moneyid'])) { // Deleting money from db
    // Set a variable of the $_POST
    $moneyid = $_POST['moneyid'];
    // Get all data from the money log
    $stmt = $con->prepare("SELECT * FROM installments_money WHERE id = ?");
    $stmt->execute(array($moneyid));
    $moneyData = $stmt->fetch();
    // Setting variables
    $amount = floatval($moneyData['amount']);
    $user = $moneyData['user_id'];
    // Get data for the user
    $stmt1 = $con->prepare("SELECT * FROM installments_users WHERE user_id = ?");
    $stmt1->execute(array($user));
    $userData = $stmt1->fetch();
    // Setting user variables
    $total = floatval($userData['total']);
    $oldDone = floatval($userData['done']);
    $oldRemain = floatval($userData['remain']);
    // Make calculations
    $newDone = $oldDone - $amount;
    $newRemain = $total - $newDone;
    // Updating the user in db
    $stmt2 = $con->prepare('UPDATE installments_users SET done = ?, remain = ? WHERE user_id = ?');
    $stmt2->execute(array($newDone, $newRemain, $user));
    // Check the installments items to make a dash or remove it
    $stmt3 = $con->prepare("SELECT SUM(total_insta_price) AS totalPaid FROM installments_items WHERE paid = 1 && user_id = ?");
    $stmt3->execute(array($user));
    $itemsData = $stmt3->fetch();
    $totalPaid = (!isset($itemsData['totalPaid'])) ? 0 : floatval($itemsData['totalPaid']);
    // Compare and make decision
    while ($totalPaid > $newDone) {
        // Get the last paid item
        $stmt4 = $con->prepare("SELECT * FROM installments_items WHERE paid = 1 && user_id = ? ORDER BY paid_date DESC");
        $stmt4->execute(array($user));
        $lastItemData = $stmt4->fetchAll();
        $lastItemID = $lastItemData[0]['log_id'];
        // Updating this item paid and paid_date fields
        $stmt5 = $con->prepare('UPDATE installments_items SET paid = 0, paid_date = NULL WHERE log_id = ?');
        $stmt5->execute(array($lastItemID));
        // Updating totalPaid variable
        $stmt3->execute(array($user));
        $itemsData = $stmt3->fetch();
        $totalPaid = (!isset($itemsData['totalPaid'])) ? 0 : floatval($itemsData['totalPaid']);
    }
    // Delete the money log itself
    $stmt6 = $con->prepare('DELETE FROM installments_money WHERE id = ?');
    $stmt6->execute(array($moneyid));
    // Update user interface
    $stmt7 = $con->prepare("SELECT * FROM installments_money WHERE user_id = ? ORDER BY date DESC");
    $stmt7->execute(array($user));
    $times = $stmt7->fetchAll();
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
            echo '<span class="btn btn-danger delM me-1" data-moneyid = " ' . $time['id'] . '">استرجاع</span>';
            echo '</td>';
            echo '</tr>';
            echo '<tr class="editM" id="' . $time['id'] . '">';
            echo '<td colspan="3">';
            echo '<div class="edit form-control d-flex justify-content-between">';
            echo '<span class="btn edit-money-close btn-danger text-center ' . $time['id'] . '">إنهاء</span>';
            echo '<input type="hidden" class="userID" value="' . $user . '">';
            echo '<span class="userID" style="display: none;">' . $user . '</span>';
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
}
