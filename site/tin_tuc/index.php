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
$data = 10;
$sql = "SELECT count(*) FROM `tin_tuc`";
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
    <title>Tin tức</title>
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
                <h3 class="header_title">Tin Tức</h3>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-9">

                    <?php foreach (selectAll("SELECT * FROM tin_tuc ORDER BY id DESC LIMIT $tin,$data") as $item) { ?>
                        <?php foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $value) {
                            $tong = dem("SELECT COUNT(*) FROM binh_luan WHERE id_tin_tuc = " . $item['id']); ?>
                            <div class="row" style="color: #626262;">
                                <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>" style="color: #626262;">
                                    <p style="font-size: 18px;margin-left: 15px;"><?= $item["tieu_de"] ?></p>
                                </a>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>"><img src="<?= BASE_URL ?>content/images_tin_tuc/<?= $item["hinh_anh"] ?>" alt="" width="100%" height="200px" style="border: 1px solid #9e7171;padding: 3px;"></a>
                                </div>
                                <div class="col-md-9">
                                    <p style="font-size: 12px;color: gray;"><i class="fas fa-stopwatch"></i> &nbsp;<?= $item["thoi_gian"] ?></p>
                                    <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>" style="color: #626262;">
                                        <p><?= $item["tom_tat"] ?>...</p>
                                    </a>
                                    <p style="font-size: 12px;color: gray;"> <i class="fas fa-eye"></i> &nbsp;<?= $item["luot_xem"] ?> &nbsp; &nbsp;<i class="fas fa-comment"></i> <?= $tong ?> &nbsp; &nbsp; <i class="fas fa-user"></i> &nbsp;<?= $value["ho_ten"] ?> </p>
                                </div>
                            </div>
                            <br><br>
                            <hr>
                    <?php }
                    } ?>
                    <br>
                    <?php
                    for ($t = 1; $t <= $page; $t++) { ?>
                        &nbsp;<a name="" id="" class="btn btn-secondary" href="index.php?product=<?= $t ?>" role="button"> <?= $t ?></a>
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