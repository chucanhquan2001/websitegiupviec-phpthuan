<div class="container-fluid" style="background-color: #F5F5F5;height: 45px;">
    <div class="container">
        <div class="row" style="color: #333;font-size: 16px;font-family: sans-serif;padding-top: 10px;">
            <div class="col-md-10">
                <i class="fas fa-phone-square-alt"></i> &nbsp;&nbsp;&nbsp;&nbsp;085.300.9301 - 082.889.0896
            </div>
            <div class="col-md-2 icon_header">
                <i class="fab fa-youtube"></i>&nbsp;&nbsp; <i class="fab fa-facebook"></i> &nbsp;&nbsp;<i class="fas fa-envelope"></i> &nbsp;&nbsp;<i class="fab fa-instagram-square"></i>
            </div>
        </div>
    </div>
</div>
<div class="head_nav" id="main-menu-tto">
    <div class="container-fluid" style="height: 100px;background-color: white;">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <a href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>content/images/logo.png" alt="" height="80px" style="margin-top: 7px"></a>
                </div>
                <div class="col-md-7" style="margin-top: 20px;">
                    <nav class="navbar navbar-expand-lg navbar-light" style="font-family: sans-serif;">
                        <a class="navbar-brand" href="<?= BASE_URL ?>index.php"><i class="fas fa-home"></i></a>&nbsp;
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Tìm người giúp việc
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <?php foreach (selectall("select * from dich_vu") as $item) { ?>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>site/dich_vu/nhan_vien.php?id=<?= $item['id'] ?>"><?= $item['ten_dich_vu'] ?></a>
                                        <?php } ?>
                                    </div>
                                </li>
                                &nbsp;
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Tìm việc làm
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <?php foreach (selectall("select * from dich_vu") as $item) { ?>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>site/dich_vu/tuyen_dung.php?id=<?= $item['id'] ?>"><?= $item['ten_dich_vu'] ?></a>
                                        <?php } ?>
                                    </div>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>site/tin_tuc/index.php?product=1">Tin tức</a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>site/lien_he">Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <?php if (isset($_SESSION['admin'])) {   ?>
                    <div class="col-md-1" style="padding-top: 28px;">
                        <nav class="navbar navbar-expand-lg navbar-light" style="height: 20px;margin-top: 10px;">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="<?= BASE_URL ?>content/images_user/<?= $_SESSION['admin']['hinh_anh'] ?>" alt="" height="40px" width="40px" style="border-radius: 40px;border: 0.5px solid black;padding: 3px;">&nbsp;
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="#"><?= $_SESSION['admin']['ho_ten'] ?></a>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>site/tai_khoan"><b>Thông tin</b></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>site/tai_khoan/dang_xuat.php" style="background-color: #ea7373; color: white" onclick="return alert('Bạn chắc chắn muốn đăng xuất chứ ?')">Đăng xuất<span style="float: right"><i class="fas fa-sign-out-alt"></i></span></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                <?php } elseif (isset($_SESSION['user'])) {   ?>
                    <div class="col-md-1" style="padding-top: 28px;">
                        <nav class="navbar navbar-expand-lg navbar-light" style="height: 20px;margin-top: 10px;">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="<?= BASE_URL ?>content/images_user/<?= $_SESSION['user']['hinh_anh'] ?>" alt="" height="40px" width="40px" style="border-radius: 30px;border: 0.5px solid black;padding: 3px;">&nbsp;
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="#"><?= $_SESSION['user']['ho_ten'] ?></a>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>site/tai_khoan"><b>Thông tin</b></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>site/tai_khoan/dang_xuat.php" style="background-color: #ea7373; color: white" onclick="return alert('Bạn chắc chắn muốn đăng xuất chứ ?')">Đăng xuất<span style="float: right"><i class="fas fa-sign-out-alt"></i></span></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                <?php } else {   ?>
                    <div class="col-md-3" style="padding-top: 28px;">
                        <a href="<?= BASE_URL ?>site/tai_khoan/dang_ky.php" class="btn btn-outline-secondary">Đăng kí</a>&nbsp;
                        <a href="<?= BASE_URL ?>site/tai_khoan/login.php" class="btn btn-success">Đăng nhập</a>
                    </div>
                <?php } ?>

                <?php if (isset($_SESSION['admin'])) { ?>
                    <div class="col-md-2" style="padding: 33px 0 0 52px;">
                        <a href="<?= BASE_URL ?>admin" style="color: black;">Quản trị</a>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['user'])) { ?>
                    <div class="col-md-2" style="padding: 17px 0 0 52px;">
                        <i class="fas fa-phone" style="font-size: 30px"></i> &nbsp; &nbsp; Hot line <span style="font-size: 20px; color: #5353dd">085.300.9301</span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>