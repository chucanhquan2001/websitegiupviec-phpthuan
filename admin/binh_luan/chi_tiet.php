<?php
session_start();
require_once('../../dao/pdo.php');
$details = null;
if (isset($_GET['id'])) {
    $details = $_GET['id'];
}
if (isset($_GET['iddelete'])) {
    $iddelete = $_GET['iddelete'];
    insert_delete_update("DELETE FROM binh_luan WHERE id = '$iddelete'");
    header("Location:index.php?msg=Xóa bình luận thành công !");
}
$stt = 0;
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
                            <h5>CHI TIẾT BÌNH LUẬN</h5>
                            <?php if (isset($_GET['msg'])) { ?>
                                <span style="color:red;">
                                    <?php echo $_GET['msg'] ?></span>
                            <?php } ?>
                        </div>
                        <br>
                        <form method='post' action=''>
                            <table class="table table-hover">
                                <thead>
                                    <tr>

                                        <th>Stt</th>
                                        <th>Nội dung bình luận</th>
                                        <th>Ngày bình luận</th>
                                        <th>Người bình luận</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach (selectAll("SELECT * FROM binh_luan WHERE id_tin_tuc = '$details'") as $row) {
                                        $e = $row['id_user'];
                                        foreach (selectAll("SELECT * FROM users WHERE id = '$e'") as $item) {
                                    ?>
                                            <tr>
                                                <td><?= $stt += 1 ?></td>
                                                <td><?= $row['noi_dung'] ?></td>
                                                <td><?= $row['ngay_bl'] ?></td>
                                                <td><?= $item['ho_ten'] ?></td>
                                                <td>
                                                    <a href="chi_tiet.php?iddelete=<?= $row['id'] ?>&id=<?= $details ?>" class="btn btn-dark" onclick="return confirm('Bạn muốn xóa bình luận này?')">Xóa</a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8">
                                            <a href="index.php" class="btn btn-danger">Quay lại</a>
                                            <br>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
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