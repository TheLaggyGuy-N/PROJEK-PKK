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

// Status Login
$is_login = isset($_SESSION['status']) && $_SESSION['status'] == "login";
$role = $is_login ? $_SESSION['role'] : 'guest';
$nama_user = $is_login ? $_SESSION['nama'] : 'Tamu';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Borcelle Store</title>

    <link rel="icon" href="../gambar/icon.jpeg">
    <link rel="stylesheet" href="../css/beranda.css">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="brand-section">
        <h1 class="brand-name">BORCELLE</h1>
    </div>
    
    <div class="search-container">
        <input type="text" class="search-input" placeholder="Cari koleksi gelang cantik...">
    </div>

    <div class="nav-action-group">
        <a href="<?php echo $is_login ? 'keranjang.php' : 'login.php'; ?>" class="cart-link">
            <img src="../gambar/keranjang.png" alt="Keranjang" class="cart-icon">
        </a>

        <div class="auth-wrapper">
            <?php if($is_login): ?>
                <div class="user-profile-card">
                    <div class="profile-avatar">
                        <img src="../gambar/avatar.svg" alt="Avatar">
                    </div>
                    <div class="profile-info">
                        <span class="user-greeting">Halo, <?php echo $nama_user; ?></span>
                        <div class="profile-dropdown">
                            <a href="profil.php">Profil & Pesanan</a>
                            <a href="beranda.php?aksi=logout" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login.php" class="btn-auth btn-masuk">Masuk</a>
                    <a href="daftar.php" class="btn-auth btn-daftar">Daftar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- BANNER -->
<section class="banner">
    <img src="../gambar/banner.png" alt="Banner">
</section>

<!-- ADMIN -->
<?php if($role == 'admin'): ?>
    <div class="admin-panel">
        <h2>Panel Admin</h2>
        <p>Halo Admin <?php echo $nama_user; ?> 👋</p>
        <a href="produk_tambah.php">+ Tambah Produk</a>
    </div>
<?php endif; ?>

<!-- PRODUK -->
<main class="content">
    <h2 class="section-title"> Produk Terbaru 🌸 </h2>
    <div class="product-grid">
        <?php
        if(!empty($data_produk)){

            foreach($data_produk as $produk){
        ?>
    <div class="product-card">
        <img src="../gambar/<?php echo $produk['foto']; ?>">
        <h3> <?php echo $produk['nama_produk']; ?> </h3>
        <p class="price"> Rp <?php echo number_format($produk['harga'],0,',','.'); ?> </p>
        <p class="stock"> Stok: <?php echo $produk['stok']; ?> </p>

        <!-- ADMIN -->
        <?php if($role == 'admin'): ?>
            <a class="btn btn-cart" href="produk_edit.php?id=<?php echo $produk['id_produk']; ?>"> Edit </a>
            <a class="btn btn-danger" href="proses_produk.php?aksi=hapus&id=<?php echo $produk['id_produk']; ?>" onclick="return confirm('Hapus produk ini?')"> Hapus </a>

        <!-- USER -->
<?php elseif($role == 'user'): ?>
            <form action="proses_keranjang.php" method="POST">
                <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                <input type="hidden" name="jumlah" value="1">
                <input type="hidden" name="aksi" value="tambah">
                <button type="submit" class="btn btn-cart">Tambah ke Keranjang</button>
            </form>

        <!-- GUEST -->
        <?php else: ?>
            <p style="margin-top:10px;"> Login dulu untuk membeli </p>
        <?php endif; ?>

        <br>

        <button class="btn btn-detail" onclick="bukaModal('detail-<?php echo $produk['id_produk']; ?>')"> Lihat Detail </button>
    </div>

    <!-- MODAL -->
    <div id="detail-<?php echo $produk['id_produk']; ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="tutupModal('detail-<?php echo $produk['id_produk']; ?>')"> &times; </span>

            <img src="../gambar/<?php echo $produk['foto']; ?>">
            <h2><?php echo $produk['nama_produk']; ?></h2>

            <p class="price"> Rp <?php echo number_format($produk['harga'],0,',','.'); ?> </p>
            <p><strong>Stok:</strong><?php echo $produk['stok']; ?></p>
            <p style="margin-top:10px;"><?php echo $produk['deskripsi']; ?></p>

            <?php if($role == 'user'): ?>
                <form action="proses_keranjang.php" method="POST" style="margin-top:20px;">
                    <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" max="<?php echo $produk['stok']; ?>" class="qty-input">

                    <button type="submit" name="tambah" class="btn btn-cart" value="tambah">Tambah ke Keranjang</button>
                    <input type="hidden" name="aksi" value="beli">
                </form>

                <form action="proses_keranjang.php" method="POST" style="margin-top:10px;">
                    <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                    <input type="hidden" name="jumlah" value="1">
                    <input type="hidden" name="aksi" value="beli">
                    <button type="submit" class="btn btn-detail" style="width:100%; background:#fff0fcf5; color:#bc7cb4; border:2px solid #bc7cb4;">Beli</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php
        }
    }else{
        echo "<p>Belum ada produk tersedia.</p>";
    }
    ?>

    </div>
</main>

<!-- ABOUT US -->
<section class="about-section">

    <div class="about-container">

        <!-- BRAND -->
        <div class="about-brand">

            <div class="footer-logo">
                <img src="../gambar/icon.jpeg" alt="Borcelle Logo">
            </div>

            <p>
                Borcelle Store merupakan website penjualan aksesoris
                wanita dengan konsep aesthetic, modern, dan elegan
                untuk menemani setiap penampilanmu ✨
            </p>

        </div>

        <!-- ANGGOTA -->
        <div class="about-group">
            <h3>Anggota Kelompok XI PPLG</h3>

            <p>• Nickho</p>
            <p>• Ganendra</p>
            <p>• Naysilla</p>
        </div>

        <!-- DEVELOPER -->
        <div class="about-group">
            <h3>Anggota Kelompok XI MPLB</h3>

            <p>• Kinara</p>
            <p>• Ajeng</p>
            <p>• Lira</p>
        </div>

        <!-- CONTACT -->
        <div class="about-contact">
            <h3>Kontak</h3>

            <p>Email : borcellestore@gmail.com</p>
            <p>WhatsApp : 0812-3456-7890</p>
            <p>Instagram : @borcelle.store</p>

            <div class="social-text">
                <p>TikTok : @borcelle.store</p>
                <p>Facebook : Borcelle Store</p>
            </div>
        </div>

    </div>

    <div class="about-bottom">
        Copyright © 2026 Borcelle Store | XI PPLG & MPLB
    </div>

</section>

<script>
    function bukaModal(id){
        document.getElementById(id).style.display = "block";
    }

    function tutupModal(id){
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function(event){
        let modal = document.getElementsByClassName('modal');

        for(let i = 0; i < modal.length; i++){
            if(event.target == modal[i]){
                modal[i].style.display = "none";
            }
        }
    }
</script>
</body>
</html>