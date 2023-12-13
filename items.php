<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'قصر الملكة';
    include "init.php";
    $today = date("j / n / Y");
    $application = isset($_GET['application']) ? $_GET['application'] : 'show';
    global $beforeEdit;
    ?>
<div class="container px-3 px-lg-0">
    <?php
if ($application == 'show') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $selectedCat = $_POST['cat'];
            $selectedSubCat = $_POST['subcat'];}
        ?>
    <h1 class="text-center">المخزن</h1>
    <div class="card row mt-4">
        <div class="card-header py-3">
            <div class="row mb-1 mb-lg-2 mb-xl-3 align-items-center justify-content-between">
                <h4 class="col ps-0 mb-0 text-end">المخزن</h4>
            </div>
            <div class="row justify-content-between align-items-center">
                <form class="col-12 col-lg-6 searchForm py-3 p-lg-0 px-2" action="items.php?application=show" method="POST">
                    <div class="input-group flex-nowrap flex-column flex-lg-row">
                        <span class="input-group-text">القسم</span>
                        <select class="form-control" name="cat" id="cat">
                            <option value="0">....</option>
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
                        <span class="input-group-text">القسم الفرعى</span>
                        <select name="subcat" class="form-control" id="subcat">
                            <option selected value="0">برجاء اختيار القسم أولا</option>
                            <?php
