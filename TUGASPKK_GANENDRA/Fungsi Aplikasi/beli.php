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

$data_keranjang = $db->tampil_keranjang($id_akun);

if (empty($data_keranjang)) {
    header("location: beranda.php");
    exit();
}

$total = 0;
foreach ($data_keranjang as $item) {
    $total += (int)$item['subtotal'];
}

$nama_user = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Borcelle Store</title>
    <link rel="icon" href="gambar/icon.jpeg">
    <link rel="stylesheet" href="beranda.css">
</head>
<body>

<header>
    <div class="brand-section">
        <h1 class="brand-name">BORCELLE</h1>
    </div>
    <div class="nav-action-group">
        <a href="keranjang.php" class="btn-auth btn-masuk" style="text-decoration: none;">&larr; Ke Keranjang</a>
    </div>
</header>

<main class="content">
    <h2 class="section-title"> Konfirmasi Pembayaran 🌸 </h2>

    <div class="cart-list-container">
        <h3 style="color: #5c4661; margin-bottom: 10px;">Ringkasan Pesanan:</h3>
        <?php foreach ($data_keranjang as $item): ?>
            <div class="cart-item-card" style="pointer-events: none;"> <div class="cart-item-info">
                    <img src="gambar/<?php echo $item['foto']; ?>">
                    <div class="cart-item-details">
                        <h3><?php echo $item['nama_produk']; ?></h3>
                        <p class="cart-item-meta">Warna: <?php echo $item['warna']; ?> | Ukuran: <?php echo $item['ukuran']; ?></p>
                    </div>
                </div>
                <div class="cart-item-pricing">
                    <div class="cart-qty-text"><?php echo $item['jumlah']; ?> Pcs</div>
                    <div class="cart-subtotal-text">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="admin-panel" style="margin-top: 20px;">
        <h3 style="color: #5c4661; margin-bottom: 15px;">Informasi Pengiriman</h3>
        <p style="font-size: 14px; margin-bottom: 5px;"><strong>Nama Penerima:</strong> <?php echo $nama_user; ?></p>
        
        <form action="proses_beli.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="total_bayar" value="<?php echo $total; ?>">
            
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #4a3b4e; margin-bottom: 5px;">Alamat Lengkap Rumah:</label>
                <textarea name="alamat" required placeholder="Masukkan alamat lengkap pengiriman gelang cantiknya..." style="width: 100%; padding: 12px; border: 2px solid #f0d5f7; border-radius: 12px; font-family: inherit; resize: none; outline: none;" rows="3"></textarea>
            </div>

            <div style="border-top: 2px dashed #f0d5f7; padding-top: 15px; text-align: right;">
                <h4 style="color: #5c4661; margin-bottom: 5px;">Total Transaksi:</h4>
                <h2 style="color: #bc7cb4; font-size: 26px; margin-bottom: 20px;">Rp <?php echo number_format($total, 0, ',', '.'); ?></h2>
                
                <button type="submit" name="aksi" value="bayar" class="btn btn-cart" style="width: auto; padding: 12px 40px; display: inline-block;">Konfirmasi & Bayar Sekarang</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>