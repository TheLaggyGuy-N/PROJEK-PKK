<?php 
include 'database.php';
$db = new database();

$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = $id_produk > 0 ? $db->koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'") : null;
$data = $query ? $query->fetch_assoc() : null;

if (!$data) {
    echo "<script>alert('Produk tidak ditemukan'); window.location='beranda.php';</script>";
    exit();
}
?>
<title>Borcelle | Edit</title>
<link rel="icon" href="gambar/icon.jpeg">
<link rel="stylesheet" href="../css/edit.css">
<div class="container">
    <h1>Edit produk</h1>
    <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_produk" value="<?php echo $data['id_produk']; ?>">

        <div class="forms">
            <label>Produk</label>
            <input type="text" name="produk" value="<?php echo $data['nama_produk']; ?>" required>
        </div>

        <div class="forms">
            <label>Harga</label>
            <input type="number" name="harga" value="<?php echo $data['harga']; ?>" required>
        </div>

        <div class="forms"> 
            <label>Warna</label>
            <input type="text" name="warna" value="<?php echo $data['warna']; ?>" required>
        </div>

        <div class="forms">
            <label>Ukuran</label>
            <input type="text" name="ukuran" value="<?php echo $data['ukuran']; ?>" required>
        </div>

        <div class="forms">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" value="<?php echo $data['deskripsi']; ?>" required>
        </div>

        <div class="forms">
            <label>Stok</label>
            <input type="text" name="stok" value="<?php echo $data['stok']; ?>" required>
        </div>

        <div class="forms">
            <label>Tambah Foto (opsional)</label>
            <input type="file" name="gambar" accept="gambar/"><br>  
            <small>Foto saat ini: <?php echo $data['foto']; ?></small><br>
            <img src="gambar/<?php echo $data['foto']; ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
            
        </div>

        <div class="tombol">
            <button class="tmbl_tambah" type="submit" name="edit">Simpan</button>
            <a href="beranda.php" class="tmbl_kembali">kembali</a>
        </div>
    </form>
</div>
