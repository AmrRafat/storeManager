<?php

use function PHPSTORM_META\type;

include_once "../../../connect.php";

if ($_POST['no'] == 1) {
    if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
        $stmt = $con->prepare("SELECT logs.*, items.item_name AS item_name, cats.cat_name AS cat_name, subcats.subcat_name AS subcat_name, users.fullname AS seller FROM logs INNER JOIN items ON logs.item_code = items.item_code INNER JOIN cats ON logs.cat_id = cats.cat_id INNER JOIN subcats ON logs.subcat_id = subcats.subcat_id INNER JOIN users ON logs.user_id = users.user_id WHERE logs.del = 0 AND logs.selling_date >= ? AND logs.selling_date <= ? ORDER BY logs.log_id");
        $stmt->execute(array($_POST['from_date'], $_POST['to_date']));
        $count = $stmt->rowCount();
        if ($count > 0) {
            $logs = $stmt->fetchAll();
            ?>
        <table class="table table-stripped">
        <thead>
            <tr>
                <th>الكود</th>
                <th>الاسم</th>
                <th>القسم</th>
                <th>الفسم الفرعى</th>
                <th>التاريخ</th>
                <th>الكمية</th>
                <th>سعر القطعة</th>
                <th>إجمالى السعر</th>
                <th>البائع</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
foreach ($logs as $log) {
                echo '<tr data-id="' . $log['log_id'] . '">';
                echo '<td>' . $log['item_code'] . '</td>';
                echo '<td>' . $log['item_name'] . '</td>';
                echo '<td>' . $log['cat_name'] . '</td>';
                echo '<td>' . $log['subcat_name'] . '</td>';
                echo '<td>' . $log['selling_date'] . '</td>';
                echo '<td>' . $log['selling_amount'] . '</td>';
                echo '<td>' . $log['unit_selling_price'] . '</td>';
                echo '<td>' . $log['total_selling_price'] . '</td>';
                echo '<td>' . $log['seller'] . '</td>';
                echo '<td><span class="btn btn-primary return">استرجاع</span></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        </table>
        <?php
} else {
            echo '<div class="text-center alert alert-info">لا توجد أى سجلات للفترة التى تم تحديدها</div>';
        }
    }
} elseif ($_POST['no'] == 2) {
    // Get log info
    $stmt2 = $con->prepare("SELECT * FROM logs WHERE log_id = ?");
    $stmt2->execute(array($_POST['id']));
    $delItem = $stmt2->fetch();
    // Update items table
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
    // Delete from logs table
    $stmt5 = $con->prepare("DELETE FROM logs WHERE log_id = ?");
    $stmt5->execute(array($_POST['id']));
    // Update user interface
    $stmt = $con->prepare("SELECT DISTINCT logs.*, items.item_name AS item_name, cats.cat_name AS cat_name, subcats.subcat_name AS subcat_name, users.fullname AS seller FROM logs INNER JOIN items ON logs.item_code = items.item_code INNER JOIN cats ON logs.cat_id = cats.cat_id INNER JOIN subcats ON logs.subcat_id = subcats.subcat_id INNER JOIN users ON logs.user_id = users.user_id WHERE logs.del = 0 ORDER BY logs.log_id DESC");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أى سجلات</div>';
    } else {
        $logs = $stmt->fetchAll();
        ?>
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>الاسم</th>
                    <th>القسم</th>
                    <th>الفسم الفرعى</th>
                    <th>التاريخ</th>
                    <th>الكمية</th>
                    <th>سعر القطعة</th>
                    <th>إجمالى السعر</th>
                    <th>البائع</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
foreach ($logs as $log) {
            echo '<tr data-id="' . $log['log_id'] . '">';
            echo '<td>' . $log['item_code'] . '</td>';
            echo '<td>' . $log['item_name'] . '</td>';
            echo '<td>' . $log['cat_name'] . '</td>';
            echo '<td>' . $log['subcat_name'] . '</td>';
            echo '<td>' . $log['selling_date'] . '</td>';
            echo '<td>' . $log['selling_amount'] . '</td>';
            echo '<td>' . $log['unit_selling_price'] . '</td>';
            echo '<td>' . $log['total_selling_price'] . '</td>';
            echo '<td>' . $log['seller'] . '</td>';
            echo '<td><span class="btn btn-primary return">استرجاع</span></td>';
            echo '</tr>';
        }
        ?>
            </tbody>
            </table>
            <?php
}
} elseif ($_POST['no'] == 3) {
    // Get log info
    $stmt2 = $con->prepare("SELECT * FROM logs WHERE log_id = ?");
    $stmt2->execute(array($_POST['id']));
    $delItem = $stmt2->fetch();
    // Update items table
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
    // Delete from logs table
    $stmt5 = $con->prepare("DELETE FROM logs WHERE log_id = ?");
    $stmt5->execute(array($_POST['id']));
    // Update user interface
    $currentDate = date('Y-m-d');
    $stmt = $con->prepare("SELECT   DISTINCT logs.*,
                                        cats.cat_name AS cat_name,
                                        subcats.subcat_name AS subcat_name,
                                        items.item_name AS item_name
                                        FROM logs
                                        INNER JOIN cats
                                        ON logs.cat_id = cats.cat_id
                                        INNER JOIN subcats
                                        ON logs.subcat_id = subcats.subcat_id
                                        INNER JOIN items
                                        ON logs.item_code = items.item_code
                                        WHERE logs.selling_date = ?
                                        GROUP BY logs.log_id");
    $stmt->execute(array($currentDate));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا يوجد أى سجلات لهذا اليوم</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
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
            <?php
}
}