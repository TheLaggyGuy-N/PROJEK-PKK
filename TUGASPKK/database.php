<?php
class database {
    var $host = "localhost";
    var $user = "root";
    var $pass = "";
    var $db   = "db_borcelle";
    var $koneksi = "";

    function __construct() {
        $this->koneksi = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        if (!$this->koneksi) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
    }

    function tampil_produk() {
        $hasil = [];
        $data = mysqli_query($this->koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
        while ($d = mysqli_fetch_array($data)) {
            $hasil[] = $d;
        }
        return $hasil;
    }

    function tampil_keranjang($id_akun) {
        $hasil = [];
        $query = "SELECT 
                    keranjang.id_keranjang,
                    produk.nama_produk,
                    produk.harga,
                    produk.warna,
                    produk.ukuran,
                    produk.deskripsi,
                    produk.foto,
                    keranjang.jumlah,
                    (produk.harga * keranjang.jumlah) AS subtotal
                  FROM keranjang
                  INNER JOIN produk ON keranjang.id_produk = produk.id_produk
                  WHERE keranjang.id_akun = '$id_akun'";
        
        $data = mysqli_query($this->koneksi, $query);
        while ($d = mysqli_fetch_array($data)) {
            $hasil[] = $d;
        }
        return $hasil;
    }

    function tambah_ke_keranjang($id_produk, $jumlah, $id_akun) {
        $cek = mysqli_query($this->koneksi, "SELECT * FROM keranjang WHERE id_produk='$id_produk' AND id_akun='$id_akun'");
        
        if (mysqli_num_rows($cek) > 0) {
            $sql = "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE id_produk='$id_produk' AND id_akun='$id_akun'";
        } else {
            $sql = "INSERT INTO keranjang (id_produk, jumlah, id_akun) VALUES ('$id_produk', '$jumlah', '$id_akun')";
        }
        
        return mysqli_query($this->koneksi, $sql);
    }

    function login_user($username, $password) {
        $query = mysqli_query($this->koneksi, "SELECT * FROM akun WHERE username='$username' AND password='$password'");
        $data = mysqli_fetch_array($query);
        return $data;
    }
}
?>