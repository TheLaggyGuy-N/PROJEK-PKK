<?php
session_start();
include 'database.php';
$db = new database();

$username = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$id_akun = $db->ambil_id_akun($username);

if ($id_akun == 0) {
    header("location: login.php");
    exit();
}

if (isset($_POST['aksi']) && ($_POST['aksi'] == 'tambah' || $_POST['aksi'] == 'beli')) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    $db->tambah_ke_keranjang($id_produk, $jumlah, $id_akun);

    if ($_POST['aksi'] == 'beli') {
        header("location: keranjang.php");
    } else {
        header("location: beranda.php");
    }
    exit();
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_produk = $_GET['id_produk'];

    $db->hapus_item_keranjang($id_produk, $id_akun);
    header("location: keranjang.php");
    exit();
}
?>