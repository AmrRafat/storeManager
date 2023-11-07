<?php

use function PHPSTORM_META\type;

include_once "../../../connect.php";

$way = isset($_POST['way']) ? $_POST['way'] : 'codeASC';

if ($way == 'codeASC') {
// Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY item_code');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes codeASC ">كود الصنف</th>
                    <th class="names ">اسم الصنف</th>
                    <th class="cats ">اسم القسم</th>
                    <th class="subcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'codeDESC') {
// Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY item_code DESC');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes codeDESC ">كود الصنف</th>
                    <th class="names ">اسم الصنف</th>
                    <th class="cats ">اسم القسم</th>
                    <th class="subcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code DESC");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY item_code DESC, adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'nameASC') {
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY item_name');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes ">كود الصنف</th>
                    <th class="names nameASC ">اسم الصنف</th>
                    <th class="cats ">اسم القسم</th>
                    <th class="subcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_name");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY item_name, adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'nameDESC') {
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY item_name DESC');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes ">كود الصنف</th>
                    <th class="names nameDESC ">اسم الصنف</th>
                    <th class="cats ">اسم القسم</th>
                    <th class="subcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_name DESC");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY item_name DESC, adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catASC') {
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY Cats');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes ">كود الصنف</th>
                    <th class="names ">اسم الصنف</th>
                    <th class="cats catASC ">اسم القسم</th>
                    <th class="subcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catDESC') {
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY Cats DESC');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes ">كود الصنف</th>
                    <th class="names ">اسم الصنف</th>
                    <th class="cats catDESC ">اسم القسم</th>
                    <th class="subcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'subcatASC') {
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY Subcats');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes ">كود الصنف</th>
                    <th class="names ">اسم الصنف</th>
                    <th class="cats ">اسم القسم</th>
                    <th class="subcats subcatASC ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'subcatDESC') {
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, cats.cat_name as Cats, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN cats ON items.cat_id = cats.cat_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 GROUP BY item_code ORDER BY Subcats DESC');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center">
                <tr>
                    <th class="codes ">كود الصنف</th>
                    <th class="names ">اسم الصنف</th>
                    <th class="cats ">اسم القسم</th>
                    <th class="subcats subcatDESC ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Cats'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catcodeASC') {
    // Get cat code
    $catCode = $_POST['cat'];
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 AND items.cat_id = ? GROUP BY item_code ORDER BY item_code');
    $stmt->execute(array($catCode));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center" data-cat='<?php echo $catCode ?>'>
                <tr>
                    <th class="catcodes codeASC ">كود الصنف</th>
                    <th class="catnames ">اسم الصنف</th>
                    <th class="catsubcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catcodeDESC') {
    // Get cat code
    $catCode = $_POST['cat'];
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 AND items.cat_id = ? GROUP BY item_code ORDER BY item_code DESC');
    $stmt->execute(array($catCode));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center" data-cat='<?php echo $catCode ?>'>
                <tr>
                    <th class="catcodes codeDESC ">كود الصنف</th>
                    <th class="catnames ">اسم الصنف</th>
                    <th class="catsubcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catnameASC') {
    // Get cat code
    $catCode = $_POST['cat'];
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 AND items.cat_id = ? GROUP BY item_code ORDER BY item_name');
    $stmt->execute(array($catCode));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center" data-cat='<?php echo $catCode ?>'>
                <tr>
                    <th class="catcodes ">كود الصنف</th>
                    <th class="catnames nameASC">اسم الصنف</th>
                    <th class="catsubcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catnameDESC') {
    // Get cat code
    $catCode = $_POST['cat'];
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 AND items.cat_id = ? GROUP BY item_code ORDER BY item_name DESC');
    $stmt->execute(array($catCode));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center" data-cat='<?php echo $catCode ?>'>
                <tr>
                    <th class="catcodes">كود الصنف</th>
                    <th class="catnames nameDESC">اسم الصنف</th>
                    <th class="catsubcats ">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catsubcatASC') {
    // Get cat code
    $catCode = $_POST['cat'];
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 AND items.cat_id = ? GROUP BY item_code ORDER BY Subcats');
    $stmt->execute(array($catCode));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center" data-cat='<?php echo $catCode ?>'>
                <tr>
                    <th class="catcodes ">كود الصنف</th>
                    <th class="catnames">اسم الصنف</th>
                    <th class="catsubcats subcatASC">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
} elseif ($way == 'catsubcatDESC') {
    // Get cat code
    $catCode = $_POST['cat'];
    // Show all info
    $stmt = $con->prepare('SELECT items.*, users.fullname AS fullname, subcats.subcat_name AS Subcats FROM items INNER JOIN users ON items.user_id = users.user_id INNER JOIN subcats ON items.subcat_id = subcats.subcat_id WHERE items.del=0 AND items.cat_id = ? GROUP BY item_code ORDER BY Subcats DESC');
    $stmt->execute(array($catCode));
    $rows = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if ($count == 0) {
        echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
    } else {
        ?>
            <table class="table table-striped text-center" data-cat='<?php echo $catCode ?>'>
                <tr>
                    <th class="catcodes">كود الصنف</th>
                    <th class="catnames">اسم الصنف</th>
                    <th class="catsubcats subcatDESC">اسم القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>أقل عدد</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
            // Get available amount
            $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE del = 0 AND item_code = ? GROUP BY item_code ORDER BY item_code");
            $stmt1->execute(array($row['item_code']));
            $data = $stmt1->fetch();
            $availableAmount = floatval($data['totalAmount']) - floatval($data['totalSold']);
            // Get latest info about adding date and price
            $stmt3 = $con->prepare("SELECT * FROM items WHERE del = 0 AND item_code = ? ORDER BY adding_date DESC");
            $stmt3->execute(array($row['item_code']));
            $allInfo = $stmt3->fetchAll();
            $info = $allInfo[0];
            ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['Subcats'] ?></td>
                    <td><?php echo $info['adding_date'] ?></td>
                    <td><?php echo $info['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td><?php echo $row['least_amount'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
}