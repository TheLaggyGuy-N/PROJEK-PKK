<?php
session_start();

$is_login = isset($_SESSION['status']) && $_SESSION['status'] == "login";

if (!$is_login) {
    header('location: login.php');
    exit();
}

include 'database.php';
$db = new database();

$username = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$id_akun = $db->ambil_id_akun($username);

// Ambil data keranjang pengguna
$data_keranjang = $db->tampil_keranjang($id_akun);

$total = 0;
if (!empty($data_keranjang)) {
    foreach ($data_keranjang as $item) {
        $total += (int)$item['subtotal'];
    }
}

$nama_user = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Borcelle Store</title>
    <link rel="icon" href="gambar/icon.jpeg">
    <link rel="stylesheet" href="beranda.css">
</head>
<body>

<header>
    <div class="brand-section">
        <h1 class="brand-name">BORCELLE</h1>
    </div>
    
    <div class="nav-action-group">
        <a href="beranda.php" class="btn-auth btn-masuk" style="text-decoration: none;">&larr; Kembali</a>

        <div class="auth-wrapper">
            <div class="user-profile-card">
                <div class="profile-avatar">
                    <img src="gambar/avatar-default.svg" alt="Avatar">
                </div>
                <div class="profile-info">
                    <span class="user-greeting">Halo, <?php echo $nama_user; ?></span>
                    <div class="profile-dropdown">
                        <a href="profil.php">Profil & Pesanan</a>
                        <a href="beranda.php?aksi=logout" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="content">
    <h2 class="section-title"> Keranjang Belanja Anda 🛒 </h2>
    
    <div class="cart-list-container">
        <?php
        if (!empty($data_keranjang)) {
            foreach ($data_keranjang as $item) {
        ?>
            <div class="cart-item-card">
                <div class="cart-item-info">
                    <img src="gambar/<?php echo $item['foto']; ?>">
                    <div class="cart-item-details">
                        <h3><?php echo $item['nama_produk']; ?></h3>
                        <p class="price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                        <p class="cart-item-meta">Warna: <?php echo $item['warna']; ?> | Ukuran: <?php echo $item['ukuran']; ?></p>
                    </div>
                </div>
                
                <div class="cart-item-pricing">
                    <div class="cart-qty-text">Jumlah: <?php echo $item['jumlah']; ?> pcs</div>
                    <div class="cart-subtotal-text">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></div>
                    <a class="btn-delete-cart" href="proses_keranjang.php?aksi=hapus&id_produk=<?php echo $item['id_produk']; ?>" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</a>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<div style='text-align: center; padding: 50px 0; width: 100%;'>";
            echo "<p style='font-size: 18px; color: #888;'>Keranjang belanja kamu masih kosong nih.</p>";
            echo "<a href='beranda.php' class='btn btn-cart' style='display: inline-block; margin-top: 15px; text-decoration: none; width: auto; padding: 10px 30px;'>Yuk Belanja Sekarang!</a>";
            echo "</div>";
        }
        ?>
    </div>

    <?php if (!empty($data_keranjang)): ?>
        <div class="admin-panel" style="margin-top: 40px; text-align: right;">
            <h3 style="color: #5c4661; margin-bottom: 5px;">Total yang Harus Dibayar:</h3>
            <h2 style="color: #bc7cb4; font-size: 28px; margin-bottom: 20px;"> Rp <?php echo number_format($total, 0, ',', '.'); ?> </h2>
            
            <a href="beranda.php" class="btn btn-detail" style="text-decoration: none; margin-right: 10px; background: #fff; color: #bc7cb4; border: 2px solid #bc7cb4;">+ Tambah Produk</a>
            <a href="beli.php" class="btn btn-cart" style="text-decoration: none;">Lanjut ke Pembayaran</a>
        </div>
    <?php endif; ?>
</main>

</body>
</html>