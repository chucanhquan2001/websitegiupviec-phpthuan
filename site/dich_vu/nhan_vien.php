<?php
session_start();
require_once "../../dao/pdo.php";

if (!isset($_GET['product'])) {
    $product = 1;
} else {
    $product = $_GET['product'];
    if (!is_numeric($product) || $product <= 0) {
        header("Location:" . BASE_URL);
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM dich_vu WHERE id = '$id'";
    $connect = connect();
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $dich_vu = $stmt->fetch();
}
$data = 10;
$sql = "SELECT count(*) FROM `nhan_vien` WHERE id_dichvu = '$id'";
$conn = connect();
$result = $conn->prepare($sql);
$result->execute();
$number = $result->fetchColumn();
$page = ceil($number / $data);
$tin = ($product - 1) * $data;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <style>
        .header_title {
            background-color: #64cc7b;
            color: white;
            text-align: center;
            width: 100%;
            height: 80px;
            line-height: 80px;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
</head>

<body>
    <?php include('../../layout/header.php') ?>
    <div class="container-fluid" style="background-color: #64cc7b;">
        <div class="container">
            <div class="row" style="margin-top:20px;margin-bottom:20px;">
                <h3 class="header_title"><?= $dich_vu['ten_dich_vu'] ?></h3>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <b><?= $dich_vu['ten_dich_vu'] ?></b>
                            <p style="color: #626262;font-size: 16px;"><?= $dich_vu['mo_ta'] ?></p>
                        </div>
                    </div>
                    <br><br>
                    <b>Nhân viên của chúng tôi</b>
                    <br><br>
                    <div class="row">

                        <?php foreach (selectAll("SELECT * FROM nhan_vien WHERE id_dichvu = '$id' AND trang_thai = 0 ORDER BY id DESC LIMIT $tin, $data") as $item) { ?>
                            <?php foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $key) { ?>
                                <?php foreach (selectAll("SELECT * FROM dich_vu WHERE id = " . $item['id_dichvu']) as $next) { ?>
                                    <div class="col-md-4">
                                        <div class="card" style="border: 1px solid #9e7171;padding: 3px;">
                                            <a href="<?= BASE_URL ?>site/nhan_vien/chi_tiet_nhan_vien.php?id=<?= $item['id'] ?>&id_dv=<?= $item['id_dichvu'] ?>"><img class="card-img-top" src="<?= BASE_URL ?>content/images_user/<?= $key['hinh_anh'] ?>" alt="Card image cap" height="250px"></a>
                                            <div class="card-body">
                                                <p class="card-text" style="font-size: 12px;">Họ tên : <?= $key['ho_ten'] ?></p>
                                                <p class="card-text" style="font-size: 12px;">Quê quán : <?= $item['dia_chi'] ?></p>
                                                <p class="card-text" style="font-size: 12px;">Dịch vụ : <?= $next['ten_dich_vu'] ?></p>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                        <?php }
                            }
                        } ?>
                        <br>
                    </div>
                    <?php
                    for ($t = 1; $t <= $page; $t++) { ?>
                        &nbsp;<a name="" id="" class="btn btn-secondary" href="nhan_vien.php?product=<?= $t ?>&id=<?= $id ?>" role="button"> <?= $t ?></a>
                    <?php
                    }
                    ?>
                    . . .
                </div>
                <div class="col-md-3">
                    <?php include_once('../../layout/content_right.php') ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../layout/footer.php') ?>
    <a id="button"></a>
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
</body>

</html>