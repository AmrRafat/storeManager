<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
<div class="container">
    <a class="navbar-brand" href="records.php">
        <img src="layout/imgs/logo_cropped.png" class="img-fluid" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link recordsPage" aria-current="page" href="records.php">السجل</a>
        </li>
        <li class="nav-item">
            <a class="nav-link instaPage" aria-current="page" href="installments.php">الأقساط</a>
        </li>
        <?php if ($_SESSION['access'] == 1 || $_SESSION['access'] == 4) {
    ?>
        <li class="nav-item">
            <a class="nav-link usersPage" aria-current="page" href="users.php">المستخدمين</a>
        </li>
        <?php }?>
        <li class="nav-item dropdown settings">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            الإعدادات
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="items.php">المخزن</a></a></li>
                <li><hr class="dropdown-divider"></li>
                <?php if ($_SESSION['access'] == 1 || $_SESSION['access'] == 4) {?>
                <li><a class="dropdown-item" href="cats.php">الأقسام</a></li>
                <li><a class="dropdown-item" href="subcats.php">الأقسام الفرعية</a></li>
                <li><a class="dropdown-item" href="bills.php">الفواتير</a></li>
                <li><a class="dropdown-item" href="calcs.php">الحسابات</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php }?>
                <li><a class="dropdown-item" href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </li>
    </ul>
    </div>
</div>
</nav>
