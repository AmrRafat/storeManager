<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'قصر الملكة';
    include "init.php";
    $today = date("j / n / Y");
    $application = isset($_GET['application']) ? $_GET['application'] : 'show';
    $currentYear = date('m') >= 4 ? date('Y') : date('Y') - 1;
    ?>
<div class="container px-4 px-lg-0">
    <?php
if ($application == 'show') {?>
    <h1 class="calcs text-center">الحسابات</h1>
    <?php
$totalStore = 0;
        $stmt = $con->prepare("SELECT * FROM items");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $totalStore = $totalStore + (($row['amount'] - $row['amount_sold']) * $row['purchase_price']);
        }
        ?>
                <h3 class="text-center border border-primary totalStore rounded-pill px-3 py-2 mx-auto">رأس مال المحل: <?php echo $totalStore ?></php></h3>
    <div class="options">
        <div class="row flex-column flex-md-row gap-4 justify-content-center text-center ">
            <a href="?application=spendings" class="col d-flex justify-content-center align-items-center rounded-4 text-decoration-none spendings">المصاريف</a>
            <a href="?application=year&year=<?php echo $currentYear ?>" class="col d-flex justify-content-center align-items-center rounded-4 text-decoration-none year">الحسابات السنوية</a>
            <a href="?application=month" class="col d-flex justify-content-center align-items-center rounded-4 text-decoration-none month">الحسابات الشهرية</a>
        </div>
    </div>
