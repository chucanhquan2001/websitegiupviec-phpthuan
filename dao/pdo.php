<?php

define("BASE_URL", "http://localhost/Website_giup_viec/");
function connect()
{
    $host = "127.0.0.1";
    $dbname = "duan1";
    $db_username = "root";
    $db_password = "";
    return new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
}

// lấy dữ liệu tất
function selectAll($sql)
{
    $conne = connect();
    $stml = $conne->prepare($sql);
    $stml->execute();
    return $select = $stml->fetchAll();
}

//lấy 1 dữ liệu
function select_once($sql)
{
    $conne = connect();
    $stml = $conne->prepare($sql);
    $stml->execute();
    return $select = $stml->fetch();
}

// thêm dữ liệu , xóa , cập nhập
function insert_delete_update($sql)
{
    $conn = connect();
    $stml = $conn->prepare($sql);
    $stml->execute();
}

function dem($sql)
{
    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
function money($money)
{
    return number_format($money, 0, '.', ',');
}
function day($time)
{
    return $time->format('d/m/Y');
}
function checkLogin()
{
    if (!isset($_SESSION['admin']) || !isset($_SESSION['user'])) {
        header('location:' . BASE_URL . "site/tai_khoan/login.php?msg=Xin mời bạn đăng nhập");
        die;
    }
  
}
