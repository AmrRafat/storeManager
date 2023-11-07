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
    <input type="hidden" class="title" value="users">
    <?php
if ($application == 'show') {
        ?>
    <h1 class="text-center">المستخدمين</h1>
    <div class="card row mt-4">
        <div class="card-header ps-4 pe-4 d-flex justify-content-between">
            <h4>المستخدمين</h4>
            <div class="btns">
                <a class="text-decoration-none btn btn-primary" href="users.php?application=add">إضافة مستخدم جديد</a>
                <a class="text-decoration-none btn btn-primary" href="users.php?application=undo">المستخدمين المحذوفين</a>
            </div>
        </div>
        <div class="card-body">
            <?php
$stmt = $con->prepare("SELECT users.* FROM users WHERE access != 3 && access != 4 ORDER BY access AND fullname");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count == 0) {
            echo '<div class="text-center alert alert-info">لا يوجد أى مستخدمين... يرجى ضافة مستخدمين</div>';
        } else {
            ?>
            <table class="table table-striped text-center">
                <tr>
                    <th>اسم المستخدم الكامل</th>
                    <th>اسم المستخدم للدخول</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الصلاحيات</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {?>
                    <tr class="align-middle">
                    <td><?php echo $row['fullname'] ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td><?php echo $row['adding_date'] ?></td>
                    <td><?php
if ($row['access'] == 1) {
                echo 'صاحب محل';
            } else {
                echo 'بائع';
            }
                ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=edit&userid=<?php echo $row['user_id'] ?>">تعديل</a>
                        <a class="text-decoration-none btn btn-danger" href="?application=del&userid=<?php echo $row['user_id'] ?>">حذف</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }?>
        </div>
        <?php } elseif ($application == 'add') {?>
            <h1 class="text-center">إضافة مستخدم جديد</h1>
            <form class="form-control text-center userAdd p-4 mt-5" action="?application=apply" method="post">
                <div class="row">
                    <div class="col-10">
                        <div class="row mb-3 justify-content-center">
                            <div class="col-6">
                                <input type="text" class="z-3 text-center form-control" name="userfullname" required='required' autocomplete="off" placeholder="اسم المستخدم الكامل">
                            </div>
                            <div class="col-6">
                                <select name="access" class="text-center z-3 form-control" required = 'required'>
                                    <option value="0">بائع</option>
                                    <option value="1">صاحب محل</option>
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <input type="text" class="z-3 text-center form-control" name="username" required='required' autocomplete="off" placeholder="اسم المستخدم للدخول">
                            </div>
                            <div class="col-6">
                                <input type="password" class="z-3 text-center form-control" name="password" required='required' autocomplete="off" placeholder="كلمة المرور">
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="row justify-content-center">
                            <div class="col">
                                <input type="submit" value="إضافة" class="form-control btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php } elseif ($application == 'apply') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_fullname = filter_var($_POST['userfullname'], FILTER_SANITIZE_STRING);
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $hashedPass = sha1(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
            $access = $_POST['access'];
            $stmt = $con->prepare("SELECT username FROM users WHERE username = ?");
            $stmt->execute(array($username));
            $count = $stmt->rowCount();
            if ($count > 0) {
                echo '<div class="alert alert-danger text-center mt-5">اسم المستخدم للدخول موجود بالفعل... يرجى اختيار اسم مستخدم مختلف</div>';
                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                    $url = $_SERVER['HTTP_REFERER'];
                } else {
                    $url = 'index.php';
                }
                header("refresh:3 url=$url");
                exit();
            } else {
                $stmt = $con->prepare("INSERT INTO users(username, password, fullname, adding_date, access) VALUES(?,?,?,now(),?)");
                $stmt->execute(array($username, $hashedPass, $user_fullname, $access));
                echo '<div class="alert alert-success text-center mt-5">تم إضافة المستخدم بنجاح</div>';
                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                    $url = $_SERVER['HTTP_REFERER'];
                } else {
                    $url = 'index.php';
                }
                header("refresh:2 url=$url");
                exit();
            }
        }
        ?>
                <h1 class="text-center">إضافة مستخدم جديد</h1>
                <?php } elseif ($application == 'edit') {
        if (isset($_GET['userid'])) {
            $userid = $_GET['userid'];
            $stmt = $con->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute(array($userid));
            $user = $stmt->fetch();?>
            <h1 class="text-center">تعديل المستخدم</h1>
            <form class="form-control text-center p-4" action="?application=update&userid=<?php echo $userid ?>" method="post">
                <input type="hidden" name="original_user_name" value="<?php echo $user['username'] ?>">
                <input type="hidden" name="original_user_fullname" value="<?php echo $user['fullname'] ?>">
                <input type="hidden" name="original_user_password" value="<?php echo $user['password'] ?>">
                <input type="hidden" name="original_user_access" value="<?php echo $user['access'] ?>">
                <table class="table table-striped rounded align-middle">
                    <tr class="rounded-top">
                        <th>نوع البيانات</th>
                        <th>البيانات القديمة</th>
                        <th class="ps-3">التعديلات</th>
                    </tr>
                    <tr>
                        <td>اسم المستخدم بالكامل</td>
                        <td><?php echo $user['fullname'] ?></td>
                        <td class="ps-3"><input type="text" class="z-3 text-center form-control" name="userfullname" autocomplete="off" placeholder="يمكنك تغييره أو تركه كما هو"></td>
                    </tr>
                    <tr>
                        <td>سم المستخدم للدخول</td>
                        <td><?php echo $user['username'] ?></td>
                        <td class="ps-3"><input type="text" class="z-3 text-center form-control" name="username" autocomplete="off" placeholder="يمكنك تغييره أو تركه كما هو"></td>
                    </tr>
                    <tr>
                        <td>كلمة المرور</td>
                        <td>***********</td>
                        <td class="ps-3"><input type="password" class="z-3 text-center form-control" name="password" autocomplete="off" placeholder="يمكنك تغييره أو تركه كما هو"></td>
                    </tr>
                    <tr>
                        <td>صلاحية المستخدم</td>
                        <td><?php if ($user['access'] == 0) {echo 'بائع';} else {echo "صاحب محل";}?></td>
                        <td class="ps-3"><select name="access" class="text-center form-control z-3">
                            <option value="0" <?php if ($user['access'] == 0) {echo ' selected ';}?> >بائع</option>
                            <option value="1"  <?php if ($user['access'] == 1) {echo ' selected ';}?>>صاحب محل</option>
                        </select></td>
                    </tr>
                </table>
                <div class=" row form-control-lg justify-content-center">
                    <div class="col-3">
                        <input type="submit" value="تعديل" class="btn form-control btn-primary">
                    </div>
                    <div class="col-3">
                        <a href="?application=show" class="btn btn-primary form-control text-decoration-none">رجوع</a>
                    </div>
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = !empty($_POST['username']) ? filter_var($_POST['username'], FILTER_SANITIZE_STRING) : $_POST['original_user_name'];
            $fullname = !empty($_POST['userfullname']) ? filter_var($_POST['userfullname'], FILTER_SANITIZE_STRING) : $_POST['original_user_fullname'];
            $hashedPass = !empty($_POST['password']) ? sha1(filter_var($_POST['password'], FILTER_SANITIZE_STRING)) : $_POST['original_user_password'];
            $access = $_POST['access'];
            $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute(array($username));
            $count = $stmt->rowCount();
            if ($count > 0) {
                $found_user = $stmt->fetch();
                if ($found_user['user_id'] != $_GET['userid']) {
                    echo '<div class="alert alert-danger text-center mt-5">اسم المستخدم للدخول موجود بالفعل... يرجى اختيار اسم اخر</div>';
                    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                        $url = $_SERVER['HTTP_REFERER'];
                    } else {
                        $url = 'index.php';
                    }
                    header("refresh:3 url=$url");
                    exit();
                } elseif ($found_user['fullname'] == $fullname && $found_user['access'] == $access && $found_user['password'] == $hashedPass) {
                    echo '<div class="alert alert-danger text-center mt-5">لم يتم حدوث أى تغيير</div>';
                    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                        $url = $_SERVER['HTTP_REFERER'];
                    } else {
                        $url = 'index.php';
                    }
                    header("refresh:2 url=users.php");
                    exit();
                } else {
                    $stmt = $con->prepare("UPDATE users SET username = ?, password = ?, fullname= ?, access = ? WHERE user_id = ?");
                    $stmt->execute(array($username, $hashedPass, $fullname, $access, $_GET['userid']));
                    echo '<div class="alert alert-success text-center mt-5">تم تعديل بيانات المستخدم بنجاح</div>';
                    header("refresh:2 url=users.php");
                    exit();
                }
            }
        }
        ?>
        <h1 class="text-center">حذف مستخدم</h1>
        <?php } elseif ($application == 'del') {
        if (isset($_GET['userid'])) {
            $userid = $_GET['userid'];
            if ($_SESSION['id'] == $userid) {
                echo '<div class="alert alert-danger text-center mt-5">عفوا... لا يمكن حذف نفسك!!</div>';
                header("refresh:2 url=users.php");
                exit();
            }
            $stmt = $con->prepare("UPDATE users SET access = 3 WHERE user_id = ?");
            $stmt->execute(array($userid));
            echo '<div class="alert alert-success text-center mt-5">تم حذف المستخدم بنجاح</div>';
            header("refresh:2 url=users.php");
            exit();
        }
        ?>
            <?php } elseif ($application == 'undo') {?>
                <h1 class="text-center">المستخدمين المحذوفين</h1>
    <div class="card row mt-4">
        <div class="card-header ps-4 pe-4 d-flex justify-content-between">
            <h4>المستخدمين المحذوفين</h4>
            <a href="?application=show" class="btn btn-primary text-decoration-none">رجوع</a>
        </div>
        <div class="card-body">
            <?php
$stmt = $con->prepare("SELECT * FROM users WHERE access = 3 ORDER BY adding_date DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count == 0) {
            echo '<div class="text-center alert alert-info">لا يوجد مستخدمين محذوفين</div>';
            header("refresh:2 url=users.php");
            exit();
        } else {
            ?>
            <table class="table table-striped text-center">
                <tr>
                    <th>اسم المستخدم بالكامل</th>
                    <th>تاريخ الإنشاء</th>
                    <th>التحكم</th>
                </tr>
                <?php
foreach ($rows as $row) {
                ?>
                    <tr class="align-middle">
                    <td><?php echo $row['fullname'] ?></td>
                    <td><?php echo $row['adding_date'] ?></td>
                    <td>
                        <a class="text-decoration-none btn btn-success" href="?application=back&userid=<?php echo $row['user_id'] ?>">استرجاع</a>
                    </td>
                </tr>
                <?php }?>
            </table>
            <?php }?>
        </div>
        <?php } elseif ($application == 'back') {
        if (isset($_GET['userid'])) {
            $userid = $_GET['userid'];
            $stmt = $con->prepare("UPDATE users SET access = 0 WHERE user_id = ?");
            $stmt->execute(array($userid));
            echo '<div class="alert alert-success text-center mt-5">تم استرجاع المستخدم بنجاح... برجاء مراجعة البيانات</div>';
            header("refresh:3 url=users.php");
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
