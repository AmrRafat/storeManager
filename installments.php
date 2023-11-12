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
<input type="hidden" class="title" value="insta">
<?php
if ($application == 'show') {
        ?>
    <h1 class="text-center">الأقساط</h1>
    <!-- Names -->
    <div class="card row mt-4">
        <div class="card-header d-flex justify-content-between">
            <h3>كشف الأسماء</h3>
            <div class="btns">
                <span class="btn btn-primary new-user" style="cursor: pointer;">اسم جديد</span>
            </div>
        </div>
        <div class="card-body start">
        <div class="errorMsg alert alert-danger text-center" style="display: none;">برجاء إكمال جميع الاختيارات</div>
        <form action="#" class="form-control w-50 mx-auto new-user-form mb-3 py-3">
            <div class="input-group">
                <button type="button" class="btn-danger btn close-new-user">إغلاق</button>
                <span class="input-group-text">الاسم</span>
                <input type="text" name="user" placeholder="......" class="text-center form-control">
                <button type="submit" class="btn btn-success submit-user-button">إضافة الاسم</button>
            </div>
        </form>
            <!-- <div class="input-group mb-4 mt-3 new-user-form">
                <div class="form form-control text-center p-3">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-1 close-new-user btn btn-danger">
                            إنهاء
                        </div>
                        <div class="col-5 px-4">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <label class="label-form">الاسم</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="z-3 ps-0 text-center form-control" name="user" required='required' autocomplete="off" placeholder="الاسم">
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <button class="submit-user-button btn btn-success" type="submit" >
                            إضافة الاسم
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="showing-data">
                <?php
$currentDate = date('Y-m-d');
        $stmt = $con->prepare("SELECT * FROM installments_users ORDER BY username");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if (empty($rows) || empty($rows[0]['username'])) {
            echo '<div class="text-center alert alert-info">لا أحد عليه أقساط</div>';
        } else {
            ?>
            <div class="row row-cols-5 gap-2 justify-content-center">
                    <?php
foreach ($rows as $row) {?>
    <a href="?application=info&userid=<?php echo $row['user_id'] ?>" class="col text-center instaName rounded-4 text-decoration-none btn btn-primary">
    <div>
        <h3><?php echo $row['username'] ?></h3>
        <span>المتبقى: <?php echo $row['remain'] ?></span>
    </div>
    </a>
                    <?php }?>
                </div>
                <?php }?>
            </div>
        </div>
        </div>
    <?php
} elseif ($application == 'info') {
        // Inforamtion of each name
        $userID = $_GET['userid'];
        // Making sure that info is updated correctly
        $stmt1 = $con->prepare("SELECT SUM(total_Insta_Price) AS totalInsta FROM installments_items WHERE user_id = ? GROUP BY user_id");
        $stmt1->execute(array($userID));
        $total = $stmt1->fetchAll();
        $check = $stmt1->rowCount();
        $totalRequired = $check != 0 ? $total[0]['totalInsta'] : 0;
        $stmt2 = $con->prepare("SELECT SUM(amount) AS moneyDone FROM installments_money WHERE user_id = ? GROUP BY user_id");
        $stmt2->execute(array($userID));
        $done = $stmt2->fetchAll();
        $totalDone = (!empty($done)) ? $done[0]['moneyDone'] : 0;
        $totalRemain = $totalRequired - $totalDone;
        $stmt4 = $con->prepare('UPDATE installments_users SET total = ?, done = ?, remain = ? WHERE user_id = ?');
        $stmt4->execute(array($totalRequired, $totalDone, $totalRemain, $userID));
        // Showing data of user
        $stmt = $con->prepare("SELECT * FROM installments_users WHERE user_id = ?");
        $stmt->execute(array($userID));
        $datas = $stmt->fetchAll();
        $info = $stmt->fetch();
        $data = $datas[0];
        ?>
    <h1 class="text-center"><?php echo $data['username'] ?></h1>
<div class="card mt-5">
    <div class="card-header">
        <div class="row">
            <div class="col text-center">
                <label for="total" class="form-label">الإجمالى</label>
                <input type="text" class="form-control text-center" id="total" value="<?php echo $data['total'] ?>" readonly>
            </div>
            <div class="col text-center">
                <label for="done" class="form-label">الذى تم استلامه</label>
                <input type="text" class="form-control text-center" id="done" value="<?php echo $data['done'] ?>" readonly>
            </div>
            <div class="col text-center">
                <label for="remain" class="form-label">المتبقى</label>
                <input type="text" class="form-control text-center" id="remain" value="<?php echo $data['remain'] ?>" readonly>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="my-0">
                            معلومات الشراء
                        </h4>
                        <a href="installments.php?application=add&userid=<?php echo $userID ?>" class="btn btn-primary text-decoration-none">إضافة</a>
                    </div>
                    <div class="card-body text-center">
                        <div class="alert alert-danger text-center delItem">تم المسح بنجاح</div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>تاريخ البيع</th>
                                        <th>(كود) الصنف</th>
                                        <th>الكمية</th>
                                        <th>السعر</th>
                                        <th>السعر بالقسط</th>
                                        <th>التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$stmt1 = $con->prepare("SELECT DISTINCT installments_items.*, items.item_name AS item_name FROM installments_items INNER JOIN items ON installments_items.item_code = items.item_code WHERE installments_items.user_id = ? GROUP BY log_id ORDER BY log_id DESC");
        $stmt1->execute(array($userID));
        $items = $stmt1->fetchAll();
        if (empty($items)) {
            echo '<tr class="align-middle">';
            echo '<td colspan="6"><div class="text-center alert alert-info my-0">لم يتم إضافة أى صنف لهذا المشترى بعد</div></td>';
            echo '</tr>';
        } else {
            foreach ($items as $item) {
                echo '<tr class="align-middle" data-done="' . $item['paid'] . '">';
                echo '<td>';
                echo $item['selling_date'];
                echo '</td>';
                echo '<td>';
                echo '(' . $item['item_code'] . ') ' . $item['item_name'];
                echo '</td>';
                echo '<td>';
                echo $item['selling_amount'];
                echo '</td>';
                echo '<td>';
                echo $item['total_selling_price'];
                echo '</td>';
                echo '<td>';
                echo $item['total_insta_price'];
                echo '</td>';
                echo '<td>';
                echo '<span class="btn btn-danger delLog" data-log="' . $item['log_id'] . '">حذف</span>';
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="my-0">
                            الأقساط المستلمة
                        </h4>
                        <span class="btn btn-primary receive-btn">استلام</span>
                    </div>
                    <div class="card-body text-center installments-money">
                        <div class="alert alert-danger text-center error" style="display: none;">برجاء إدخال قيمةالمبلغ</div>
                        <div class="alert alert-success text-center success" style="display: none;">تم استلام المبلغ</div>
                        <div class="receive form-control justify-content-between">
                            <span class="btn money-close btn-danger text-center">إنهاء</span>
                            <input type="hidden" class="userID" value="<?php echo $_GET['userid'] ?>">
                            <input type="number" min="0" step="0.01" class="col-4 ps-0 money form-contorl" name="money" placeholder="المبلغ">
                            <span class="btn btn-success money-done">استلام المبلغ</span>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>تاريخ الاستلام</th>
                                    <th>المبلغ</th>
                                    <th>التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
$stmt2 = $con->prepare("SELECT * FROM installments_money WHERE user_id = ? ORDER BY date DESC");
        $stmt2->execute(array($userID));
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
                echo '<td colspan="3">';?>
                <div class="edit form-control d-flex justify-content-between">
                            <span class="btn edit-money-close btn-danger text-center <?php echo $time['id'] ?>">إنهاء</span>
                            <input type="hidden" class="userID" value="<?php echo $_GET['userid'] ?>">
                            <input type="hidden" min="0" class="oldMoney" value="<?php echo $time['amount'] ?>">
                            <input type="number" min="0" step="0.01" class="col-4 ps-0 form-contorl new" name="money" value="<?php echo $time['amount'] ?>" placeholder="المبلغ">
                            <span class="btn btn-success money-done-edit <?php echo $time['id'] ?>">تعديل المبلغ</span>
                        </div>
                        <?php
echo '</td>';
                echo '</tr>';
                echo '<tr class="bad ' . $time['id'] . '">';
                echo '<td colspan="3">';
                echo '<div class="alert alert-danger text-center">لم يتم أى تعديل</div>';
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} elseif ($application == 'add') {
        // Adding new item into installments plan
        $userID = $_GET['userid'];
        $stmt = $con->prepare("SELECT username FROM installments_users WHERE user_id = ?");
        $stmt->execute(array($userID));
        $user = $stmt->fetch();
        ?>
    <h1 class="text-center">إضافة صنف بالتقسيط</h1>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <h3>إضافة صنف بالتقسيط إلى: <?php echo $user['username'] ?></h3>
            <a href="?application=info&userid=<?php echo $userID ?>" class="btn btn-primary text-decoration-none">رجوع</a>
        </div>
        <div class="card-body instaItem">
            <form class="newItemAdd">
                <div class="row mb-3">
                    <div class="col">
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
                    <div class="col">
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
                    <div class="col">
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
                    <div class="col">
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
                </div>
                <div class="row mb-3 gap-4 px-2">
                    <div class="col form-control">
                        <div class="row">
                            <div class="col-6 text-center">
                                <label class="form-label">المتاح</label>
                            </div>
                                <div class="col-6">
                                    <input type="text" disabled class="z-3 ps-0 text-center avail form-control" name="avail" autocomplete="off" value="المتاح">
                                </div>
                            </div>
                        </div>
                        <div class="col form-control">
                            <div class="row">
                                <div class="col text-center">
                                    <label class="form-label">الكمية</label>
                                </div>
                                <div class="col">
                                    <input type="number" min="1" max="" class="z-3 ps-0 text-center amount form-control" name="selling_amount" required='required' autocomplete="off" placeholder="الكمية">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row mb-3 gap-4 px-2">
                    <div class="col form-control">
                        <div class="row">
                            <div class="col text-center">
                                <label class="form-label">سعر القطعة</label>
                            </div>
                            <div class="col">
                                <input type="number" min="0" step="0.01" class="z-3 ps-0 text-center form-control unit-price" name="unit_price" required='required' autocomplete="off" placeholder="سعر القطعة">
                            </div>
                        </div>
                    </div>
                    <div class="col form-control">
                        <div class="row">
                            <div class="col text-center">
                                <label class="form-label">الإجمالى</label>
                            </div>
                            <div class="col">
                                <input type="number" min="0" step="0.01" class="z-3 ps-0 total-price text-center form-control" name="total" required='required' autocomplete="off" placeholder="الإجمالى">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 gap-4 px-2">
                    <div class="col form-control">
                        <div class="row">
                            <div class="col text-center">
                                <label class="form-label">سعر القطعة بالتقسيط</label>
                            </div>
                            <div class="col">
                                <input type="number" min="0" step="0.01" class="z-3 ps-0 text-center form-control insta-unit-price" name="unit_price" required='required' autocomplete="off" placeholder="سعر القطعة بالتقسيط">
                            </div>
                        </div>
                    </div>
                    <div class="col form-control">
                        <div class="row">
                            <div class="col text-center">
                                <label class="form-label">الإجمالى بالتقسيط</label>
                            </div>
                            <div class="col">
                                <input type="number" min="0" step="0.01" class="z-3 ps-0 insta-total-price text-center form-control" name="total" required='required' autocomplete="off" placeholder="الإجمالى بالتقسيط">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 mx-auto">
                        <input type="hidden" name="userid" id="userid" value="<?php echo $userID ?>">
                        <input type="submit" class="btn btn-primary form-control addInstaItem" value="إضافة">
                    </div>
                </div>
    </form>
        </div>
    </div>
    <div class="errorMsg alert alert-danger text-center" style="display: none;">برجاء إكمال جميع الاختيارات</div>
    <div class="errorMsgAmount alert alert-danger text-center" style="display: none;">لقد تعديت الكمية المتاحة</div>
    <div class="successMsg alert alert-success text-center" style="display: none;">تمت الإضافة بنجاح</div>
</div>
    </div>
</div>
    <?php
}
    include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
