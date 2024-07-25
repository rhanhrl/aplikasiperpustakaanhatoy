<?php
    session_start();
    include '../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    $id_buku=$_GET["id_buku"];
    $gambar_buku=$_GET["gambar_buku"];

    $sql="delete from buku where id_buku='$id_buku'";
    $hapus_buku=mysqli_query($kon,$sql);

    //Menghapus file foto jika foto selain gambar default
    if ($gambar_buku!='gambar_default.png'){
        unlink("gambar/".$gambar_buku);
    }

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query-query diatas
    if ($hapus_buku) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../dist/halaman.php?page=pustaka&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../dist/halaman.php?page=pustaka&hapus=gagal");

    }

?>

