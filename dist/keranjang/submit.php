<?php
session_start();
include '../../config/database.php';

// Memulai transaksi
mysqli_query($kon, "START TRANSACTION");

$query = mysqli_query($kon, "SELECT max(id_peminjaman) as id_peminjaman_terbesar FROM peminjaman");
$data = mysqli_fetch_array($query);
$id_peminjaman = $data['id_peminjaman_terbesar'];
$id_peminjaman++;
$kode_peminjaman = sprintf("%05s", $id_peminjaman);
$tanggal = date('Y-m-d');
$tanggal_kembali = date('Y-m-d', strtotime($tanggal . ' + 7 days')); // Set tanggal_kembali to one week from today
$kode_anggota = $_SESSION['kode_pengguna'];
$status = 1; // Set the default status for a new loan (1 for borrowed)
$jenis_denda = 0; // Set the default jenis_denda value
$denda = 0; // Set the default denda value

$simpan_tabel_peminjaman = mysqli_query($kon, "INSERT INTO peminjaman (kode_peminjaman, kode_anggota, tanggal) VALUES ('$kode_peminjaman', '$kode_anggota', '$tanggal')");

// Simpan detail transaksi
$all_detail_saved = true;
if (!empty($_SESSION["cart_buku"])) {
    foreach ($_SESSION["cart_buku"] as $item) {
        $kode_buku = $item['kode_buku'];
        $simpan_tabel_detail = mysqli_query($kon, "INSERT INTO detail_peminjaman (kode_peminjaman, kode_buku, tanggal_pinjam, tanggal_kembali, status, jenis_denda, denda) VALUES ('$kode_peminjaman', '$kode_buku', '$tanggal', '$tanggal_kembali', '$status', '$jenis_denda', '$denda')");
        if (!$simpan_tabel_detail) {
            $all_detail_saved = false;
            break;
        }
    }
}

if ($simpan_tabel_peminjaman && $all_detail_saved) {
    // Jika semua query berhasil, lakukan commit
    mysqli_query($kon, "COMMIT");

    // Kosongkan keranjang belanja
    unset($_SESSION["cart_buku"]);
    header("Location: ../halaman.php?page=booking&kode_peminjaman=$kode_peminjaman");
} else {
    // Jika ada query yang gagal, lakukan rollback
    mysqli_query($kon, "ROLLBACK");

    // Kosongkan keranjang buku
    unset($_SESSION["cart_buku"]);
    header("Location: ../halaman.php?page=booking&add=gagal");
}
?>
