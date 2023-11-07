<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
$today = date("Y-m-d");

if ($_POST['code'] != 0) {
    $stmt = $con->prepare('SELECT *, SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE item_code = ? GROUP BY item_code');
    $stmt->execute(array($_POST['code']));
    $items = $stmt->fetchAll();
    $availAmount = $items[0]['totalAmount'] - $items[0]['totalSold'];
    $items[0]['availAmount'] = $availAmount;
    echo (json_encode($items[0]));
} elseif ($_POST['cat']) {
    $stmt = $con->prepare("SELECT * FROM subcats WHERE cat_id = ? ORDER BY subcat_name");
    $stmt->execute(array($_POST['cat']));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '<option value="0">برجاء اختيار القسم الفرعى</option>';
        $subcats = $stmt->fetchAll();
        foreach ($subcats as $subcat) {
            echo '<option ';
            if ($subcat['subcat_id'] == $_POST['subcat1']) {
                echo 'selected';
            }
            echo ' value="';
            echo $subcat['subcat_id'];
            echo '">';
            echo $subcat['subcat_name'];
            echo '</option>';
        }
    } else {
        echo '<option value="0">لا يوجد أقسام فرعية</option>';
    }
} elseif ($_POST['subcat']) {
    $stmt = $con->prepare("SELECT * FROM items WHERE subcat_id = ? GROUP BY item_code ORDER BY item_name");
    $stmt->execute(array($_POST['subcat']));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '<option value="0">برجاء اختيار القسم الفرعى</option>';
        $items1 = $stmt->fetchAll();
        foreach ($items1 as $item1) {
            echo '<option ';
            if ($item1['item_code'] == $_POST['item']) {
                echo 'selected';
            }
            echo ' value="';
            echo $item1['item_code'];
            echo '">';
            echo $item1['item_name'];
            echo '</option>';
        }
    } else {
        echo '<option value="0">لا توجد أى أصناف</option>';
    }
}
