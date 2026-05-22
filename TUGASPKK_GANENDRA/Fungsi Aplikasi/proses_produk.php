<?php
session_start();
include "database.php";
$db = new database();

if (isset($_POST['tambah'])) {
    $produk   = $_POST['produk'];
    $harga    = $_POST['harga'];
    $warna    = $_POST['warna'];
    $ukuran   = $_POST['ukuran'];
    $detail   = $_POST['deskripsi'];
    $stok     = $_POST['stok'];

    $nama_file     = $_FILES['gambar']['name'];
    $tmpt_file     = $_FILES['gambar']['tmp_name'];
    $folder_tujuan = "gambar/" . $nama_file;

    if (move_uploaded_file($tmpt_file, $folder_tujuan)) {
        $sql = "INSERT INTO produk (nama_produk, harga, warna, ukuran, deskripsi, stok, foto) 
                VALUES ('$produk', '$harga', '$warna', '$ukuran', '$detail', '$stok', '$nama_file')";
        
        if ($db->koneksi->query($sql)) {
            echo "<script>alert('Produk berhasil ditambah!'); window.location='beranda.php';</script>";
        } else {
            echo "Gagal ke database: " . $db->koneksi->error;
        }
    } else {
        echo "<script>alert('Gagal upload gambar!'); window.history.back();</script>";
    }

} elseif (isset($_POST['edit'])) {
    $id_produk = $_POST['id_produk'];
    $produk    = $_POST['produk'];
    $harga     = $_POST['harga'];
    $warna     = $_POST['warna'];
    $ukuran    = $_POST['ukuran'];
    $detail    = $_POST['deskripsi'];
    $stok      = $_POST['stok'];

    if ($_FILES['gambar']['name'] != "") {
        $nama_file     = $_FILES['gambar']['name'];
        $tmpt_file     = $_FILES['gambar']['tmp_name'];
        $folder_tujuan = "gambar/" . $nama_file;
        move_uploaded_file($tmpt_file, $folder_tujuan);

        $sql = "UPDATE produk SET nama_produk='$produk', harga='$harga', warna='$warna', ukuran='$ukuran', deskripsi='$detail', stok='$stok', foto='$nama_file' WHERE id_produk='$id_produk'";
    } else {

        $sql = "UPDATE produk SET nama_produk='$produk', harga='$harga', warna='$warna', ukuran='$ukuran', deskripsi='$detail', stok='$stok' WHERE id_produk='$id_produk'";
    }

    if ($db->koneksi->query($sql)) {
        echo "<script>alert('Produk berhasil diupdate!'); window.location='beranda.php';</script>";
    } else {
        echo "Gagal update: " . $db->koneksi->error;
    }

} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_produk = $_GET['id'];

    $cari = $db->koneksi->query("SELECT foto FROM produk WHERE id_produk='$id_produk'");
    $data = $cari->fetch_assoc();
    if (file_exists("gambar/" . $data['foto'])) {
        unlink("gambar/" . $data['foto']);
    }

    $sql = "DELETE FROM produk WHERE id_produk='$id_produk'";
    if ($db->koneksi->query($sql)) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location='beranda.php';</script>";
    } else {
        echo "Gagal hapus: " . $db->koneksi->error;
    }
}
?>