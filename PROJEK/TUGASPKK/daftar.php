<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Borcelle Store</title>
</head>
<body>
    <form action="autentikasi.php" method="POST">
        <h1>Daftar Akun Baru</h1>

        <div class="forms">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required>
        </div>

        <div class="forms">
            <label>Gmail</label>
            <input type="email" name="gmail" required placeholder="user@gmail.com">
        </div>

        <div class="forms">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="forms">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="forms">
            <label>Nomer Telepon</label>
            <input type="number" name="notlp">
        </div>

        <div class="forms">
            <label>Alamat</label>
            <input type="text" name="alamat" required>
        </div>

        <div class="tombol">
            <button type="submit" name="daftar">Daftar Sekarang</button>
            <br><br>
            <a href="login.php">Sudah punya akun? Login di sini</a>
            <br>
            <a href="beranda.php">Kembali ke Beranda</a>
        </div>
    </form>
</body>
</html>