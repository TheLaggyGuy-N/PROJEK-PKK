<?php
session_start();
include 'database.php';
$db = new database();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gmail    = $_POST['gmail'];

    if (empty($username) || empty($password) || empty($gmail)) {
        echo "<script>alert('Isi dengan benar!'); window.history.back();</script>";
        exit;
    }

    $query = mysqli_query($db->koneksi, "SELECT * FROM akun WHERE username='$username' AND password='$password' AND gmail='$gmail'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        
        $_SESSION['user']   = $data['username'];
        $_SESSION['nama']   = $data['nama'];
        $_SESSION['role']   = $data['role']; 
        $_SESSION['status'] = "login";

        echo "<script>alert('Login Berhasil!'); window.location='beranda.php';</script>";
    } else {
        echo "<script>alert('Akun tidak ditemukan! Periksa kembali data Anda.'); window.history.back();</script>";
    }

} elseif (isset($_POST['daftar'])) {
    $nama     = $_POST['nama'];
    $gmail    = $_POST['gmail'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $notlp    = $_POST['notlp'];
    $alamat   = $_POST['alamat'];
    $role     = "user"; 

    if (empty($nama) || empty($username) || empty($password) || empty($gmail)) {
        echo "<script>alert('Nama, Username, Gmail, dan Password wajib diisi!'); window.history.back();</script>";
        exit;
    }

    $cek_user = mysqli_query($db->koneksi, "SELECT * FROM akun WHERE username='$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah terpakai!'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO akun (nama, gmail, username, password, no_tlp, alamat, role) 
                VALUES ('$nama', '$gmail', '$username', '$password', '$notlp', '$alamat', '$role')";
        
        if (mysqli_query($db->koneksi, $sql)) {
            echo "<script>alert('Berhasil Daftar! Silakan Login.'); window.location='login.php';</script>";
        } else {
            die("Error Database: " . mysqli_error($db->koneksi));
        }
    }
}
?>