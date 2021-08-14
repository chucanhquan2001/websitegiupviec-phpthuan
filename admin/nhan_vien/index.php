<?php
session_start();
require_once('../../dao/pdo.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    insert_delete_update("DELETE FROM nhan_vien WHERE id = '$id'");
    $error = "Xóa tài khoản đã chọn thành công !";
}
// phân trang
if (!isset($_GET['product'])) {
    $product = 1;
} else {
    $product = $_GET['product'];
    if (!is_numeric($product) || $product <= 0) {
        header("Location:" . BASE_URL);
    }
}
$data = 6;
$sql = "SELECT count(*) FROM `nhan_vien`";
$conn = connect();
$result = $conn->prepare($sql);
$result->execute();
$number = $result->fetchColumn();
$page = ceil($number / $data);
$tin = ($product - 1) * $data;

$giatri = selectAll("SELECT
  nv.* , us.ho_ten as ho_ten , dv.ten_dich_vu as ten_dich_vu , us.gioi_tinh as gioi_tinh , us.ngay_sinh as nam_sinh
FROM nhan_vien nv
  JOIN dich_vu dv ON nv.id_dichvu = dv.id
 JOIN users us ON nv.id_user = us.id LIMIT $tin,$data
");


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
            /* height: 150px;
            overflow-y: auto; */
            /* Set a width if you like */
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
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php include('../layout/menu.php'); ?>
                </div>
                <div class="col-md-9" style="margin-top: 40px;">
                    <div class="content_admin" style="background-color: #fdf7f7;border-radius: 10px;">
                        <div class="tieu_de" style="padding: 20px 0 0 10px;">
                            <h5>THÔNG TIN NGƯỜI TÌM VIỆC</h5>
                        </div>
                        <?php
                        if (isset($_GET['msg'])) {
                        ?>
                            <p style="color: red;margin-left: 10px;"><?= $_GET['msg']; ?></p>
                        <?php } ?>
                        <?php
                        if (isset($error)) {
                        ?>
                            <p style="color: red;margin-left: 10px;"><?= $error ?></p>
                        <?php } ?>
                        <br>
                        <table class="table" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th scope="col">Stt</th>
                                    <th scope="col">Họ tên</th>
                                    <th scope="col">Địa chỉ</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Kinh Nghiệm</th>
                                    <th scope="col">Tình trạng hôn nhân</th>
                                    <th scope="col">Dịch vụ</th>
                                    <th scope="col">Hình thức làm việc</th>
                                    <th scope="col">Mức Lương</th>
                                    <th scope="col">Nguyện Vọng</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stt = 0;
                                foreach ($giatri as $item) {
                                ?>
                                    <tr>
                                        <th><?= $stt += 1 ?></th>
                                        <td><?= $item['ho_ten'] ?></td>
                                        <td><?= $item['dia_chi'] ?></td>
                                        <td><?= $item['phone'] ?></td>
                                        <td><?= $item['kinh_nghiem'] ?></td>
                                        <td><?= ($item['status'] == 0) ? 'Đã kết hôn' : 'Chưa kết hôn' ?></td>


                                        <td><?= $item['ten_dich_vu'] ?></td>
                                        <td><?= $item['hinh_thuc_lam_viec'] ?></td>
                                        <td><?= money($item['muc_luong']) ?> vnđ</td>
                                        <td><?= $item['nguyen_vong'] ?></td>
                                        <td><?= ($item['trang_thai'] == 0) ? 'Chưa có người thuê' : 'Đã có người thuê' ?></td>
                                        <td>
                                            <a href="edit_nhan_vien.php?id=<?= $item['id'] ?>" class="btn btn-secondary" style="font-size: 12px"><i class="fas fa-edit"></i></a>
                                            <br>
                                            <br>
                                            <a href="index.php?id=<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn muốn xóa thật chứ?')" style="font-size: 12px"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
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
                        <a href="add_nhan_vien.php" class="btn btn-secondary">Thêm người tìm việc</a>
                        <br>
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