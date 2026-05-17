<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="gambar/icon.jpeg">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <form action="autentikasi.php" method="POST">
        <h1>Login</h1>
        <div class="forms">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="forms">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="forms">
            <label>Gmail</label>
            <input type="email" name="gmail" required placeholder="user@gmail.com">
        </div>
        <button type="submit" name="login">Login</button><br><br>
        <a href="daftar.php">Belum Punya Akun?</a>
    </form>
</body>
</html>