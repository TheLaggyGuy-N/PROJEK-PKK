<?php
session_start();

if (isset($_GET['aksi']) && $_GET['aksi'] == 'logout') {
    session_destroy();
    header("location: beranda.php");
    exit();
}

include 'database.php';
$db = new database();
$data_produk = $db->tampil_produk();

// Inisialisasi status login
$is_login = isset($_SESSION['status']) && $_SESSION['status'] == "login";
$role = $is_login ? $_SESSION['role'] : 'guest';
$nama_user = $is_login ? $_SESSION['nama'] : 'Tamu';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Borcelle Store - Beranda</title>
</head>
<body>

    <!-- Header & Navigasi Dinamis -->
    <header>
        <h1>Selamat Datang, <?php echo $nama_user; ?>!</h1>
        <nav>
            <a href="beranda.php">Home</a> |
            <?php if ($is_login): ?>
                <a href="keranjang.php">Keranjang Belanja</a> |
                <a href="beranda.php?aksi=logout" onclick="return confirm('Yakin ingin keluar?')">Logout (<?php echo $role; ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a> |
                <a href="daftar.php">Daftar</a>
            <?php endif; ?>
        </nav>
    </header>

    <hr>

    <!-- Fitur Khusus Admin -->
    <?php if ($role == 'admin'): ?>
        <section style="background: #f0fdf4; padding: 15px; border: 1px solid #bbf7d0; margin-bottom: 20px;">
            <h3>Panel Admin</h3>
            <p>Anda memiliki akses untuk mengelola stok barang.</p>
            <a href="produk_tambah.php" style="padding: 5px 10px; background: #22c55e; color: white; text-decoration: none; border-radius: 5px;">+ Tambah Produk Baru</a>
        </section>
    <?php endif; ?>

    <h2>Koleksi Produk Kami</h2>

    <div class="kontainer-produk" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php 
        if (!empty($data_produk)) {
            foreach ($data_produk as $produk) { 
        ?>
            <div class="kartu-produk" style="border: 1px solid #ddd; padding: 15px; width: 250px; border-radius: 10px;">
                <img src="gambar/<?php echo $produk['foto']; ?>" alt="Foto" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                
                <h3><?php echo $produk['nama_produk']; ?></h3>
                <p><strong>Harga:</strong> Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
                <p><strong>Stok:</strong> <?php echo $produk['stok']; ?></p>
                
                <hr>

                <!-- Tombol Aksi Sesuai Role -->
                <?php if ($role == 'admin'): ?>
                    <!-- Tampilan Admin -->
                    <a href="proses_edit.php?id=<?php echo $produk['id_produk']; ?>">Edit</a> | 
                    <a href="proses_produk.php?aksi=hapus&id=<?php echo $produk['id_produk']; ?>" style="color: red;" onclick="return confirm('Hapus produk ini?')">Hapus</a>

                <?php elseif ($role == 'user'): ?>
                    <!-- Tampilan Pembeli Terdaftar -->
                    <form action="proses_keranjang.php" method="POST">
                        <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" style="width: 50px;">
                        <button type="submit" name="tambah">Tambah ke Keranjang</button>
                    </form>

                <?php else: ?>
                    <!-- Tampilan Guest (Tamu) -->
                    <p style="font-size: 0.8rem; color: #666;">Silakan <a href="login.php">login</a> untuk membeli produk ini.</p>
                <?php endif; ?>

                <br>
                <button onclick="bukaModal('detail-<?php echo $produk['id_produk']; ?>')" style="background: none; border: none; color: blue; cursor: pointer; text-decoration: underline; padding: 0;">Lihat Detail</button>

                <div id="detail-<?php echo $produk['id_produk']; ?>" class="modal-detail" style="display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0,5);">
                    <div style="background-color: white; margin: 10% auto; padding: 20px; border-radius: 10px; width: 50%; max-width: 500px; position: relative;">
                        
                        <!-- Tombol Close (X) -->
                        <span onclick="tutupModal('detail-<?php echo $produk['id_produk']; ?>')" style="position: absolute; right: 20px; top: 15px; font-size: 24px; cursor: pointer;">&times;</span>
                        
                        <h2>Detail Produk</h2>
                        <img src="gambar/<?php echo $produk['foto']; ?>" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px;">
                        
                        <h3><?php echo $produk['nama_produk']; ?></h3>
                        <p><strong>Harga:</strong> Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
                        <p><strong>Stok Tersedia:</strong> <?php echo $produk['stok']; ?></p>
                        <p><strong>Deskripsi:</strong> <?php echo $produk['deskripsi']; ?></p> <!-- Pastikan nama kolom sesuai (deskripsi/detail) -->
                        
                        <hr>

                        <!-- Form Tambah ke Keranjang di dalam Detail (Hanya untuk Role User) -->
                        <?php if ($role == 'user'): ?>
                            <form action="proses_keranjang.php" method="POST" style="margin-top: 15px;">
                                <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                                <label>Jumlah Belanja: </label>
                                <input type="number" name="jumlah" value="1" min="1" max="<?php echo $produk['stok']; ?>" style="width: 60px; padding: 5px;">
                                <button type="submit" name="tambah" style="background: #22c55e; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer;">+ Tambah ke Keranjang</button>
                            </form>
                        <?php elseif ($role == 'guest'): ?>
                            <p style="font-size: 0.9rem; color: #666;">Tertarik membeli? Silakan <a href="login.php">login</a> terlebih dahulu.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
            } 
        } else {
            echo "<p>Belum ada produk yang tersedia.</p>";
        }
        ?>
    </div>

</body>
</html>