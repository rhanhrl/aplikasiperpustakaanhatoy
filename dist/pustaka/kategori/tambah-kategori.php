<?php
session_start();
    if (isset($_POST['tambah_kategori_buku'])) {
        
        //Include file koneksi, untuk koneksikan ke database
        include '../../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Memulai transaksi
            mysqli_query($kon,"START TRANSACTION");

            $kode_kategori_buku=input($_POST["kode_kategori_buku"]);
            $nama_kategori_buku=input($_POST["nama_kategori_buku"]);

            $sql="insert into kategori_buku (kode_kategori_buku,nama_kategori_buku) values
                ('$kode_kategori_buku','$nama_kategori_buku')";


            //Mengeksekusi/menjalankan query 
            $simpan_kategori_buku=mysqli_query($kon,$sql);

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_kategori_buku) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../../dist/halaman.php?page=kategori&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../../dist/halaman.php?page=kategori&add=gagal");
            }

        }
       
    }
?>


<?php
    // mengambil data barang dengan kode paling besar
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_kategori_buku) as kodeTerbesar FROM kategori_buku");
    $data = mysqli_fetch_array($query);
    $id_kategori_buku = $data['kodeTerbesar'];
    $id_kategori_buku++;
    $huruf = "K";
    $kodekategori_buku = $huruf . sprintf("%03s", $id_kategori_buku);
?>
<form action="pustaka/kategori/tambah-kategori.php" method="post">
    <div class="form-group">
        <label>Kode kategori Buku:</label>
        <h3><?php echo $kodekategori_buku; ?></h3>
        <input name="kode_kategori_buku" value="<?php echo $kodekategori_buku; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama kategori Buku:</label>
        <input name="nama_kategori_buku" type="text" class="form-control" placeholder="Masukan nama kategori buku" required>
    </div>

    <button type="submit" name="tambah_kategori_buku" id="btn-kategori_buku" class="btn btn-dark">Tambah</button>
</form>

