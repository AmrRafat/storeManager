<?php

use function PHPSTORM_META\type;

include_once "../../../connect.php";

if (!empty($_POST['catid'])) {
    $stmt = $con->prepare("SELECT * FROM subcats WHERE cat_id = ? AND del = 0 ORDER BY subcat_name ASC");
    $stmt->execute(array($_POST['catid']));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '<option value="0">برجاء اختيار القسم الفرعى</option>';
        $subcats = $stmt->fetchAll();
        foreach ($subcats as $subcat) {
            echo '<option ';
            echo ' value="';
            echo $subcat['subcat_id'];
            echo '">';
            echo $subcat['subcat_name'];
            echo '</option>';
        }
    } else {
        echo '<option value="0">لا يوجد أقسام فرعية</option>';
    }
} elseif (!empty($_POST['subcatid'])) {
    $stmt = $con->prepare("SELECT * FROM items WHERE subcat_id = ? AND del = 0 GROUP BY item_code ORDER BY item_name ASC");
    $stmt->execute(array($_POST['subcatid']));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '<option value="0">برجاء اختيار الصنف</option>';
        $items = $stmt->fetchAll();
        foreach ($items as $item) {
            echo '<option ';
            echo ' value="';
            echo $item['item_code'];
            echo '">';
            echo $item['item_name'];
            echo '</option>';
        }
    } else {
        echo '<option value="0">لا توجد أصناف</option>';
    }
} elseif (!empty($_POST['catid1'])) {
    $itendedItem;
    if (isset($_POST['itemcode'])) {
        $theItemCode = intval($_POST['itemcode']);
        $stmt5 = $con->prepare("SELECT * FROM items WHERE item_code = ? ORDER BY adding_date DESC");
        $stmt5->execute(array($theItemCode));
        $neededItem = $stmt5->fetchAll();
        $itendedItem = $neededItem[0];
    }
    $stmt = $con->prepare("SELECT * FROM subcats WHERE cat_id = ? AND del = 0 ORDER BY subcat_name ASC");
    $stmt->execute(array($_POST['catid1']));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '<option value="0">برجاء اختيار القسم الفرعى</option>';
        $subcats1 = $stmt->fetchAll();
        foreach ($subcats1 as $subcat1) {
            echo '<option ';
            if ($subcat1['subcat_id'] == $itendedItem['subcat_id']) {
                echo 'selected';
            }
            echo ' value="';
            echo $subcat1['subcat_id'];
            echo '">';
            echo $subcat1['subcat_name'];
            echo '</option>';
        }
    }
} else {
    echo '<option value="0">لا توجد أصناف</option>';
}
