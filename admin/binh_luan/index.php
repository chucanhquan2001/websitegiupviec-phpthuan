<?php
session_start();
require_once('../../dao/pdo.php');

if (!isset($_GET['product'])) {
    $product = 1;
} else {
    $product = $_GET['product'];
    if (!is_numeric($product) || $product <= 0) {
        header("Location:" . BASE_URL);
    }
}
$data = 6;
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
    <title>Document</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <style>
        .vertical-menu {
            margin-top: 40px;
            width: 205px;
            border-radius: 10px;
            box-shadow: 0 0 10px 0 #d7bfbf;


        }

        .vertical-menu a {
            background-color: #fdf7f7;
            color: black;
            display: block;
            padding: 12px;
            text-decoration: none;
        }

        .vertical-menu a:hover {
            background-color: #ccc;
        }

        .vertical-menu a.active {
            border-radius: 10px 10px 0 0;
            background-color: #c0b6b6;
            color: white;
        }
    </style>
</head>

<body>
    <?php include('../layout/header.php'); ?>
    <div class="container-fluid" style="margin-bottom:80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php include('../layout/menu.php'); ?>
                </div>
                <div class="col-md-9" style="margin-top: 40px;">
                    <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;">
                        <div class="tieu_de" style="padding: 20px 0 0 10px;">
                            <h5>BÌNH LUẬN BÀI VIẾT</h5>
                            <?php if (isset($_GET['msg'])) { ?>
                                <span style="color:red;">
                                    <?php echo $_GET['msg'] ?></span>
                            <?php } ?>
                        </div>
                        <br>
                        <table class="table" style="font-size: 14px">
                            <thead>
                                <tr>
                                    <th>Stt</th>
                                    <th>Bài viết có bình luận</th>
                                    <th>Số bình luận</th>
                                    <th>Gần nhất</th>
                                    <th>Cũ nhất</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stt = 0;
                                foreach (selectAll("SELECT DISTINCT id_tin_tuc FROM binh_luan ORDER BY id ASC LIMIT $tin,$data") as $item) {
                                    $id_sp = $item['id_tin_tuc'];
                                    foreach (selectAll("SELECT * FROM tin_tuc WHERE id= '$id_sp'") as $value) {
                                        $tong = dem("SELECT COUNT(*) FROM binh_luan WHERE id_tin_tuc = '$id_sp'");
                                        foreach (selectAll("SELECT * FROM binh_luan WHERE id_tin_tuc='$id_sp' ORDER BY id DESC LIMIT 1 ") as $row) {
                                            foreach (selectAll("SELECT * FROM binh_luan WHERE id_tin_tuc='$id_sp' ORDER BY id ASC LIMIT 1 ") as $next) {
                                ?>
                                                <tr>
                                                    <td><?= $stt += 1 ?></td>
                                                    <td><?= $value['tieu_de'] ?></td>
                                                    <td><?= $tong ?></td>
                                                    <td><?= $row['ngay_bl'] ?></td>
                                                    <td><?= $next['ngay_bl'] ?></td>
                                                    <td>
                                                        <a href="chi_tiet.php?id=<?= $id_sp ?>" class="btn btn-danger">Chi tiết</a>
                                                    </td>
                                                </tr>
                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        <?php
                                        for ($t = 1; $t <= $page; $t++) { ?>
                                            &nbsp;<a name="" id="" class="btn btn-secondary" href="index.php?product=<?= $t ?>" role="button"> <?= $t ?></a>
                                        <?php
                                        }
                                        ?>
                                        . . .

                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include('../layout/footer.php'); ?>

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