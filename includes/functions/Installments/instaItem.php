<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
include_once "../functions.php";
$today = date("Y-m-d");

if ($_POST['add'] == 1) {
// Start Variables
    $code = $_POST['code'];
    $cat = $_POST['cat'];
    $subcat = $_POST['subcat'];
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_INT);
    $uPrice = filter_var($_POST['unitPrice'], FILTER_SANITIZE_NUMBER_FLOAT);
    $tPrice = filter_var($_POST['totalPrice'], FILTER_SANITIZE_NUMBER_FLOAT);
    $tIPrice = filter_var($_POST['totalInstaPrice'], FILTER_SANITIZE_NUMBER_FLOAT);
    $userid = $_POST['userid'];
    $seller = $_SESSION['id'];
    // Update items table sold items
    $amount2Bsold = $amount;
    $itemsid = [];
    $remaining;
    $stmt = $con->prepare("SELECT item_id, amount, amount_sold, purchase_price FROM items WHERE item_code = ? AND amount_sold < amount ORDER BY item_id");
    $stmt->execute(array($code));
    $itemids = $stmt->fetchAll();
    foreach ($itemids as $itemid) {
        $remaining = ($itemid['amount'] - $itemid['amount_sold']);
        if ($amount2Bsold >= $remaining) {
            $itemsid[] = ['itemID' => $itemid['item_id'], 'amount' => $remaining, 'old_sold' => $itemid['amount_sold'], 'price' => $itemid['purchase_price']];
            $amount2Bsold = $amount2Bsold - $remaining;
            if ($amount2Bsold == 0) {
                break;
            }
        } else {
            $itemsid[] = ['itemID' => $itemid['item_id'], 'amount' => $amount2Bsold, 'old_sold' => $itemid['amount_sold'], 'price' => $itemid['purchase_price']];
            break;
        }
    }
    $totalpPrice = 0;
    foreach ($itemsid as $thing) {
        $totalpPrice = ($thing['amount'] * $thing['price']) + $totalpPrice;
    }
    foreach ($itemsid as $items_id) {
        $stmt2 = $con->prepare("UPDATE items SET amount_sold = ? WHERE item_id = ?");
        $new_sold = $items_id['amount'] + $items_id['old_sold'];
        $stmt2->execute(array($new_sold, $items_id['itemID']));
    }
    if (checkItems() == 1) {
        echo 1;
    } else {
        echo 0;
    }
// Insert data into table of installments items
    $stmt = $con->prepare("INSERT INTO installments_items(item_code, cat_id, subcat_id, selling_date, selling_amount, unit_selling_price, total_selling_price, total_insta_price, total_purchase_price, user_id, seller_id) VALUES(?,?,?,now(),?,?,?,?,?,?,?)");
    $stmt->execute(array($code, $cat, $subcat, $amount, $uPrice, $tPrice, $tIPrice, $totalpPrice, $userid, $seller));
    // Update intallments user total required
    $stmt1 = $con->prepare("SELECT SUM(total_Insta_Price) AS totalInsta FROM installments_items WHERE user_id = ? GROUP BY user_id");
    $stmt1->execute(array($userid));
    $total = $stmt1->fetchAll();
    $totalRequired = $total[0]['totalInsta'];
    $stmt2 = $con->prepare('UPDATE installments_users SET total = ? WHERE user_id = ?');
    $stmt2->execute(array($totalRequired, $userid));
} elseif ($_POST['add'] == 2) {
    // Get log info
    $logID = $_POST['logID'];
    $stmt = $con->prepare("SELECT * FROM installments_items WHERE log_id = ?");
    $stmt->execute(array($logID));
    $delItem = $stmt->fetch();
    // Updating installments user info
    $stmt1 = $con->prepare("SELECT * FROM installments_users WHERE user_id = ?");
    $stmt1->execute(array($delItem['user_id']));
    $userInfo = $stmt1->fetch();
    $oldTotal = $userInfo['total'];
    $oldDone = $userInfo['done'];
    $newTotal = $oldTotal - $delItem['total_insta_price'];
    $newRemain = $newTotal - $oldDone;
    $stmt2 = $con->prepare("UPDATE installments_users SET total = ?, remain = ? WHERE user_id = ?");
    $stmt2->execute(array($newTotal, $newRemain, $delItem['user_id']));
    // Updating items table
    $stmt3 = $con->prepare("SELECT item_id, amount_sold FROM items WHERE item_code = ? ORDER BY item_id DESC");
    $stmt3->execute(array($delItem['item_code']));
    $itemsInfo = $stmt3->fetchAll();
    $mat = [];
    $delAmount = $delItem['selling_amount'];
    foreach ($itemsInfo as $item) {
        if ($delAmount > $item['amount_sold']) {
            $mat[$item['item_id']] = 0;
            $delAmount = $delAmount - $item['amount_sold'];
        } else {
            $mat[$item['item_id']] = $item['amount_sold'] - $delAmount;
        }
    }
    $k = array_reverse($mat, true);
    foreach ($k as $m => $v) {
        $stmt4 = $con->prepare("UPDATE items SET amount_sold = ? WHERE item_id = ?");
        $stmt4->execute(array($v, $m));
    }
    // DELETE log from installments_items
    $stmt8 = $con->prepare("DELETE FROM installments_items WHERE log_id = ?");
    $stmt8->execute(array($logID));
    // Check items to make a line over the items fully paid
    // Get total amount paid (total amount paid)
    $stmt9 = $con->prepare("SELECT done FROM installments_users WHERE user_id = ?");
    $stmt9->execute(array($delItem['user_id']));
    $totalPaidAmount = $stmt9->fetch();
    $totalDone = $totalPaidAmount['done'];
    // Get info from table where items are paid (how much is done paid)
    $stmt5 = $con->prepare("SELECT SUM(total_insta_price) AS totalPaid FROM installments_items WHERE paid = 1");
    $stmt5->execute();
    $info = $stmt5->fetch();
    $totalPaid = empty($info['totalPaid']) ? 0 : $info['totalPaid'];
    // Get info of items not paid yet
    $stmt6 = $con->prepare("SELECT * FROM installments_items WHERE paid = 0 ORDER BY selling_date DESC");
    $stmt6->execute();
    $itemsData = $stmt6->fetchAll();
    $itemData = $itemsData[0]; // Checking the oldest unpaid item only
    if (($totalDone - $totalPaid) >= $itemData['total_insta_price']) { // (totalPaid - totalDonePaid = amount paid and not assigned to an item yet)
        $id = $itemData['log_id'];
        // Updating the item to be paid and apply the line on it
        $stmt7 = $con->prepare("UPDATE installments_items SET paid = 1, paid_date = now() WHERE log_id = ?");
        $stmt7->execute(array($id));
    }
}
