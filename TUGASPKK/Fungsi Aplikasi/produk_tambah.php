<title>Borcelle | Tambah</title>
<link rel="icon" href="gambar/icon.jpeg">
<link rel="stylesheet" href="../css/tambah.css">
<div class="container">
    <h1>Tambah produk</h1>
    <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
        <div class="forms">
            <label>Produk</label>
            <input type="text" name="produk" required>
        </div>
        <div class="forms">
            <label>Harga</label>
            <input type="number" name="harga" required>
        </div>
        <div class="forms">
            <label>Warna</label>
            <input type="text" name="warna" required>
        </div>
        <div class="forms">
            <label>Ukuran</label>
            <input type="text" name="ukuran" required>
        </div>
        <div class="forms">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" required>
        </div>
        <div class="forms">
            <label>Stok</label>
            <input type="text" name="stok" required>
        </div>
        <div class="forms">
            <label>Tambah Foto</label>
            <input type="file" name="gambar" required>
        </div>
        <div class="tombol">
            <button class="tmbl_tambah" type="submit" name="tambah">Tambah</button>
            <a href="beranda.php" class="tmbl_kembali">kembali</a>
        </div>
    </form>
</div>