<?php } elseif ($application == "spendings") {?>
    <h1 class="text-center">المصاريف</h1>
    <div class="card">
        <div class="card-header p-3">
            <form class="text-center spendingsForm form-control py-3">
                <div class="input-group w-75 mx-auto flex-column flex-lg-row">
                    <span class="input-group-text">نوع المصاريف</span>
                    <input type="text" name="spending_name" class="spending_name form-control" required="required">
                    <span class="input-group-text">التاريخ</span>
                    <input type="date" name="date" class="date form-control" required="required">
                    <span class="input-group-text">المبلغ</span>
                    <input type="number" name="amount" min="1" step="0.01" class="amount form-control" required="required">
                    <button type="submit" class="btn btn-success spendingAdd">إضافة</button>
                    <a href="?application=show" class="text-decoration-none btn-primary btn">رجوع</a>
                </div>
            </form>
        </div>
        <div class="card-body data p-3">
            <?php
$stmt = $con->prepare("SELECT * FROM spendings ORDER BY date DESC");
        $stmt->execute();
        $check = $stmt->rowCount();
        if ($check == 0) {
            echo '<div class="alert alert-info text-center">لا توجد أى بيانات</div>';
        } else {
            $data = $stmt->fetchAll();
            ?>
            <div class="alert alert-success text-center spendingMsg">تمت الإضافة بنجاح</div>
            <div class="alert alert-success text-center spendingDelMsg">تمت المسح بنجاح</div>
                    <table class="table table-striped spendingsTable">
                        <thead>
                            <tr>
                                <th>المصاريف</th>
                                <th>التاريخ</th>
                                <th>المبلغ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
foreach ($data as $info) {
                ?>
<tr data-id="<?php echo $info['spendingID'] ?>" class="align-middle overflow-hidden infoData">
    <td><?php echo $info['spendingName'] ?></td>
    <td><?php echo $info['date'] ?></td>
    <td><?php echo $info['amount'] ?></td>
    <td class="forth"><span class="btn btn-danger spendingDel py-1">حذف</span></td>
</tr>
<?php }?>
</tbody>
                    </table>
                <?php }?>
        </div>
    </div>

    <?php
} elseif ($application == "month") {?>
    <h1 class="text-center monthsCalcshead">الحسابات الشهرية</h1>
    <h1 class="text-center">لسنة
        <?php
if (isset($_GET['selectedYear'])) {
        $year = $_GET['selectedYear'];
    } else {
        $month = date("m");
        if ($month < 4) {
            $year = date('Y') - 1;
        } else {
            $year = date('Y');
        }
    }
        echo '<span class="selectedYear">' . $year . '</span>';
        ?>
</h1>
<div class="monthsOptions mb-3">
        <div class="input-group mx-auto">
            <input type="number" name="year" id="year" min="2020" max="2099" step="1" class="text-center form-control" value="<?php echo $year = date('Y') ?>">
            <button type="button" class="btn btn-success changeYear">تغيير السنة</button>
            <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
        </div>
    </div>
    <div class="months">
        <div class="row justify-content-center">
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=04&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">4 - إبريل</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=05&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">5 - مايو</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=06&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">6 - يونيو</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=07&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">7 - يوليو</a></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=08&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">8 - أغسطس</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=09&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">9 - سبتمبر</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=10&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">10 - أكتوبر</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=11&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">11 - نوفمبر</a></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=12&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">12 - ديسمبر</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=01&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">1 - يناير</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=02&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">2 - فبراير</a></div>
            <div class="col-6 col-lg p-3"><a href="?application=analyse&month=03&year=<?php echo $year ?>" class="text-reset text-decoration-none btn">3 - مارس</a></div>
        </div>
    </div>
    <?php } elseif ($application == 'analyse') {?>
            <h1 class="text-center">عرض شهر <?php echo intval($_GET['month']) ?></h1>
            <h1 class="text-center theYear">لسنة <?php echo $_GET['year'] ?></h1>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>حسابات الشهر</h4>
                    <a href="?application=month" class="btn btn-primary text-decoration-none">رجوع</a>
                </div>
                <div class="card-body monthCalcs">
                    <?php
// Start checking logs first
        $firstDate = $_GET['year'] . "-" . $_GET['month'] . '-01';
        $lastDate = date('Y-m-t', strtotime($firstDate));
        $stmt = $con->prepare("SELECT SUM(total_selling_price) AS totalIncome, SUM(total_purchase_price) AS totalPrice FROM logs WHERE selling_date >= ? && selling_date <= ?");
        $stmt1 = $con->prepare("SELECT SUM(amount) AS totalSpendings FROM spendings WHERE date >= ? && date <= ?");
        $stmt2 = $con->prepare("SELECT SUM(total_insta_price) AS instaIncome, SUM(total_purchase_price) AS instaPurchase FROM installments_items WHERE paid_date >= ? && paid_date <= ?");
        $stmt->execute(array($firstDate, $lastDate));
        $stmt1->execute(array($firstDate, $lastDate));
        $stmt2->execute(array($firstDate, $lastDate));
        $check = $stmt->rowCount();
        $spendings = $stmt1->fetch();
        $totalSpendings = empty($spendings['totalSpendings']) ? 0 : $spendings['totalSpendings'];
        $instaIncome = $stmt2->fetch();
        $totalInstaIncome = empty($instaIncome['instaIncome']) ? 0 : $instaIncome['instaIncome'];
        $totalInstaPurchase = empty($instaIncome['instaPurchase']) ? 0 : $instaIncome['instaPurchase'];
        if ($check == 0) {
            echo '<div class="alert alert-info text-center">لا توجد بيانات</div>';
        } else {
            $data = $stmt->fetch();
            if (empty($data['totalIncome'])) {
                echo '<div class="alert alert-info text-center">لا توجد بيانات</div>';
            } else {
                $income = floatval($data['totalIncome']) + floatval($totalInstaIncome);
                $purchases = floatval($data['totalPrice']) + floatval($totalInstaPurchase);
                // Showing Main info: total income, total purchase price, total spendings, & profit
                $profit = $income - $purchases - floatval($totalSpendings);
                ?>
                <div class="row text-center monthlyCalcHeader mb-3 w-100 mx-auto justify-content-center">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى الإيراد</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo $income ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى سعر الشراء</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo $purchases ?></span>
                        </div>
                    </div>
                </div>
                <div class="row text-center monthlyCalcHeader w-100 mx-auto justify-content-center">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى المصاريف</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo round($totalSpendings, 2) ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">الربح</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo round($profit, 2) ?></span>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>بيانات الأيام</h4>
                </div>
                <div class="card-body daysCalcs">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اليوم</th>
                                <th>إيراد المبيعات</th>
                                <th>إيراد الأقساط</th>
                                <th>إجمالى سعر الشراء</th>
                                <th>الربح</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        <?php
$lastDay = date('d', strtotime($lastDate));
                for ($i = 1; $i < ($lastDay + 1); $i++) {
                    $date = $_GET['year'] . "-" . $_GET['month'] . "-" . $i;
                    $stmt3 = $con->prepare("SELECT SUM(total_selling_price) AS totalSelling, SUM(total_purchase_price) AS totalPurchase FROM logs WHERE selling_date = ?");
                    $stmt4 = $con->prepare("SELECT SUM(total_insta_price) AS totalInsta, SUM(total_purchase_price) AS totalP FROM installments_items WHERE paid_date = ?");
                    $stmt3->execute(array($date));
                    $stmt4->execute(array($date));
                    $info = $stmt3->fetch();
                    $info2 = $stmt4->fetch();
                    $sellings1 = empty($info['totalSelling']) ? 0 : $info['totalSelling'];
                    $purchase = empty($info['totalPurchase']) ? 0 : $info['totalPurchase'];
                    $instaIncome = empty($info2['totalInsta']) ? 0 : $info2['totalInsta'];
                    $instaPrice = empty($info2['totalP']) ? 0 : $info2['totalP'];
                    $sellings = floatval($sellings1) + floatval($instaIncome);
                    $purchase = floatval($purchase) + floatval($instaPrice);
                    $dailyProfit = $sellings - $purchase;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $sellings1 . '</td>';
                    echo '<td>' . $instaIncome . '</td>';
                    echo '<td>' . $purchase . '</td>';
                    echo '<td>' . $dailyProfit . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                $pureMonthProfit = $income - $purchases;
                echo '<tfoot class="table-group-divider">';
                echo '<tr class="">';
                echo '<th>الإجمالى</th>';
                echo '<th>' . $data['totalIncome'] . '</th>';
                echo '<th>' . $totalInstaIncome . '</th>';
                echo '<th>' . $purchases . '</th>';
                echo '<th>' . $pureMonthProfit . '</th>';
                echo '</tr>';
                echo '</tfoot>';
                ?>
                    </table>
                </div>
            </div>
        <?php }
        }
    } elseif ($application == 'year') {
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $month = date('m');
            if ($month >= 4) {
                $year = date('Y');
            } else {
                $year = date('Y') - 1;
            }
        }
        ?>
        <h1 class="text-center">عرض سنة <?php echo $year ?></h1>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-content-center">
                    <h4>حسابات السنة</h4>
                    <div class="d-flex align-items-center">
                        <div class="input-group">
                            <input type="number" min="2020" value="<?php echo $year ?>" class="yearChanging form-control" style="max-width:150px; display:inline-block">
                            <button type="button" class="btn btn-success changeYearCalc">تغيير السنة</button>
                            <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
                        </div>
                    </div>
                </div>
                <div class="card-body yearCalcs">
                    <?php
// Start checking logs first
        $firstDate = $year . "-04-01";
        $lastDate = (floatval($year) + 1) . "-03-31";
        $stmt = $con->prepare("SELECT SUM(total_selling_price) AS totalIncome, SUM(total_purchase_price) AS totalPrice FROM logs WHERE selling_date >= ? && selling_date <= ?");
        $stmt1 = $con->prepare("SELECT SUM(amount) AS totalSpendings FROM spendings WHERE date >= ? && date <= ?");
        $stmt2 = $con->prepare("SELECT SUM(amount) AS totalBills FROM bills WHERE date >= ? && date <= ?");
        $stmt3 = $con->prepare("SELECT SUM(total_insta_price) AS instaIncome, SUM(total_purchase_price) AS instaPurchase FROM installments_items WHERE paid_date >= ? && paid_date <= ?");
        $stmt->execute(array($firstDate, $lastDate));
        $stmt1->execute(array($firstDate, $lastDate));
        $stmt2->execute(array($firstDate, $lastDate));
        $stmt3->execute(array($firstDate, $lastDate));
        $check = $stmt->rowCount();
        $spendings = $stmt1->fetch();
        $totalSpendings = empty($spendings['totalSpendings']) ? 0 : $spendings['totalSpendings'];
        $totalBills = $stmt2->fetch();
        $totalBill = empty($totalBills['totalBills']) ? 0 : $totalBills['totalBills'];
        $info = $stmt3->fetch();
        $instaIncome = empty($info['instaIncome']) ? 0 : $info['instaIncome'];
        $instaPurchase = empty($info['instaPurchase']) ? 0 : $info['instaPurchase'];
        if ($check == 0) {
            echo '<div class="alert alert-info text-center">لا توجد بيانات</div>';
        } else {
            $data = $stmt->fetch();
            if (empty($data['totalIncome'])) {
                echo '<div class="alert alert-info text-center">لا توجد بيانات</div>';
            } else {
                $totalIncome = floatval($data['totalIncome']) + floatval($instaIncome);
                $totalExpense = floatval($data['totalPrice']) + floatval($instaPurchase);
                // Showing Main info: total income, total purchase price, total spendings, & profit
                $profit = $totalIncome - $totalExpense - floatval($totalSpendings);
                ?>
                <div class="row text-center yearlyCalcHeader mb-3 w-100 mx-auto justify-content-center">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى الإيراد</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo $totalIncome ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى سعر الشراء</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo $totalExpense ?></span>
                        </div>
                    </div>
                </div>
                <div class="row text-center yearlyCalcHeader mb-3 w-100 mx-auto justify-content-center">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى المصاريف</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo round($totalSpendings, 2) ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">الربح</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo round($profit, 2) ?></span>
                        </div>
                    </div>
                </div>
                <div class="row text-center yearlyCalcHeader w-100 mx-auto justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="input-group w-100">
                            <span class="input-group-text form-control justify-content-center">إجمالى الفواتير</span>
                            <span class="input-group-text form-control justify-content-center"><?php echo $totalBill ?></span>
                        </div>
                    </div>
                </div>
                </div>
                </div>
            <div class="card">
                <div class="card-header">
                    <h4>بيانات الشهور</h4>
                </div>
                <div class="card-body monthsCalcs">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>الشهر</th>
                                <th>الإيراد</th>
                                <th>إجمالى سعر الشراء</th>
                                <th>إجمالى المصاريف</th>
                                <th>الربح</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
for ($i = 4; $i < 13; $i++) {
                    $date1 = $_GET['year'] . "-" . $i . "-01";
                    $date2 = date('Y-m-t', strtotime($date1));
                    $stmt2 = $con->prepare("SELECT SUM(total_selling_price) AS totalSelling, SUM(total_purchase_price) AS totalPurchase FROM logs WHERE selling_date >= ? && selling_date <= ?");
                    $stmt5 = $con->prepare("SELECT SUM(total_insta_price) AS instaIncome, SUM(total_purchase_price) AS totalP FROM installments_items WHERE paid_date >= ? && paid_date <=?");
                    $stmt4 = $con->prepare("SELECT SUM(amount) AS totalSpendings FROM spendings WHERE date >= ? && date <= ?");
                    $stmt2->execute(array($date1, $date2));
                    $stmt5->execute(array($date1, $date2));
                    $stmt4->execute(array($date1, $date2));
                    $info = $stmt2->fetch();
                    $info2 = $stmt5->fetch();
                    $info3 = $stmt4->fetch();
                    $sellings = empty($info['totalSelling']) ? 0 : $info['totalSelling'];
                    $purchase = empty($info['totalPurchase']) ? 0 : $info['totalPurchase'];
                    $instaSells = empty($info2['instaIncome']) ? 0 : $info2['instaIncome'];
                    $instaPurchase = empty($info2['totalP']) ? 0 : $info2['totalP'];
                    $spendings = empty($info3['totalSpendings']) ? 0 : $info3['totalSpendings'];
                    $sellings = floatval($sellings);
                    $purchase = floatval($purchase);
                    $totalSales = $sellings + floatval($instaSells);
                    $totalPurchase = $purchase + floatval($instaPurchase) + floatval($spendings);
                    $dailyProfit = $totalSales - $totalPurchase;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $totalSales . '</td>';
                    echo '<td>' . round($totalPurchase, 2) . '</td>';
                    echo '<td>' . round($spendings, 2) . '</td>';
                    echo '<td>' . round($dailyProfit, 2) . '</td>';
                    echo '</tr>';
                }
                for ($i = 1; $i < 4; $i++) {
                    $date1 = $_GET['year'] . "-" . $i . "-01";
                    $date2 = date('Y-m-t', strtotime($date1));
                    $stmt2 = $con->prepare("SELECT SUM(total_selling_price) AS totalSelling, SUM(total_purchase_price) AS totalPurchase FROM logs WHERE selling_date >= ? && selling_date <= ?");
                    $stmt5 = $con->prepare("SELECT SUM(total_insta_price) AS instaIncome, SUM(total_purchase_price) AS totalP FROM installments_items WHERE paid_date >= ? && paid_date <=?");
                    $stmt4 = $con->prepare("SELECT SUM(amount) AS totalSpendings FROM spendings WHERE date >= ? && date <= ?");
                    $stmt2->execute(array($date1, $date2));
                    $stmt5->execute(array($date1, $date2));
                    $stmt4->execute(array($date1, $date2));
                    $info = $stmt2->fetch();
                    $info2 = $stmt5->fetch();
                    $info3 = $stmt4->fetch();
                    $sellings = empty($info['totalSelling']) ? 0 : $info['totalSelling'];
                    $purchase = empty($info['totalPurchase']) ? 0 : $info['totalPurchase'];
                    $instaSells = empty($info2['instaIncome']) ? 0 : $info2['instaIncome'];
                    $instaPurchase = empty($info2['totalP']) ? 0 : $info2['totalP'];
                    $spendinds = empty($info3['totalSpendings']) ? 0 : $info3['totalSpendings'];
                    $sellings = floatval($sellings);
                    $purchase = floatval($purchase);
                    $totalSales = $sellings + floatval($instaSells);
                    $totalPurchase = $purchase + floatval($instaPurchase) + floatval($spendings);
                    $dailyProfit = $totalSales - $totalPurchase;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $totalSales . '</td>';
                    echo '<td>' . $totalPurchase . '</td>';
                    echo '<td>' . $spendings . '</td>';
                    echo '<td>' . $dailyProfit . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                ?>
                    </table>
                </div>
            </div>
        <?php }
        }
    }?>
</div>
<?php
include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();