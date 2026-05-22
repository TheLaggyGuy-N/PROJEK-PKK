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

if (isset($_POST['aksi']) && $_POST['aksi'] == 'bayar') {
    
    $alamat = $_POST['alamat']; 
    
    $proses_checkout = $db->checkout_pesanan($id_akun, $alamat);

    if ($proses_checkout) {
        echo "<script>
                alert('Terima kasih! Pembayaran sukses dikonfirmasi. Pesanan Borcelle kamu sedang diproses 🌸');window.location.href = 'beranda.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memproses pembelian, coba lagi.');window.location.href = 'keranjang.php';
              </script>";
    }
    exit();
} else {
    header("location: beranda.php");
    exit();
}
?>