if (isset($selectedSubCat) && $selectedSubCat == 0) {?>
                        <option selected value="0">برجاء اختيار القسم أولا</option>
                        <?php } elseif (isset($selectedSubCat)) {
            $stmt = $con->prepare("SELECT * FROM subcats WHERE del = 0 AND cat_id = ? ORDER BY subcat_name");
            $stmt->execute(array($selectedCat));
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
                        <input type="submit" class="btn btn-primary showOnSelection" value="عرض الأصناف">
                    </div>
                </form>
            <div class="btns col-12 col-lg-6">
                <div class="row justify-content-around justify-content-lg-end mx-auto gap-0 gap-md-1 align-items-center">
                    <span class="col-5 col-md mb-2 mb-md-0 btn btn-primary showAll">عرض الكل</span>
                    <a class="col-5 col-md mb-2 mb-md-0 text-decoration-none btn btn-primary" href="items.php?application=alert">إنذار نفاد صنف</a>
                    <a class="col-5 col-md text-decoration-none btn btn-primary" href="items.php?application=addquantity">إضافة كمية</a>
                    <a class="col-5 col-md text-decoration-none btn btn-primary" href="items.php?application=additem">إضافة صنف</a>
                </div>
            </div>
            </div>
        </div>
        <div class="card-body">
            <?php
if (
            isset($selectedCat)
            &&
            isset($selectedSubCat)
            &&
            $selectedCat != 0
            &&
            $selectedSubCat != 0
        ) {
            $stmt = $con->prepare("SELECT
                        items.*,
                        users.fullname AS fullname,
                        SUM(items.amount) AS totalAmount,
                        SUM(items.amount_sold) AS totalSold
                    FROM
                        items
                    INNER JOIN users ON
                    items.user_id = users.user_id
                    WHERE items.del = 0
                    AND items.cat_id = ?
                    AND items.subcat_id = ?
                    GROUP BY item_code
                    ORDER BY item_name, adding_date DESC");
            $stmt->execute(array($selectedCat, $selectedSubCat));
            $rows = $stmt->fetchAll();
            $count = $stmt->rowCount();
            if ($count == 0) {
                echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
            } else {
                ?>
            <table class="table table-striped text-center itemsTable1">
                <tr class="align-middle">
                    <th>الكود</th>
                    <th>اسم الصنف</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
                    $availableAmount = $row['totalAmount'] - $row['totalSold'];
                    $stmt1 = $con->prepare("SELECT * FROM items WHERE item_code = ? ORDER BY adding_date DESC");
                    $stmt1->execute(array($row['item_code']));
                    $rows2 = $stmt1->fetchAll();
                    ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $rows2[0]['adding_date'] ?></td>
                    <td><?php echo $rows2[0]['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-2 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
        } elseif (
            isset($selectedCat)
            &&
            isset($selectedSubCat)
            &&
            $selectedCat != 0
            &&
            $selectedSubCat == 0) {
            $stmt = $con->prepare("SELECT
                        items.*,
                        users.fullname AS fullname,
                        subcats.subcat_name AS subcatName,
                        SUM(items.amount) AS totalAmount,
                        SUM(items.amount_sold) AS totalSold
                    FROM
                        items
                    INNER JOIN users ON
                    items.user_id = users.user_id
                    INNER JOIN subcats ON
                    items.subcat_id = subcats.subcat_id
                    WHERE items.del = 0
                    AND items.cat_id = ?
                    GROUP BY item_code
                    ORDER BY item_name, adding_date DESC");
            $stmt->execute(array($selectedCat));
            $rows = $stmt->fetchAll();
            $count = $stmt->rowCount();
            if ($count == 0) {
                echo '<div class="text-center alert alert-info">لا توجد أصناف بعد... يرجى إضافة صنف جديد</div>';
            } else {
                ?>
            <table class="table table-striped text-center itemsTable2" data-cat = "<?php echo $selectedCat ?>">
                <tr class="align-middle">
                    <th class="catcodes">الكود</th>
                    <th class="catnames">اسم الصنف</th>
                    <th class="catsubcats">القسم الفرعى</th>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>الموجود حاليا</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
                    $availableAmount = $row['totalAmount'] - $row['totalSold'];
                    $stmt1 = $con->prepare("SELECT * FROM items WHERE item_code = ? ORDER BY adding_date DESC");
                    $stmt1->execute(array($row['item_code']));
                    $rows2 = $stmt1->fetchAll();
                    ?>
                    <tr class="align-middle">
                    <td><?php echo $row['item_code'] ?></td>
                    <td><?php echo $row['item_name'] ?></td>
                    <td><?php echo $row['subcatName'] ?></td>
                    <td><?php echo $rows2[0]['adding_date'] ?></td>
                    <td><?php echo $rows2[0]['purchase_price'] ?></td>
                    <td><?php echo $availableAmount ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success px-1 px-lg-2 mb-2 mb-lg-0" href="?application=edit&itemcode=<?php echo $row['item_code'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-info px-1 px-lg-2" href="?application=info&itemcode=<?php echo $row['item_code'] ?>">تفاصيل</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }
        } else {
            echo '<div class="text-center alert alert-info">برجاء اختيار القسم والقسم الفرعى لعرض الأصناف</div>';
        }
        ?>
        </div>
    </div>
    <?php } elseif ($application == 'additem') {
        unset($_POST['catid']);
        $selectedCat = isset($_GET['c']) ? $_GET['c'] : 0;
        $selectedSubCat = isset($_GET['s']) ? $_GET['s'] : 0;
        ?>
            <h1 class="text-center">إضافة صنف جديد</h1>
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center justify-content-between px-2">
                        <h3 class="mb-0 col-7">إضافة صنف جديد</h3>
                        <a href="?application=show" class="col-3 col-lg-1 btn btn-primary text-decoration-none">رجوع</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-control text-center item p-3 addItemForm" action="items.php?application=apply&way=item" method="POST">
                        <div class="input-group w-75 mx-auto">
                            <span class="input-group-text">القسم</span>
                            <select class="form-control text-center z-3" required = "required" name="cat" id="cat">
                                <option value="0">برجاء اختيار القسم</option>
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
                            <span class="input-group-text">القسم الفرعى</span>
                            <select name="subcat" class="form-control text-center z-3" required = "required" id="subcat">
                                <?php
if (isset($selectedSubCat) && $selectedSubCat == 0) {?>
                            <option selected value="0">برجاء اختيار القسم أولا</option>
                            <?php } elseif (isset($selectedSubCat)) {
            $stmt = $con->prepare("SELECT * FROM subcats WHERE del = 0 && cat_id = ? ORDER BY subcat_name");
            $stmt->execute(array($selectedCat));
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
                        <div class="input-group w-75 mx-auto my-3">
                            <span class="input-group-text">اسم الصنف</span>
                            <input type="text" class="z-3 form-control text-center" name="item_name" required='required' autocomplete="off" placeholder="اسم الصنف">
                            <span class="input-group-text">سعر الشراء</span>
                            <input type="number" min="0" step="0.01" class="z-3 text-center form-control" name="item_purchase_price" required='required' autocomplete="off" placeholder="سعر الشراء">
                        </div>
                        <div class="input-group w-75 mx-auto mb-3">
                            <span class="input-group-text">الكمية</span>
                            <input type="number" min="1" class="z-3 text-center form-control" name="item_amount" required='required' autocomplete="off" placeholder="الكمية">
                            <span class="input-group-text">أقل كمية</span>
                            <input type="number" min="0" class="z-3 text-center form-control" name="item_leastamount" required='required' autocomplete="off" placeholder="أقل كمية">
                        </div>
                    <div class="row justify-content-center">
                        <div class="col-5 col-md-4 col-lg-2">
                            <input type="submit" class="btn btn-primary form-control" value="إضافة صنف">
                        </div>
                    </div>
                </form>
                </div>
            </div>
    <?php } elseif ($application == 'addquantity') {
        unset($_POST['catid']);
        ?>
            <h1 class="text-center">إضافة كمية جديدة</h1>
            <div class="card">
                <div class="card-header px-3 d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">إضافة كمية جديدة</h3>
                    <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
                </div>
                <div class="card-body">
                <form class="form-control text-center quantity p-3" action="items.php?application=apply&way=quantity" method="POST">
                    <div class="input-group w-75 mx-auto">
                        <span class="input-group-text">الكود</span>
                        <select class="form-control text-center z-3" required = "required" name="code" id="code">
                                <option value="0">....</option>
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
                        <span class="input-group-text">القسم</span>
                        <select class="form-control text-center z-3" required = "required" name="cat" id="cat">
                            <option value="0">برجاء اختيار القسم</option>
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
                        <div class="input-group mx-auto my-3 w-75">
                        <span class="input-group-text">القسم الفرعى</span>
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
                        <span class="input-group-text">الصنف</span>
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
                        <div class="input-group my-3 w-75 mx-auto">
                            <span class="input-group-text">سعر الشراء</span>
                            <input type="number" min="0" step="0.01" class="z-3 text-center form-control" name="item_purchase_price" required='required' autocomplete="off" placeholder="سعر الشراء">
                            <span class="input-group-text">الكمية</span>
                            <input type="number" min="1" class="z-3 text-center form-control" name="item_amount" required='required' autocomplete="off" placeholder="الكمية">
                        </div>
                    <div class="row justify-content-center">
                        <div class="col-5 col-md-4 col-lg-2">
                            <input type="submit" class="btn btn-primary form-control" value="إضافة صنف">
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <?php } elseif ($application == 'apply') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_GET['way'] == 'item') {
                if ($_POST['cat'] == 0 || $_POST['subcat'] == 0) {
                    echo '<div class="alert alert-danger text-center mt-5">يرجى اختيار الأقسام كاملة</div>';
                    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                        $url = $_SERVER['HTTP_REFERER'];
                    } else {
                        $url = 'index.php';
                    }
                    header("refresh:2 url=$url");
                    exit();
                }
            } elseif ($_GET['way'] == 'quantity') {
                if ($_POST['cat'] == 0 || $_POST['subcat'] == 0 || $_POST['item1'] == 0) {
                    echo '<div class="alert alert-danger text-center mt-5">يرجى إتمام جميع الاختيارات كاملة</div>';
                    $url = $_SERVER['HTTP_REFERER'];
                    header("refresh:2 url=$url");
                    exit();
                }
            }
            $catid = $_POST['cat'];
            $subcatid = $_POST['subcat'];
            $item_price = filter_var($_POST['item_purchase_price'], FILTER_SANITIZE_NUMBER_FLOAT);
            $item_amount = filter_var($_POST['item_amount'], FILTER_SANITIZE_NUMBER_INT);
            $userid = $_SESSION['id'];
            if ($_GET['way'] == 'item') {
                echo '<h1 class="text-center">إضافة صنف جديد</h1>';
                $item_name = filter_var($_POST['item_name'], FILTER_SANITIZE_STRING);
                $least_amount = filter_var($_POST['item_leastamount'], FILTER_SANITIZE_NUMBER_INT);
                $stmt = $con->prepare("SELECT * FROM items WHERE cat_id = ? AND subcat_id = ? ORDER BY item_code DESC");
                $stmt->execute(array($catid, $subcatid));
                $count = $stmt->rowCount();
                $names = $stmt->fetchAll();
                $check = false;
                if ($count >= 1) {
                    foreach ($names as $name) {
                        if ($name['item_name'] == $item_name) {
                            echo '<div class="alert alert-danger text-center mt-5">هذا الصنف موجود بالفعل</div>';
                            $url = $_SERVER['HTTP_REFERER'] . '&c=' . $catid . '&s=' . $subcatid;
                            header("refresh:1 url=$url");
                            exit();
                        } else {
                            $check = true;
                        }
                    }
                }
                $stmt2 = $con->prepare("SELECT * FROM items ORDER BY item_code DESC");
                $stmt2->execute();
                $codes = $stmt2->fetchAll();
                $lastCode = $codes[0]['item_code'];
                if ($count == 0 || $check == true) {
                    $newCode = $lastCode + 1;
                    $stmt = $con->prepare("INSERT INTO items(item_code, item_name, adding_date, purchase_price, cat_id, subcat_id, amount, least_amount, user_id) VALUES(?,?,now(),?,?,?,?,?,?)");
                    $stmt->execute(array($newCode, $item_name, $item_price, $catid, $subcatid, $item_amount, $least_amount, $userid));
                    echo '<div class="alert alert-success text-center mt-5">تم إضافة الصنف بنجاح</div>';
                    $url = $_SERVER['HTTP_REFERER'] . '&c=' . $catid . '&s=' . $subcatid;
                    header("refresh:1 url=$url");
                    exit();
                }
            } elseif ($_GET['way'] == 'quantity') {
                echo '<h1 class="text-center">إضافة كمية جديدة</h1>';
                $item_code = $_POST['item1'];
                $stmt = $con->prepare("SELECT * FROM items WHERE item_code = ? AND cat_id = ? AND subcat_id = ? ORDER BY adding_date DESC");
                $stmt->execute(array($item_code, $catid, $subcatid));
                $items = $stmt->fetchAll();
                $today = date('Y-m-d');
                $date1 = strtotime($today);
                $date2 = strtotime($items[0]['adding_date']);
                if ($date1 == $date2) {
                    echo '<div class="alert alert-danger text-center mt-5">تمت إضافة كمية إلى هذا الصنف مسبقا اليوم</div>';
                    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                        $url = $_SERVER['HTTP_REFERER'];
                    } else {
                        $url = 'index.php';
                    }
                    header("refresh:3 url=$url");
                    exit();
                } else {
                    $theItem = $items[0];
                    $stmt = $con->prepare("INSERT INTO items(item_code, item_name, adding_date, purchase_price, cat_id, subcat_id, amount, least_amount, user_id) VALUES(?,?,now(),?,?,?,?,?,?)");
                    $stmt->execute(array($theItem['item_code'], $theItem['item_name'], $item_price, $catid, $subcatid, $item_amount, $theItem['least_amount'], $userid));
                    echo '<div class="alert alert-success text-center mt-5">تم إضافة الكمية بنجاح</div>';
                    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                        $url = $_SERVER['HTTP_REFERER'];
                    } else {
                        $url = 'index.php';
                    }
                    header("refresh:2 url=$url");
                    exit();
                }
            }
        }
        ?>

                <?php } elseif ($application == 'edit') {
        if (isset($_GET['itemcode'])) {
            global $beforeEdit;
            $item_code = $_GET['itemcode'];
            $stmt = $con->prepare("SELECT * FROM items WHERE item_code = ? ORDER BY adding_date DESC");
            $stmt->execute(array($item_code));
            $items = $stmt->fetchAll();
            $beforeEdit = $items[0];
            ?>
            <h1 class="text-center">تعديل الصنف</h1>
            <form class="form-control text-center p-3 editItemForm" action="?application=update&code=<?php echo $item_code ?>" method="post">
            <div class="input-group w-75 mx-auto mb-3">
                <span class="input-group-text">القسم</span>
                <select class="form-control text-center z-3" name="cat" id="cat">
                            <option value="0">برجاء اختيار القسم</option>
                            <?php
$stmt = $con->prepare("SELECT * FROM cats WHERE del = 0 ORDER BY cat_name");
            $stmt->execute();
            $cats = $stmt->fetchAll();
            foreach ($cats as $cat) {
                echo '<option ';
                if ($cat['cat_id'] == $items[0]['cat_id']) {
                    echo 'selected';
                }
                echo ' value="';
                echo $cat['cat_id'];
                echo '">';
                echo $cat['cat_name'];
                echo '</option>';
            }
            ?>
                        </select>
                        <span class="input-group-text">القسم الفرعى</span>
                        <select name="subcat" class="form-control text-center z-3" id="subcat"></select>
                    </div>
                    <div class="input-group mb-3 mx-auto w-75">
                        <?php
$stmt = $con->prepare("SELECT * FROM items WHERE del = 0 AND cat_id = ? AND subcat_id = ? GROUP BY item_code ORDER BY item_name");
            $stmt->execute(array($items[0]['cat_id'], $items[0]['subcat_id']));
            $items2 = $stmt->fetchAll();
            ?>
            <span class="input-group-text">اسم الصنف القديم</span>
            <span class="input-group-text"><h5><?php echo $items[0]['item_name'] ?></h5></span>
            <span class="input-group-text">اسم الصنف الجديد</span>
            <input type="text" min="0" class="form-control z-3 text-center" name="item1" autocomplete="off" placeholder="يمكن تغييره إذا أردت">
            </div>
            <div class="input-group w-75 mx-auto mb-3">
                <span class="input-group-text">اخر سعر</span>
                <span class="input-group-text"><h5><?php echo $items[0]['purchase_price'] ?></h5></span>
                <span class="input-group-text">السعر الجديد</span>
                <input type="number" min="0" step="0.01" class="form-control z-3 text-center" name="price" autocomplete="off" placeholder="يمكن تغييره إذا أردت">
            </div>
            <div class="input-group w-75 mx-auto mb-3">
                <span class="input-group-text">اخر كمية تم إدخالها</span>
                <span class="input-group-text"><h5><?php echo $items[0]['amount'] ?></h5></span>
                <span class="input-group-text">الكمية الصحيحة</span>
                <input type="number" min="0" class="z-3 form-control text-center" name="amount" autocomplete="off" placeholder="يمكنك تعديله إذا أردت">
            </div>
            <div class="input-group mx-auto w-75 mb-3">
                <span class="input-group-text">أقل كمية مسموح بها</span>
                <span class="input-group-text"><h5><?php echo $items[0]['least_amount'] ?></h5></span>
                <span class="input-group-text">الكمية الأقل الجديدة المسموح بها</span>
                <input type="number" min="0" class="z-3 form-control text-center" name="leastamount" autocomplete="off" placeholder="يمكنك تعديله إذا أردت">
            </div>
            <div class="row justify-content-center mb-3">
                <div class="col-3 position-relative">
                    <input type="submit" value="تعديل" class="btn form-control btn-primary">
                </div>
                <div class="col-3 position-relative">
                    <a href="?applicaiton=show" class="btn btn-primary form-control text-decoration-none">رجوع</a>
                </div>
            </div>
            </form>
            <?php
} else {
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
            } else {
                $url = 'index.php';
            }
            header("refresh:2 url=$url");
            exit();
        }
        ?>
        <?php } elseif ($application == 'info') {
        $stmt = $con->prepare("SELECT
        items.*,
        users.fullname AS fullname
    FROM
        items
    INNER JOIN users ON
    items.user_id = users.user_id
    WHERE items.del = 0
    AND item_code = ?
    ORDER BY adding_date DESC");
        $stmt->execute(array($_GET['itemcode']));
        $rows = $stmt->fetchAll();
        $stmt1 = $con->prepare("SELECT SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items WHERE item_code = ? GROUP BY item_code");
        $stmt1->execute(array($_GET['itemcode']));
        $data = $stmt1->fetch();
        $available = floatval($data['totalAmount']) - floatval($data['totalSold']);
        ?>
        <h1 class="text-center">عرض تفاصيل الصنف</h1>
        <div class="card">
            <div class="card-header item-details-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col-9">
                        <h3 class="col-12 text-end fs-6">
                            تفاصيل الصنف:
                        </h3>
                        <h3 class="text-end col-12 fs-6">
                            <?php
echo $rows[0]['item_name'] . ' [كود ( ' . $rows[0]['item_code'] . ' )]';
        ?>
                        </h3>
                        <h3 class="text-end col-12 fs-6">
                            <?php
echo '[المتاح: ' . $available . ']';
        ?>
                        </h3>
                    </div>
                    <div class="col-3 backBtn">
                        <a href="?applicatoin=show" class="d-flex align-items-center justify-content-center btn btn-primary text-decoration-none">
                                رجوع
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped text-center">
                    <tr>
                    <th>تاريخ الشراء</th>
                    <th>سعر الشراء</th>
                    <th>كمية الشراء</th>
                    <th>أقل عدد</th>
                </tr>
                <?php
foreach ($rows as $row) {
            ?>
                    <tr class="align-middle">
                        <td><?php echo $row['adding_date'] ?></td>
                        <td><?php echo $row['purchase_price'] ?></td>
                        <td><?php echo $row['amount'] ?></td>
                        <td><?php echo $row['least_amount'] ?></td>
                    </tr>
                    <?php }?>
                </table>
                </div>
                </div>
                <?php
} elseif ($application == 'update') {
        // Get data before edit to compare with
        global $beforeEdit;
        $item_code = $_GET['code'];
        $stmt = $con->prepare("SELECT * FROM items WHERE item_code = ? ORDER BY adding_date DESC");
        $stmt->execute(array($item_code));
        $items = $stmt->fetchAll();
        $beforeEdit = $items[0];
        // Check all fields made
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['cat'] == 0 || $_POST['subcat'] == 0) {
                echo '<div class="alert alert-danger text-center mt-5">يرجى إتمام جميع الاختيارات الخاصة بالقسم والقسم الفرعى</div>';
                $url = $_SERVER['HTTP_REFERER'];
                header("refresh:2 url=$url");
                exit();
            }
            // Catch inputs
            $catid = $_POST['cat'];
            $subcatid = $_POST['subcat'];
            $itemname = filter_var($_POST['item1'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
            $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_INT);
            $leastamount = filter_var($_POST['leastamount'], FILTER_SANITIZE_NUMBER_INT);
            // Updating DB
            if (
                $catid != $beforeEdit['cat_id'] ||
                $subcatid != $beforeEdit['subcat_id'] ||
                !empty($itemname) ||
                !empty($leastamount) ||
                $leastamount == 0 ||
                !empty($price) ||
                !empty($amount) ||
                $amount == 0
            ) {
                // Check changes in cat, subcat and name
                if ($catid != $beforeEdit['cat_id'] || $subcatid != $beforeEdit['subcat_id'] || !empty($itemname)) {
                    // Check name in this cat/subcat combination
                    $itemname = (empty($itemname)) ? $beforeEdit['item_name'] : $itemname;
                    $stmt = $con->prepare("SELECT * FROM items WHERE cat_id = ? AND subcat_id = ? AND item_name = ?");
                    $stmt->execute(array($catid, $subcatid, $itemname));
                    $check = $stmt->rowCount();
                    if ($check > 0) { // if found, get back
                        echo '<div class="alert alert-danger text-center mt-5">هذا الصنف موجود بالفعل</div>';
                        $url = "items.php?application=edit&itemcode=" . $beforeEdit['item_code'];
                        header("refresh:1 url=$url");
                        exit();
                    } else { // if not found, apply the changes
                        $stmt = $con->prepare('UPDATE items SET cat_id = ?, subcat_id = ?, item_name = ? WHERE item_code = ?');
                        $stmt->execute(array($catid, $subcatid, $itemname, $beforeEdit['item_code']));
                    }
                }
                // Check least amount changes
                if (!empty($leastamount) || $leastamount == 0) {
                    $stmt2 = $con->prepare("UPDATE items SET least_amount = ? WHERE item_code = ?");
                    $stmt2->execute(array($leastamount, $beforeEdit['item_code']));
                }
                // Check changes in price and amount
                if (!empty($price) || !empty($amount) || $amount == 0) {
                    // Set price and amount values based on their relative field values
                    $price = (empty($price)) ? $beforeEdit['purchase_price'] : $price;
                    $amount = (empty($amount) && $amount != 0) ? $beforeEdit['amount'] : $amount;
                    $stmt3 = $con->prepare("UPDATE items SET purchase_price = ?, amount = ? WHERE item_id = ?");
                    $stmt3->execute(array($price, $amount, $beforeEdit['item_id']));
                }
                // Showing success msg and get back to items main page
                echo '<div class="alert alert-success text-center mt-5">تم تعديل البيانات بنجاح</div>';
                header("refresh:1 url=items.php");
                exit();
            } else {
                // No changes made msg
                echo '<div class="alert alert-danger text-center mt-5">لم يتم تعديل أى بيانات</div>';
                $url = "items.php?application=edit&itemcode=" . $beforeEdit['item_code'];
                header("refresh:1 url=$url");
                exit();
            }
        }
    } elseif ($application == 'alert') {
        ?>
    <h1 class="text-center">الأصناف على وشك النفاد</h1>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">الأصناف</h4>
            <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>اسم الصنف</th>
                        <th>الكمية المتبقية</th>
                        <th>أقل عدد</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$stmt = $con->prepare('SELECT *,SUM(amount) AS totalAmount, SUM(amount_sold) AS totalSold FROM items GROUP BY item_code');
        $stmt->execute();
        $items = $stmt->fetchAll();
        foreach ($items as $item) {
            $remaining = floatval(floatval($item['totalAmount']) - floatval($item['totalSold']));
            $least = floatval($item['least_amount']);
            if ($least == 0) {
                continue;
            } elseif ($remaining <= $least) {
                echo '<tr>';
                echo '<td>' . $item['item_code'] . '</td>';
                echo '<td>' . $item['item_name'] . '</td>';
                echo '<td>' . $remaining . '</td>';
                echo '<td>' . $least . '</td>';
                echo '</tr>';
            }
        }
        ?>
                </tbody>
            </table>
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
