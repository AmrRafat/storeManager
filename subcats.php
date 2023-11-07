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
    <h1 class="text-center">الأقسام الفرعية</h1>
    <div class="card row mt-4">
        <div class="card-header ps-4 pe-4 d-flex justify-content-between">
            <h4>الأقسام الفرعية</h4>
            <div class="btns">
                <span class="btn btn-primary newSubcat">إضافة قسم فرعى جديد</span>
                <a class="text-decoration-none btn btn-primary" href="subcats.php?application=undo">الأقسام الفرعية المحذوفة</a>
            </div>
        </div>
        <div class="card-body">
        <div class="form-control mb-3 py-3 text-center newSubcatForm">
            <div class="row justify-content-center">
                <div class="col-3 position-relative">
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
                </div>
                <div class="col-3 position-relative">
                    <input type="text" class="z-3 text-center form-control" name="subcat_name" required='required' autocomplete="off" placeholder="اسم القسم الفرعى">
                </div>
                <div class="col-1">
                    <span class="btn btn-primary form-control subcatAdd">إضافة</span>
                </div>
                <div class="col-1">
                    <span class="btn btn-danger form-control subcatEnd">إنهاء</span>
                </div>
            </div>
    </div>
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
                            <a class="text-decoration-none btn btn-success ms-2" href="?application=edit&subcatid=<?php echo $row['subcat_id'] ?>">تعديل</a>
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
            <form class="form-control p-3 text-center" action="?application=update&subcatid=<?php echo $subcatid ?>" method="post">
            <input type="hidden" name="original_subcat_name" value="<?php echo $subcat['subcat_name'] ?>">
            <table class="table table-dark table-striped">
                <thead>
                    <tr class="align-middle">
                        <td class="pe-3">القسم</td>
                        <td>الاسم القديم للقسم الفرعى</td>
                        <td>الاسم الجديد</td>
                        <td class="ps-3"><a href="?application=show" class="text-decoration-none btn btn-primary form-control">رجوع</a></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="align-middle">
                        <td class="pe-3">
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
                        </td>
                        <td>
                        <?php echo $subcat['subcat_name'] ?>
                        </td>
                        <td>
                        <input type="text" class="z-3 text-center form-control" name="subcat_name" autocomplete="off" placeholder="يمكنك تغيير اسم القسم الفرعى هنا إذا أردت">
                        </td>
                        <td class="ps-3">
                        <input type="submit" value="تعديل" class="btn btn-primary form-control">
                        </td>
                    </tr>
                </tbody>
            </table>
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
