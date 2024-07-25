<?php
session_start();
    if (isset($_POST['edit_kategori_buku'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../../config/database.php';

        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $id_kategori_buku=input($_POST["id_kategori_buku"]);
        $nama_kategori_buku=input($_POST["nama_kategori_buku"]);
        
        $sql="update kategori_buku set
        nama_kategori_buku='$nama_kategori_buku'
        where id_kategori_buku=$id_kategori_buku";

        //Mengeksekusi atau menjalankan query 
        $edit_kategori_buku=mysqli_query($kon,$sql);
        
        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($edit_kategori_buku) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../dist/halaman.php?page=kategori&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../dist/halaman.php?page=kategori&edit=gagal");
        }
        
    }

    //-------------------------------------------------------------------------------------------

    $id_kategori_buku=$_POST["id_kategori_buku"];
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM kategori_buku where id_kategori_buku=$id_kategori_buku");
    $data = mysqli_fetch_array($query); 

    $kode_kategori_buku=$data['kode_kategori_buku'];
    $nama_kategori_buku=$data['nama_kategori_buku'];
 
?>
<form action="pustaka/kategori/edit-kategori.php" method="post">
    <div class="form-group">
        <label>Kode kategori Buku:</label>
        <h3><?php echo $kode_kategori_buku; ?></h3>
        <input name="kode_kategori_buku" value="<?php echo $kode_kategori_buku; ?>" type="hidden" class="form-control">
        <input name="id_kategori_buku" value="<?php echo $id_kategori_buku; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama kategori Buku:</label>
        <input name="nama_kategori_buku" value="<?php echo $nama_kategori_buku; ?>" type="text" class="form-control" placeholder="Masukan nama kategori" required>
    </div>

    <button type="submit" name="edit_kategori_buku" id="btn-kategori_buku" class="btn btn-dark" >Update</button>
</form>