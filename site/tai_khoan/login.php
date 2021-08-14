<?php
session_start();
require_once('../../dao/pdo.php');
if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    header("Location:" . BASE_URL . "index.php?msg=Đăng nhập thành công !");
}

if (isset($_POST['login'])) {
    $userErr = $passErr = "";
    if (empty($_POST['user'])) {
        $userErr = "Vui lòng nhập tên đăng nhập !";
    } else {
        $user = $_POST['user'];
    }
    if (empty($_POST['pass'])) {
        $passErr = "Vui lòng nhập mật khẩu !";
    } else {
        $password = md5($_POST['pass']);
    }
    if ($userErr . $passErr == "") {
        $check = "SELECT * FROM users WHERE ten_dang_nhap = '$user' AND mat_khau = '$password' AND trang_thai = :trang_thai AND vai_tro=:vai_tro";
        $conn = connect();
        $count = $conn->prepare($check);
        $count->execute(array(
            'vai_tro' => 0,
            'trang_thai' => 0
        ));
        $frofile = $count->fetch();

        $check_admin = "SELECT * FROM users WHERE ten_dang_nhap = '$user' AND mat_khau = '$password' AND vai_tro= :vai_tro AND trang_thai = :trang_thai ";
        $conn = connect();
        $cout_admin = $conn->prepare($check_admin);
        $cout_admin->execute(array(
            ':vai_tro' => 1,
            ':trang_thai' => 0
        ));
        $frofile_admin = $cout_admin->fetch();

        if ($cout_admin->rowCount() > 0) {
            $_SESSION['admin'] =
                [
                    'ten_dang_nhap' => $frofile_admin['ten_dang_nhap'],
                    'id' => $frofile_admin['id'],
                    'ho_ten' => $frofile_admin['ho_ten'],
                    'email' => $frofile_admin['email'],
                    'hinh_anh' => $frofile_admin['hinh_anh']
                ];
            header("Location:" . BASE_URL . "admin/index.php?msg=Đăng nhập quản trị thành công !");
        } elseif ($count->rowCount() > 0) {
            $_SESSION['user'] =
                [
                    'ten_dang_nhap' => $frofile['ten_dang_nhap'],
                    'id' => $frofile['id'],
                    'ho_ten' => $frofile['ho_ten'],
                    'email' => $frofile['email'],
                    'hinh_anh' => $frofile['hinh_anh']
                ];
            header("location:" . BASE_URL . "index.php?msg=Đăng nhập thành công !");
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu chưa đúng hoặc tài khoản của bạn đã bị khóa!";
        }
    }
}


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
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: url(../../content/images/login.png) no-repeat;
            background-size: cover;
        }

        .login-box {
            width: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
        }

        .login-box h1 {
            float: left;
            font-size: 40px;
            border-bottom: 6px solid #4ff74f;
            margin-bottom: 50px;
            padding: 5px 0;
        }

        .textbox {
            width: 100%;
            overflow: hidden;
            font-size: 20px;
            padding: 8px 0;
            margin: 8px 0;
            border-bottom: 1px solid #4ff74f;
        }

        .textbox i {
            width: 26px;
            float: left;
            text-align: center;
        }

        .textbox input {
            border: none;
            outline: none;
            background: none;
            color: white;
            font-size: 18px;
            width: 80%;
            float: left;
            margin: 0 10px;
        }

        .btn {
            width: 100%;
            background: none;
            border: 2px solid #4ff74f;
            color: white;
            padding: 5px;
            font-size: 18px;
            cursor: pointer;
            margin: 12px 0;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h1>Login</h1><br><br><br>
        <?php if (isset($error)) :  ?>
            <p style="color : red; position: absolute; top: 17%"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['msg'])) :  ?>
            <p style="color : red; position: absolute; top: 17%"><?= $_GET['msg'] ?></p>
        <?php endif; ?>
        <form action="" id="login" method="post">
            <div class="textbox">
                <i class="fas fa-user"></i>
                <input type="text" name="user" id="" placeholder="Username"><br>

            </div>
            <?php
            if (isset($userErr)) {
                echo "<p style='color: red;'>" . $userErr . "</p>";
            }
            ?>
            <div class="textbox">
                <i class="fas fa-lock"></i>
                <input type="password" name="pass" id="" placeholder="Password"><br>

            </div>
            <?php
            if (isset($passErr)) {
                echo "<p style='color: red;'>" . $passErr . "</p>";
            }
            ?>
            <br>
            <input type="checkbox" name="remember" id="" style="width:20px;height:15px"> Ghi nhớ tài khoản
            <button type="submit" name="login" class="btn btn-danger">Đăng nhập</button>
            <a class="forgot" href="<?= BASE_URL ?>site/tai_khoan/quen_mat_khau.php">Quên mật khẩu ?</a>
            <p>Nếu bạn chưa có tài khoản. Vui lòng đăng ký <a href="dang_ky.php">Tại đây?</a></p>
        </form>
    </div>

</body>

</html>