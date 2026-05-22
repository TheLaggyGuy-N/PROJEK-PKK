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
    
    function login_user($username, $password) {
        $query = mysqli_query($this->koneksi, "SELECT * FROM akun WHERE username='$username' AND password='$password'");
        $data = mysqli_fetch_array($query);
        return $data;
    }

    function ambil_id_akun($username) {
        $query = mysqli_query($this->koneksi, "SELECT id_akun FROM akun WHERE username='$username' LIMIT 1");
        $data = mysqli_fetch_assoc($query);
        return isset($data['id_akun']) ? (int)$data['id_akun'] : 0;
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
                    produk.id_produk,
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

    function hapus_item_keranjang($id_produk, $id_akun) {
        $sql = "DELETE FROM keranjang WHERE id_produk='$id_produk' AND id_akun='$id_akun'";
        return mysqli_query($this->koneksi, $sql);
    }

    function checkout_pesanan($id_akun, $alamat) {
        $keranjang_query = mysqli_query($this->koneksi, 
        "SELECT keranjang.*, produk.nama_produk, (produk.harga * keranjang.jumlah) AS subtotal 
        FROM keranjang 
        INNER JOIN produk ON keranjang.id_produk = produk.id_produk 
        WHERE keranjang.id_akun = '$id_akun'");
        
        $tgl_sekarang = date('Y-m-d H:i:s');

        while ($item = mysqli_fetch_assoc($keranjang_query)) {
            $id_produk   = $item['id_produk'];
            $nama_produk = $item['nama_produk'];
            $jumlah      = $item['jumlah'];
            $total_bayar = $item['subtotal'];
            
            mysqli_query($this->koneksi, 
            "INSERT INTO transaksi (id_akun, nama_produk, jumlah, total_bayar, alamat, tgl_pesan) 
            VALUES ('$id_akun', '$nama_produk', '$jumlah', '$total_bayar', '$alamat', '$tgl_sekarang')");
            
            mysqli_query($this->koneksi, "UPDATE produk SET stok = stok - $jumlah WHERE id_produk = '$id_produk'");
        }

        $sql_kosongkan = "DELETE FROM keranjang WHERE id_akun='$id_akun'";
        return mysqli_query($this->koneksi, $sql_kosongkan);
    }
}
?>