<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");
$itemsid = [];
$amount2Bsold = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_INT);
$remaining;
// Get the right items to get the amount from
$stmt = $con->prepare("SELECT item_id, amount, amount_sold, purchase_price FROM items WHERE item_code = ? AND amount_sold < amount ORDER BY item_id");
$stmt->execute(array($_POST['code']));
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
$stmt1 = $con->prepare("INSERT INTO logs(item_code, cat_id, subcat_id, selling_date, selling_amount, unit_selling_price, total_selling_price, total_purchase_price, user_id) VALUES(?,?,?,now(),?,?,?,?,?)");
$stmt1->execute(array($_POST['code'], $_POST['cat'], $_POST['subcat'], $amount2Bsold, filter_var($_POST['unitPrice'], FILTER_SANITIZE_NUMBER_FLOAT), filter_var($_POST['totalPrice'], FILTER_SANITIZE_NUMBER_FLOAT), $totalpPrice, $_SESSION['id']));
foreach ($itemsid as $items_id) {
    $stmt2 = $con->prepare("UPDATE items SET amount_sold = ? WHERE item_id = ?");
    $new_sold = $items_id['amount'] + $items_id['old_sold'];
    $stmt2->execute(array($new_sold, $items_id['itemID']));
}
$stmt3 = $con->prepare("SELECT DISTINCT logs.*, cats.cat_name AS cat_name, subcats.subcat_name AS subcat_name, items.item_name AS item_name FROM logs INNER JOIN cats ON logs.cat_id = cats.cat_id INNER JOIN subcats ON logs.subcat_id = subcats.subcat_id INNER JOIN items ON logs.item_code = items.item_code WHERE logs.selling_date = ? GROUP BY logs.log_id");
$stmt3->execute(array($today));
$rows = $stmt3->fetchAll();
$count = $stmt3->rowCount();
?>
            <table class="table text-center table-striped">
                <tr>
                    <th>كود الصنف</th>
                    <th>اسم الصنف</th>
                    <th>القسم</th>
                    <th>القسم الفرعى</th>
                    <th>العدد المباع</th>
                    <th>سعر القطعة</th>
                    <th>إجمالى السعر</th>
                    <th></th>
                </tr>
                <?php
foreach ($rows as $row) {?>
                    <tr class="align-middle" data-id=<?php echo $row['log_id'] ?>>
                        <td><?php echo $row['item_code'] ?></td>
                        <td><?php echo $row['item_name'] ?></td>
                        <td><?php echo $row['cat_name'] ?></td>
                        <td><?php echo $row['subcat_name'] ?></td>
                        <td><?php echo $row['selling_amount'] ?></td>
                        <td><?php echo $row['unit_selling_price'] ?></td>
                        <td><?php echo $row['total_selling_price'] ?></td>
                        <td><span class="btn btn-primary returnItem">استرجاع</span></td>
                    </tr>
                    <?php }?>
                </table>
