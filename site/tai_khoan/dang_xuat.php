<?php
require_once('../../dao/pdo.php');
session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    header("Location:" . BASE_URL . "index.php?msg=Đăng xuất thành công !");
} elseif (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
    header("Location:" . BASE_URL . "index.php?msg=Đăng xuất thành công !");
}
