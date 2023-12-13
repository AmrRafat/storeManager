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
                <tr class="align-middle">
                    <th class="px-1 codes codeASC ">الكود</th>
                    <th class="px-1 names ">اسم الصنف</th>
                    <th class="px-1 cats ">اسم القسم</th>
                    <th class="px-1 subcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes codeDESC ">الكود</th>
                    <th class="px-1 names ">اسم الصنف</th>
                    <th class="px-1 cats ">اسم القسم</th>
                    <th class="px-1 subcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes ">الكود</th>
                    <th class="px-1 names nameASC ">اسم الصنف</th>
                    <th class="px-1 cats ">اسم القسم</th>
                    <th class="px-1 subcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes ">الكود</th>
                    <th class="px-1 names nameDESC ">اسم الصنف</th>
                    <th class="px-1 cats ">اسم القسم</th>
                    <th class="px-1 subcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes ">الكود</th>
                    <th class="px-1 names ">اسم الصنف</th>
                    <th class="px-1 cats catASC ">اسم القسم</th>
                    <th class="px-1 subcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes ">الكود</th>
                    <th class="px-1 names ">اسم الصنف</th>
                    <th class="px-1 cats catDESC ">اسم القسم</th>
                    <th class="px-1 subcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes ">الكود</th>
                    <th class="px-1 names ">اسم الصنف</th>
                    <th class="px-1 cats ">اسم القسم</th>
                    <th class="px-1 subcats subcatASC ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 codes ">الكود</th>
                    <th class="px-1 names ">اسم الصنف</th>
                    <th class="px-1 cats ">اسم القسم</th>
                    <th class="px-1 subcats subcatDESC ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Cats'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 catcodes codeASC ">الكود</th>
                    <th class="px-1 catnames ">اسم الصنف</th>
                    <th class="px-1 catsubcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 catcodes codeDESC ">الكود</th>
                    <th class="px-1 catnames ">اسم الصنف</th>
                    <th class="px-1 catsubcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 catcodes ">الكود</th>
                    <th class="px-1 catnames nameASC">اسم الصنف</th>
                    <th class="px-1 catsubcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 catcodes">الكود</th>
                    <th class="px-1 catnames nameDESC">اسم الصنف</th>
                    <th class="px-1 catsubcats ">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 catcodes ">الكود</th>
                    <th class="px-1 catnames">اسم الصنف</th>
                    <th class="px-1 catsubcats subcatASC">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
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
                <tr class="align-middle">
                    <th class="px-1 catcodes">الكود</th>
                    <th class="px-1 catnames">اسم الصنف</th>
                    <th class="px-1 catsubcats subcatDESC">القسم الفرعى</th>
                    <th class="px-1 ">تاريخ الشراء</th>
                    <th class="px-1 ">سعر الشراء</th>
                    <th class="px-1 ">المتاح</th>
                    <th>التحكم</th></th>
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
                    <td class="px-1"><?php echo $row['item_code'] ?></td>
                    <td class="px-1"><?php echo $row['item_name'] ?></td>
                    <td class="px-1"><?php echo $row['Subcats'] ?></td>
                    <td class="px-1"><?php echo $info['adding_date'] ?></td>
                    <td class="px-1"><?php echo $info['purchase_price'] ?></td>
                    <td class="px-1"><?php echo $availableAmount ?></td>
                    <td class="px-1">
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-1 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
}