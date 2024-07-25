<?php
    include '../../../config/database.php';

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id_kategori_buku=input($_GET["id_kategori_buku"]);
 

    $hapus_kategori_buku=mysqli_query($kon,"delete from kategori_buku where id_kategori_buku=$id_kategori_buku");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_kategori_buku) {
        header("Location:../../../dist/halaman.php?page=kategori&hapus=berhasil");
    }
    else {
        header("Location:../../../dist/halaman.php?page=kategori&hapus=gagal");
    }
    
?>
