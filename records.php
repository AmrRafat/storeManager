<?php
ob_start();
session_start();
unset($_POST['cat']);
unset($_POST['subcat']);
if (isset($_SESSION['username'])) {
    $pageTitle = 'قصر الملكة';
    include "init.php";
    $today = date("j / n / Y");
    $application = isset($_GET['application']) ? $_GET['application'] : 'show';
    ?>
<div class="container">
<input type="hidden" class="title" value="records">
<?php
if ($application == 'show') {
        ?>
    <h1 class="text-center">السجل</h1>
    <div class="card row mt-4">
        <div class="card-header d-flex justify-content-between">
            <h3>سجل يوم :
                <?php
echo $today;
        ?>
            </h3>
            <div class="btns">
                <span class="btn btn-primary new-record" style="cursor: pointer;">بيع جديد</span>
                <a class="text-decoration-none btn btn-primary" href="records.php?application=old">عرض سجل قديم</a>
            </div>
        </div>
        <div class="card-body start">
            <div class="input-group mb-4 mt-3 new-record-form">
                <span style="position: absolute; top:-18px;right:-8px; cursor:pointer;"><i class="fa-regular fa-circle-xmark close-new-record btn btn-danger rounded-circle" style="padding: 7px; font-size: 20px; "></i></span>
                <div class="form form-control text-center">
                    <div class="row p-3">
                    <div class="col-1 ps-1">
                    <select class="form-control text-center z-3" required = "required" name="code" id="code">
                                <option value="0">الكود</option>
                                <?php
$stmt = $con->prepare("SELECT * FROM items WHERE del = 0 GROUP BY item_code ORDER BY item_code");
        $stmt->execute();
        $codes = $stmt->fetchAll();
        foreach ($codes as $code) {
            echo '<option ';
            echo ' value="';
            echo $code['item_code'];
            echo '">';
            echo $code['item_code'];
            echo '</option>';
        }
        ?>
                            </select>
                    </div>
                    <div class="col-2 ps-1 pe-1">
                    <select class="form-control text-center z-3" required = "required" name="cat" id="cat">
                                <option value="0">القسم</option>
                                <?php
$stmt = $con->prepare("SELECT * FROM cats WHERE del = 0 ORDER BY cat_name");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        foreach ($cats as $cat) {
            echo '<option ';
            if (isset($selectedCat)) {
                if ($cat['cat_id'] == $selectedCat) {
                    echo 'selected';
                }
            }
            echo ' value="';
            echo $cat['cat_id'];
            echo '">';
            echo $cat['cat_name'];
            echo '</option>';
        }
        ?>
                            </select>
                    </div>
                    <div class="col-2 ps-1 pe-1">
                            <select name="subcat" class="form-control text-center z-3" required = "required" id="subcat">
                            <option selected value="0">برجاء اختيار القسم أولا</option>
                                <?php
if (isset($selectedSubCat) && $selectedSubCat == 0) {?>
                            <option selected value="0">برجاء اختيار القسم أولا</option>
                            <?php } elseif (isset($selectedSubCat)) {
            $stmt = $con->prepare("SELECT * FROM subcats WHERE del = 0 ORDER BY subcat_name");
            $stmt->execute();
            $subcats = $stmt->fetchAll();
            foreach ($subcats as $subcat) {
                echo '<option ';
                if (isset($selectedSubCat) && $selectedSubCat != 0) {
                    if ($subcat['subcat_id'] == $selectedSubCat) {
                        echo 'selected';
                    }
                }
                echo ' value="';
                echo $subcat['subcat_id'];
                echo '">';
                echo $subcat['subcat_name'];
                echo '</option>';
            }
        }
        ?>
                            </select>
                        </div>
                        <div class="col-2 ps-1 pe-1">
                            <select name="item1" class="form-control text-center z-3" required = "required" id="item1">
                            <option selected value="0">برجاء اختيار القسم أولا</option>
                                <?php
if (isset($selectedSubCat) && $selectedSubCat == 0) {?>
                            <option selected value="0">برجاء اختيار القسم أولا</option>
                            <?php } elseif (isset($selectedSubCat)) {
            $stmt = $con->prepare("SELECT * FROM items WHERE del = 0 ORDER BY item_name");
            $stmt->execute();
            $items = $stmt->fetchAll();
            foreach ($items as $item) {
                echo '<option ';
                echo ' value="';
                echo $item['item_code'];
                echo '">';
                echo $item['item_name'];
                echo '</option>';
            }
        }
        ?>
                            </select>
                        </div>
                        <div class="col-1 ps-1 pe-1">
                            <input type="text" disabled class="z-3 ps-0 text-center avail form-control" name="avail" required='required' autocomplete="off" value="المتاح">
                        </div>
                        <div class="col-1 ps-1 pe-1">
                            <input type="number" min="1" max="" class="z-3 ps-0 text-center amount form-control" name="selling_amount" required='required' autocomplete="off" placeholder="الكمية">
                        </div>
                        <div class="col-2 ps-1 pe-1">
                            <input type="number" min="0" step="0.01" class="z-3 ps-0 text-center form-control unit-price" name="unit_price" required='required' autocomplete="off" placeholder="سعر القطعة">
                        </div>
                        <div class="col-1 pe-1">
                            <input type="number" min="0" step="0.01" class="z-3 ps-0 total-price text-center form-control" name="total" required='required' autocomplete="off" placeholder="الإجمالى">
                        </div>
                    </div>
                    <button class="submit-button" type="submit" style="border: none; position:absolute; top:0; left:0;">
                        <span style="position: absolute; top:-18px;left:-8px; cursor:pointer;"><i class="fa-regular fa-circle-check btn btn-success rounded-circle" style="padding: 7px; font-size: 20px; "></i></span>
                    </button>
                </div>
            </div>
            <div class="errorMsg alert alert-danger text-center" style="display: none;">برجاء إكمال جميع الاختيارات</div>
            <div class="errorMsgAmount alert alert-danger text-center" style="display: none;">لقد تعديت الكمية المتاحة</div>
            <div class="successMsg alert alert-success text-center" style="display: none;">تمت الإضافة بنجاح</div>
            <div class="showing-data">
                <?php
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
                <?php }?>
            </div>
        </div>
        </div>
    <?php
} elseif ($application = 'old') {
        ?>
        <a href="records.php?application=old" class="text-decoration-none text-reset">
            <h1 class="text-center">عرض السجلات القديمة</h1>
        </a>
    <div class="card">
        <div class="card-header">
            <div class="text-center">
                <div class="row">
                    <div class="col-3">
                        <div class="input-group">
                            <label for="from" class="col-form-label">من</label>
                            <input type="date" class="form-control me-3 text-center" name="from" id="from">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <label for="to" class="col-form-label">إلى</label>
                            <input type="date" class="form-control me-3 text-center" name="to" id="to">
                        </div>
                    </div>
                    <div class="col text-start">
                        <button class="old-logs btn btn-primary">عرض</button>
                        <button class="btn btn-primary"><a href="records.php?application=old" class="text-decoration-none text-reset">عرض الكل</a></button>
                        <button class="btn btn-primary"><a href="records.php" class="text-decoration-none text-reset">رجوع</a></button>
                    </div>
                </div>
            </div>
            </div>
            <div class="card-body old-logs-data">
                <?php
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
        ?>
        </div>
    </div>
    <?php }?>
</div>
<?php
include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
