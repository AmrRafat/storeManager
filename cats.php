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
    <h1 class="text-center">الأقسام</h1>
    <div class="card mt-4">
        <div class="card-header px-4">
            <div class="row justify-content-between align-items-center">
                <h4 class="col-12 col-lg-3 text-center text-lg-end">الأقسام</h4>
                <div class="btns col-12 col-lg-9 justify-content-center justify-content-lg-end d-flex">
                    <span class="btn btn-primary ms-3 newCat">إضافة قسم جديد</span>
                    <a class="text-decoration-none btn btn-primary" href="cats.php?application=undo">الأقسام المحذوفة</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <from class="catAdd">
                <div class="input-group w-100 w-md-75 w-lg-50 mx-auto p-3">
                    <input type="text" class="z-3 form-control text-center" name="cat_name" required='required' autocomplete="off" placeholder="اسم القسم">
                    <button type="submit" class="btnAdd btn btn-primary">إضافة</button>
                    <button type="button" class="btnEnd btn btn-danger">إغلاق</button>
                </div>
            </from>
            <div class="text-center alert alert-danger errorEmpty" style="display: none;">اسم القسم فارغ</div>
            <div class="text-center alert alert-danger error" style="display: none;">هذا القسم موجود بالفعل</div>
            <div class="text-center alert alert-success success" style="display: none;">تمت إضافة القسم بنجاح</div>
            <div class="cats">
                <?php
