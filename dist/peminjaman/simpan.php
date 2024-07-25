<?php
// Memulai session dan koneksi database
session_start();
include '../../config/database.php';

// Memulai transaksi
mysqli_query($kon, "START TRANSACTION");

$query = mysqli_query($kon, "SELECT max(id_peminjaman) as id_peminjaman_terbesar FROM peminjaman");
$data = mysqli_fetch_array($query);
$id_peminjaman = $data['id_peminjaman_terbesar'];
$id_peminjaman++;
$kode_peminjaman = sprintf("%05s", $id_peminjaman);

$kode_anggota = $_GET['kode_anggota'];
$tanggal_pinjam = date('Y-m-d');
$status = "1";

// Ambil waktu peminjaman dari aturan perpustakaan
$query = mysqli_query($kon, "SELECT waktu_peminjaman FROM aturan_perpustakaan LIMIT 1");
$data = mysqli_fetch_array($query);
$waktu_peminjaman = $data['waktu_peminjaman'];
$tanggal_kembali = date('Y-m-d', strtotime("+$waktu_peminjaman days"));

$simpan_tabel_peminjaman = mysqli_query($kon, "INSERT INTO peminjaman (kode_peminjaman, kode_anggota, tanggal) VALUES ('$kode_peminjaman', '$kode_anggota', '$tanggal_pinjam')");

// Inisialisasi variabel untuk menyimpan status query detail peminjaman dan update stok
$detail_peminjaman_berhasil = true;
$update_stok_berhasil = true;

// Simpan detail transaksi
if (!empty($_SESSION["cart_buku"])):
    foreach ($_SESSION["cart_buku"] as $item):
        $kode_buku = $item['kode_buku'];
        
        $simpan_detail_peminjaman = mysqli_query($kon, "INSERT INTO detail_peminjaman (kode_peminjaman, kode_buku, tanggal_pinjam, tanggal_kembali, status) VALUES ('$kode_peminjaman', '$kode_buku', '$tanggal_pinjam', '$tanggal_kembali', '$status')");
        
        // Cek apakah query berhasil
        if (!$simpan_detail_peminjaman) {
            $detail_peminjaman_berhasil = false;
            break;
        }

        $ambil_buku = mysqli_query($kon, "SELECT stok FROM buku WHERE kode_buku='$kode_buku'");
        $data = mysqli_fetch_array($ambil_buku); 
        $stok = $data['stok'] - 1;

        // Update stok buku
        $update_stok = mysqli_query($kon, "UPDATE buku SET stok=$stok WHERE kode_buku='$kode_buku'");

        // Cek apakah query berhasil
        if (!$update_stok) {
            $update_stok_berhasil = false;
            break;
        }

    endforeach;
endif;

// Kondisi apakah berhasil atau tidak dalam mengeksekusi beberapa query di atas
if ($simpan_tabel_peminjaman && $detail_peminjaman_berhasil && $update_stok_berhasil) {
    // Jika semua query berhasil, lakukan commit
    mysqli_query($kon, "COMMIT");

    // Kosongkan keranjang belanja
    unset($_SESSION["cart_buku"]);
    header("Location: ../halaman.php?page=daftar-peminjaman&add=berhasil");
} else {
    // Jika ada query yang gagal, lakukan rollback
    mysqli_query($kon, "ROLLBACK");

    // Kosongkan keranjang belanja
    unset($_SESSION["cart_buku"]);
    header("Location: ../halaman.php?page=daftar-peminjaman&add=gagal");
}

// Tutup koneksi ke database
mysqli_close($kon);
?>
