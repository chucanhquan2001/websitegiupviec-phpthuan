<?php
session_start();
require_once "../../dao/pdo.php";
if (isset($_SESSION['user']) || $_SESSION['admin']) {
    if (isset($_SESSION['user'])) {
        $id = $_SESSION['user']['id'];
    } else {
        $id = $_SESSION['admin']['id'];
    }
    $select = selectall("select * from users where id ='$id'");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <link rel="stylesheet" href="../../content/lien_he.css">
    <style>
        ul>li {
            list-style: none;

        }

        ul>li>p {
            text-align: right;
            color: rgba(85, 85, 85, .8);
            margin-bottom: 35px;

        }

        .chua_anh {
            width: 100%;
            border-left: 1px solid #efefef;
            padding-right: 10px;
            height: 250px;
            padding-left: 30px;
        }

        .title_anh {
            width: 90px;
            text-align: center;
            background: #fff;
            color: #555;
            border: 1px solid rgba(0, 0, 0, .09);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .03);
            margin: 0px auto;
            margin-top: 10px;
            margin-left: 55px;
        }
    </style>
</head>

<body>
    <?php include('../../layout/header.php'); ?>
    <div class="container-fluid">
        <div class="row " style="margin-top:20px;margin-bottom:20px;">
            <h3 class="header_title">Thông tin tài khoản</h3>

            <?php if (isset($_GET['msg'])) { ?>
                <span style="color:red;"><?php echo $_GET['msg'] ?></span>
            <?php } ?>
        </div>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <h3 style="width:100%;">Hồ Sơ Của Tôi</h3>
                            <p style="width:100%;">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

                            <?php foreach ($select as $item) { ?>
                                <div class="col-md-2.5">
                                    <ul>
                                        <li>
                                            <p class="chu">
                                                Họ tên
                                            </p>
                                        </li>
                                        <li>
                                            <p class="chu">
                                                Tên đăng nhập
                                            </p>
                                        </li>
                                        <li>
                                            <p class="chu">
                                                Email
                                            </p>
                                        </li>
                                        <li>
                                            <p class="chu">
                                                Ngày sinh
                                            </p>
                                        </li>
                                        <li>
                                            <p class="chu">
                                                Giới Tính
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li style="margin-bottom:20px;">
                                            <span>
                                                <div class="from-gruop">
                                                    <input type="text" value="<?= $item['ho_ten'] ?>" class='form-control' disabled>
                                                </div>

                                            </span>
                                        </li>
                                        <li style="margin-bottom:20px;">
                                            <div class="from-gruop">
                                                <input type="text" value="<?= $item['ten_dang_nhap'] ?>" class='form-control' disabled>
                                            </div>
                                        </li>
                                        <li style="margin-bottom:20px;">
                                            <div class="from-gruop">
                                                <input type="text" value="<?= $item['email'] ?>" class='form-control' disabled>
                                            </div>
                                        </li>
                                        <li style="margin-bottom:20px;">
                                            <div class="from-gruop">
                                                <input type="date" value="<?= $item['ngay_sinh'] ?>" class='form-control' disabled>
                                            </div>
                                        </li>

                                        <li style="margin-bottom:20px;">
                                            <div class="from-gruop">
                                                <input type="text" value="<?= $item['gioi_tinh'] == 0 ? 'Nam' : 'Nữ' ?> " class='form-control' disabled>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3.5">
                                    <div class="chua_anh">
                                        <img src="<?= BASE_URL . 'content/images_user/' . $item['hinh_anh'] ?>" style="width:120px;height:120px;border-radius:50%;margin-left:40px;" alt="Ảnh tài khoản">
                                        <p class="title_anh">Ảnh</p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <hr>
                        <div class="row">
                            <a href="<?= BASE_URL . "site/tai_khoan/doi_thong_tin.php?id=$id" ?>" class="btn btn-success">Đổi thông tin</a>&nbsp;
                            <a href="<?= BASE_URL ?>site/tai_khoan/doi_mat_khau.php?id=<?= $id ?>" class="btn btn-success">Đổi Mật khẩu</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php include_once "../../layout/content_right.php" ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include_once('../../layout/footer.php') ?>
</body>
<?php include('../../link/js.php') ?>
<script src="../../content/js.js"></script>
<script src="../../content/jquery.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var $filter = $('.head_nav');
        var $filterSpacer = $('<div />', {
            "class": "vnkings-spacer",
            "height": $filter.outerHeight()
        });
        if ($filter.size()) {
            $(window).scroll(function() {
                if (!$filter.hasClass('fix') && $(window).scrollTop() > $filter.offset().top) {
                    $filter.before($filterSpacer);
                    $filter.addClass("fix");
                } else if ($filter.hasClass('fix') && $(window).scrollTop() < $filterSpacer.offset().top) {
                    $filter.removeClass("fix");
                    $filterSpacer.remove();
                }
            });
        }

    });
</script>

</html>