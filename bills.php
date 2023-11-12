<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'قصر الملكة';
    include "init.php";
    $today = date("j / n / Y");
    $application = isset($_GET['application']) ? $_GET['application'] : 'show';
    ?>
<div class="container">
    <?php
if ($application == 'show') {
        ?>
    <h1 class="text-center">الفواتير</h1>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>الفواتير</h4>
            <span class="btn btn-primary addBill">إضافة فاتورة</span>
        </div>
        <div class="card-body">
            <div class="error alert alert-danger text-center">يرجى إكمال جميع الحقول</div>
            <div class="success alert alert-success text-center">تم إضافة الفاتورة</div>
            <div class="successDel alert alert-success text-center">تم مسح الفاتورة</div>
            <form class="newBillForm p-3 form-control mb-3">
                <div class="input-group mx-auto w-75">
                    <span class="input-group-text">تاريخ الفاتورة</span>
                    <input type="date" name="billDate" id="date" class="form-control" required="required">
                    <span class="input-group-text">اسم البائع</span>
                    <input placeholder="..." type="text" name="sellerName" id ="name" class="form-control" required="required">
                    <span class="input-group-text">إجمالى الفاتورة</span>
                    <input placeholder="00.00" type="number" name="billAmount" autocomplete="off" id="amount" min="1" class="form-control" required="required">
                    <button type="submit" class="btn btn-success newBillBtn">إضافة</button>
                    <button type="button" class="btn btn-danger endNewBill">إغلاق</button>
                </div>
                <!-- <div class="row justify-content-center align-items-center">
                    <div class="col text-center">
                        <label class="form-label">
                            تاريخ الفاتورة
                        </label>
                    </div>
                    <div class="col position-relative">
                    </div>
                    <div class="col text-center">
                        <label class="form-label">
                            اسم البائع
                        </label>
                    </div>
                    <div class="col-3 position-relative">
                    </div>
                    <div class="col text-center">
                        <label class="form-label">
                            إجمالى الفاتورة
                        </label>
                    </div>
                    <div class="col-2 position-relative">
                    </div>
                    <div class="col">
                        <div class="row gap-1 justify-content-center">
                            <div class="col">
                            </div>
                            <div class="col">
                            </div>
                        </div>
                    </div>
                </div> -->
            </form>
            <div class="dataShow form-control">
                <?php
$stmt = $con->prepare("SELECT * FROM bills ORDER BY date DESC");
        $stmt->execute();
        $check = $stmt->rowCount();
        if ($check == 0) {
            echo '<div class="alert alert-info text-center">لم يتم تسجيل أى فاتورة بعد</div>';
        } else {
            $data = $stmt->fetchAll();
        }
        if (isset($data)) {?>
        <div class="row text-center mb-2">
            <div class="col-2 fw-bold">تاريخ الفاتورة</div>
            <div class="col-4 fw-bold">اسم البائع</div>
            <div class="col-2 fw-bold">إجمالى الفاتورة</div>
            <div class="col-4"></div>
        </div>
        <hr>
        <div class="dataInfo">
            <?php
foreach ($data as $info) {
            echo '<div class="row text-center data align-items-center py-2" data-log="' . $info['bill_id'] . '">';
            echo '<div class="col-2">' . $info['date'] . '</div>';
            echo '<div class="col-4">' . $info['seller'] . '</div>';
            echo '<div class="col-2">' . $info['amount'] . '</div>';
            echo '<div class="col-4 delCol">';
            echo '<span class="btn btn-danger delBill">حذف</span>';
            echo '</div>';
            echo '</div>';
            echo '<hr>';
        }?>
            </div>
        <?php
}
        ?>
            </div>
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
