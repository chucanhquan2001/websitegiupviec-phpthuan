<?php

if (!isset($_SESSION['admin'])) {
    header("Location:" . BASE_URL);
}

?>
<div class="head_nav">
    <div class="container-fluid" style="background-color: #fdf7f7;height: 50px;box-shadow: 0 0 10px 0 #d7bfbf;">
        <div class="container">
            <div class="row">
                <div class="col-md-3" style="padding-top: 10px;height: 50px;">
                    <a href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>content/images/logo.png" alt="" height="40px" width="40px" style="margin-top: -6px"></a>
                </div>

                <div class="col-md-8" style="padding-top: 5px;">
                    <?php if (isset($_SESSION['admin'])) {   ?>
                        <nav class="navbar navbar-expand-lg navbar-light" style="height: 20px;margin-top: 10px;float: right">
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
                    <?php } ?>
                </div>
                <div class="col-md-1" style="padding-top: 10px;">
                    <div class="icon_admin_bell" style="float: right; ">
                        <a href="#" style="color: black"><i class=" fas fa-bell"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" style="color: black"><i class="fas fa-list"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>