$stmt = $con->prepare("SELECT cats.*, users.fullname AS fullname FROM cats INNER JOIN users ON cats.user_id = users.user_id WHERE DEL = 0 ORDER BY cat_name");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count == 0) {
            echo '<div class="text-center alert alert-info">لا توجد أقسام بعد... يرجى إضافة قسم جديد</div>';
        } else {
            ?>
            <table class="table table-striped text-center">
                <tr>
                    <th>اسم القسم</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الشخص الذى أنشأه</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {?>
                    <tr class="align-middle">
                        <td><?php echo $row['cat_name'] ?></td>
                        <td><?php echo $row['adding_date'] ?></td>
                        <td><?php echo $row['fullname'] ?></td>
                        <td>
                            <a class="text-decoration-none btn btn-success ms-0 ms-lg-2 mb-2 mb-lg-0" href="?application=edit&catid=<?php echo $row['cat_id'] ?>">تعديل</a>
                            <a class="text-decoration-none btn btn-danger" href="?application=del&catid=<?php echo $row['cat_id'] ?>">حذف</a>
                        </td>
                    </tr>
                    <?php }?>
                </table>
                <?php }?>
            </div>
        </div>
        <?php } elseif ($application == 'edit') {
        if (isset($_GET['catid'])) {
            $catid = $_GET['catid'];
            $stmt = $con->prepare("SELECT * FROM cats WHERE cat_id = ?");
            $stmt->execute(array($_GET['catid']));
            $cat = $stmt->fetch();?>
            <h1 class="text-center">تعديل القسم</h1>
            <form class="catEdit form-control text-center p-4" action="?application=update&catid=<?php echo $catid ?>" method="post">
                <div class="input-group w-75 mb-3 mx-auto">
                    <span class="input-group-text">اسم القسم القديم</span>
                    <span class="input-group-text"><h5><?php echo $cat['cat_name'] ?></h5></span>
                    <span class="input-group-text">الاسم الجديد للقسم</span>
                    <input type="text" class="z-3 form-control text-center" name="cat_name" required='required' autocomplete="off" placeholder="الاسم الجديد للقسم">
                </div>
                <div class="row gab-3 justify-content-center">
                    <div class="col-lg-3 col-4">
                        <input type="submit" value="تعديل" class="form-control btn btn-primary">
                    </div>
                    <div class="col-lg-3 col-4">
                        <a href="?application=show" class="btn btn-primary form-control text-decoration-none">رجوع</a>
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
        echo '<h1 class="text-center">تعديل قسم</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cat_name = filter_var($_POST['cat_name'], FILTER_SANITIZE_STRING);
            $user_id1 = $_SESSION['id'];
            $stmt = $con->prepare("SELECT cat_name FROM cats WHERE cat_name = ?");
            $stmt->execute(array($cat_name));
            $count = $stmt->rowCount();
            if ($count > 0) {
                echo '<div class="alert alert-danger text-center mt-5">هذا القسم موجود بالفعل</div>';
                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                    $url = $_SERVER['HTTP_REFERER'];
                } else {
                    $url = 'index.php';
                }
                header("refresh:2 url=$url");
                exit();
            } else {
                $stmt = $con->prepare("UPDATE cats SET cat_name = ? WHERE cat_id = ?");
                $stmt->execute(array($cat_name, $_GET['catid']));
                echo '<div class="alert alert-success text-center mt-5">تم تعديل القسم بنجاح</div>';
                header("refresh:2 url=cats.php");
                exit();
            }
        }
        ?>
        <?php } elseif ($application == 'del') {
        if (isset($_GET['catid'])) {
            $catid = $_GET['catid'];
            $stmt1 = $con->prepare('SELECT * FROM items WHERE cat_id = ?');
            $stmt1->execute(array($catid));
            $check1 = $stmt1->rowCount();
            $stmt2 = $con->prepare("SELECT * FROM subcats WHERE cat_id = ?");
            $stmt2->execute(array($catid));
            $check2 = $stmt2->rowCount();
            if ($check1 > 0 || $check2 > 0) {
                echo '<div class="alert alert-danger text-center mt-5">لا يمكن حذف القسم</div>';
                header("refresh:2 url=cats.php");
                exit();
            } else {
                $stmt = $con->prepare("UPDATE cats SET del = 1, del_id = ?, del_date = now() WHERE cat_id = ?");
                $stmt->execute(array($_SESSION['id'], $catid));
                echo '<div class="alert alert-success text-center mt-5">تم حذف القسم بنجاح</div>';
                header("refresh:2 url=cats.php");
                exit();
            }
        }
        ?>
            <?php } elseif ($application == 'undo') {?>
                <h1 class="text-center">الأقسام المحذوفة</h1>
                <div class="card row mt-4">
                    <div class="card-header ps-4 pe-4 d-flex justify-content-between">
                        <h4>الأقسام المحذوفة</h4>
                        <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
                    </div>
                    <div class="card-body">
                        <?php
$stmt = $con->prepare("SELECT cats.*, u1.fullname AS adding_fullname, u2.fullname AS del_fullname FROM cats INNER JOIN users AS u1 ON cats.user_id = u1.user_id INNER JOIN users AS u2 ON cats.del_id = u2.user_id WHERE DEL = 1 ORDER BY del_date");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count == 0) {
            echo '<div class="text-center alert alert-info">لا توجد أقسام محذوفة</div>';
            header("refresh:2 url=cats.php");
            exit();
        } else {
            ?>
            <table class="table table-striped text-center">
                <tr>
                    <th>اسم القسم</th>
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
                        <td><?php echo $row['cat_name'] ?></td>
                        <td><?php echo $row['adding_date'] ?></td>
                        <td><?php echo $row['adding_fullname'] ?></td>
                        <td><?php echo $row['del_date'] ?></td>
                        <td><?php echo $row['del_fullname'] ?></td>
                        <td>
                            <a class="text-decoration-none btn btn-primary" href="?application=back&catid=<?php echo $row['cat_id'] ?>">استرجاع</a>
                        </td>
                    </tr>
                    <?php }?>
                </table>
                <?php }?>
            </div>
            <?php } elseif ($application == 'back') {
        echo '<h1 class="text-center">تم استرجاع القسم</h1>';
        if (isset($_GET['catid'])) {
            $catid = $_GET['catid'];
            $stmt = $con->prepare("UPDATE cats SET del = 0, user_id = ?, del_date = ?, adding_date = now() WHERE cat_id = ?");
            $stmt->execute(array($_SESSION['id'], mktime(00, 00, 00, 01, 01, 2200), $catid));
            echo '<div class="alert alert-success text-center mt-5">تم استرجاع القسم بنجاح</div>';
            header("refresh:2 url=cats.php");
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
