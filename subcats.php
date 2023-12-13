<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'قصر الملكة';
    include "init.php";
    $today = date("j / n / Y");
    $application = isset($_GET['application']) ? $_GET['application'] : 'show';
    ?>
<div class="container px-3 px-lg-0">
    <?php
if ($application == 'show') {
        ?>
    <h1 class="text-center">الأقسام الفرعية</h1>
    <div class="card row mt-4">
        <div class="card-header px-4">
            <div class="row justify-content-between align-items-center">
                <h4 class="col-12 col-lg-3 text-center text-lg-end">الأقسام الفرعية</h4>
                <div class="btns col-12 col-lg-9 justify-content-center justify-content-lg-end d-flex">
                    <span class="btn btn-primary newSubcat ms-2 px-2">إضافة قسم فرعى جديد</span>
                    <a class="text-decoration-none btn btn-primary" href="subcats.php?application=undo">الأقسام الفرعية المحذوفة</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="newSubcatForm rounded-3">
                <div class="input-group w-75 mx-auto py-3 flex-column flex-lg-row">
                    <span class="input-group-text">القسم</span>
                    <select name="cats" class="text-center form-control z-3" required = 'required'>
                        <option value="0">يرجى اختيار القسم</option>
                        <?php
$stmt = $con->prepare("SELECT * FROM cats WHERE del = 0 ORDER BY cat_name");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        foreach ($cats as $cat) {
            echo '<option value="';
            echo $cat['cat_id'];
            echo '">';
            echo $cat['cat_name'];
            echo '</option>';
        }
        ?>
                    </select>
                    <span class="input-group-text">القسم الفرعى</span>
                    <input type="text" class="z-3 text-center form-control" name="subcat_name" required='required' autocomplete="off" placeholder="اسم القسم الفرعى">
                    <button type="submit" class="btn btn-success subcatAdd">إضافة</button>
                    <button type="button" class="btn btn-danger subcatEnd">إغلاق</button>
                </div>
            </form>
    <div class="text-center alert alert-danger error" style="display: none;">هذا القسم الفرعى موجود بالفعل</div>
        <div class="text-center alert alert-danger error1" style="display: none;">برجاء اختيار القسم أولا</div>
        <div class="text-center alert alert-danger error2" style="display: none;">اسم القسم الفرعى فارغ</div>
        <div class="text-center alert alert-success success" style="display: none;">تمت إضافة القسم الفرعى بنجاح</div>
    <div class="subcatShow">
        <?php
$stmt = $con->prepare("SELECT
                        subcats.*,
                        users.fullname AS fullname,
                        cats.cat_name AS catName
                    FROM
                        subcats
                    INNER JOIN users ON
                    subcats.user_id = users.user_id
                    INNER JOIN cats ON
                    subcats.cat_id = cats.cat_id
                    WHERE subcats.del = 0
                    ORDER BY subcat_name");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count == 0) {
            echo '<div class="text-center alert alert-info">لا توجد أقسام فرعية بعد... يرجى إضافة قسم فرعى جديد</div>';
        } else {
            ?>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th><span class="subCat asc">اسم القسم الفرعى</span></th>
                        <th><span class="mainCat">اسم القسم الرئيسى</span></th>
                        <th>تاريخ الإنشاء</th>
                        <th>الشخص الذى أنشأه</th>
                        <th>التحكم</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
foreach ($rows as $row) {?>
                    <tr class="align-middle">
                        <td><?php echo $row['subcat_name'] ?></td>
                        <td><?php echo $row['catName'] ?></td>
                        <td><?php echo $row['adding_date'] ?></td>
                        <td><?php echo $row['fullname'] ?></td>
                        <td>
                            <a class="text-decoration-none btn btn-success ms-lg-2 ms-0 mb-2 mb-lg-0" href="?application=edit&subcatid=<?php echo $row['subcat_id'] ?>">تعديل</a>
                            <a class="text-decoration-none btn btn-danger" href="?application=del&subcatid=<?php echo $row['subcat_id'] ?>">حذف</a>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
                </table>
                <?php }?>
            </div>
        </div>
                <?php } elseif ($application == 'edit') {
        if (isset($_GET['subcatid'])) {
            $subcatid = $_GET['subcatid'];
            $stmt = $con->prepare("SELECT * FROM subcats WHERE subcat_id = ?");
            $stmt->execute(array($_GET['subcatid']));
            $subcat = $stmt->fetch();?>
            <h1 class="text-center">تعديل القسم الفرعى</h1>
            <form class="form-control editSubCat p-3 text-center" action="?application=update&subcatid=<?php echo $subcatid ?>" method="post">
            <input type="hidden" name="original_subcat_name" value="<?php echo $subcat['subcat_name'] ?>">
            <div class="input-group w-100 mx-auto">
                        <span class="input-group-text editSubcatlabel">اسم القسم الفرعى القديم</span>
                        <span class="input-group-text form-control justify-content-center"><h5><?php echo $subcat['subcat_name'] ?></h5></span>
                    </div>
                    <div class="input-group w-100 mx-auto my-3">
                        <span class="input-group-text editSubcatlabel">اسم القسم الفرعى الجديد</span>
                        <input type="text" class="z-3 text-center form-control" name="subcat_name" autocomplete="off" placeholder="يمكنك تغيير اسم القسم الفرعى هنا إذا أردت">
                    </div>
                    <div class="input-group w-100 mx-auto mb-3">
                <span class="input-group-text editSubcatlabel justify-content-center">القسم</span>
                <select name="cats" class="text-center z-3 form-control">
                            <option value="0">يرجى اختيار القسم</option>
                            <?php
$stmt = $con->prepare("SELECT * FROM cats WHERE del = 0 ORDER BY cat_name");
            $stmt->execute();
            $cats = $stmt->fetchAll();
            foreach ($cats as $cat) {
                echo '<option ';
                if ($cat['cat_id'] == $subcat['cat_id']) {
                    echo ' selected ';
                }
                echo ' value="';
                echo $cat['cat_id'];
                echo '"';
                echo '>';
                echo $cat['cat_name'];
                echo '</option>';
            }
            ?>
                        </select>
                    </div>
                    <div class="row w-100 justify-content-center mx-auto">
                        <div class="col-5 col-md-4 col-lg-3">
                            <button type="submit" class="btn form-control btn-success">حفظ التعديلات</button>
                        </div>
                        <div class="col-3 col-md-2">
                            <a href="?application=show" class="text-decoration-none text-reset"><button type="button" class="btn form-control btn-primary">رجوع</button></a>
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
        <?php } elseif ($application == 'update') {
        echo '<h1 class="text-center">تعديل قسم فرعى</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['cats'] == 0) {
                echo '<div class="alert alert-danger text-center mt-5">يرجى اختيار القسم الرئيسى</div>';
                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                    $url = $_SERVER['HTTP_REFERER'];
                } else {
                    $url = 'index.php';
                }
                header("refresh:2 url=$url");
                exit();
            }
            $cat_id = $_POST['cats'];
            $subcat_name = !empty($_POST['subcat_name']) ? filter_var($_POST['subcat_name'], FILTER_SANITIZE_STRING) : $_POST['original_subcat_name'];
            $user_id1 = $_SESSION['id'];
            $stmt = $con->prepare("SELECT * FROM subcats WHERE subcat_name = ? AND cat_id = ?");
            $stmt->execute(array($subcat_name, $cat_id));
            $count = $stmt->rowCount();
            if ($count > 0) {
                $found_subcat = $stmt->fetch();
                if ($found_subcat['subcat_id'] == $_GET['subcatid']) {
                    echo '<div class="alert alert-danger text-center mt-5">لم يتم حدوث أى تغيير</div>';
                    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                        $url = $_SERVER['HTTP_REFERER'];
                    } else {
                        $url = 'index.php';
                    }
                    header("refresh:2 url=subcats.php");
                    exit();
                }
                echo '<div class="alert alert-danger text-center mt-5">هذا القسم الفرعى موجود بالفعل</div>';
                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                    $url = $_SERVER['HTTP_REFERER'];
                } else {
                    $url = 'index.php';
                }
                header("refresh:2 url=$url");
                exit();
            } else {
                $stmt = $con->prepare("UPDATE subcats SET subcat_name = ?, cat_id = ? WHERE subcat_id = ?");
                $stmt->execute(array($subcat_name, $cat_id, $_GET['subcatid']));
                echo '<div class="alert alert-success text-center mt-5">تم تعديل القسم الفرعى بنجاح</div>';
                header("refresh:2 url=subcats.php");
                exit();
            }
        }
        ?>
        <?php } elseif ($application == 'del') {
        if (isset($_GET['subcatid'])) {
            $subcatid = $_GET['subcatid'];
            $stmt1 = $con->prepare("SELECT * FROM items WHERE subcat_id = ?");
            $stmt1->execute(array($subcatid));
            $check = $stmt1->rowCount();
            if ($check == 0) {
                $stmt = $con->prepare("UPDATE subcats SET del = 1, del_id = ?, del_date = now() WHERE subcat_id = ?");
                $stmt->execute(array($_SESSION['id'], $subcatid));
                echo '<div class="alert alert-success text-center mt-5">تم حذف القسم الفرعى بنجاح</div>';
                header("refresh:2 url=subcats.php");
                exit();
            } else {
                echo '<div class="alert alert-danger text-center mt-5">لا يمكن حذف القسم الفرعى</div>';
                header("refresh:2 url=subcats.php");
                exit();
            }
        }
        ?>
            <?php } elseif ($application == 'undo') {?>
                <h1 class="text-center">الأقسام الفرعية المحذوفة</h1>
                <div class="card row mt-4">
                    <div class="card-header ps-4 pe-4 d-flex justify-content-between">
                        <h4>الأقسام الفرعية المحذوفة</h4>
                        <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
                    </div>
        <div class="card-body">
            <?php
$stmt = $con->prepare("SELECT subcats.*, u1.fullname AS adding_fullname, u2.fullname AS del_fullname, cats.cat_name AS catName FROM subcats INNER JOIN users AS u1 ON subcats.user_id = u1.user_id INNER JOIN users AS u2 ON subcats.del_id = u2.user_id INNER JOIN cats ON subcats.cat_id = cats.cat_id WHERE subcats.del = 1 ORDER BY del_date");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count == 0) {
            echo '<div class="text-center alert alert-info">لا توجد أقسام فرعية محذوفة</div>';
            header("refresh:2 url=subcats.php");
            exit();
        } else {
            ?>
            <table class="table table-striped text-center">
                <tr>
                    <th>اسم القسم</th>
                    <th>القسم الفرعى</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>تاريخ الحذف</th>
                    <th>الشخص الذى حذفه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
                ?>
                    <tr class="align-middle">
                        <td><?php echo $row['subcat_name'] ?></td>
                        <td><?php echo $row['catName'] ?></td>
                        <td><?php echo $row['adding_date'] ?></td>
                        <td><?php echo $row['adding_fullname'] ?></td>
                        <td><?php echo $row['del_date'] ?></td>
                        <td><?php echo $row['del_fullname'] ?></td>
                        <td>
                            <a class="text-decoration-none btn btn-success" href="?application=back&subcatid=<?php echo $row['subcat_id'] ?>">استرجاع</a>
                        </td>
                    </tr>
                    <?php }?>
                </table>
                <?php }?>
            </div>
            <?php } elseif ($application == 'back') {
        echo '<h1 class="text-center">تم استرجاع القسم الفرعى</h1>';
        if (isset($_GET['subcatid'])) {
            $subcatid = $_GET['subcatid'];
            $stmt = $con->prepare("UPDATE subcats SET del = 0, user_id = ?, del_date = ?, adding_date = now() WHERE subcat_id = ?");
            $stmt->execute(array($_SESSION['id'], mktime(00, 00, 00, 01, 01, 2200), $subcatid));
            echo '<div class="alert alert-success text-center mt-5">تم استرجاع القسم الفرعى بنجاح</div>';
            header("refresh:2 url=subcats.php");
            exit();
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
