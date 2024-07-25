<?php
session_start();
    if (isset($_POST['tambah_anggota'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            mysqli_query($kon,"START TRANSACTION");

            $kode=input($_POST["kode"]);
            $judul_buku=$_POST["judul_buku"];
            $kategori_buku=input($_POST["kategori_buku"]);
            $penulis=input($_POST["penulis"]);
            $penerbit=input($_POST["penerbit"]);
            $tahun=input($_POST["tahun"]);
            $halaman=input($_POST["halaman"]);
            $dimensi=input($_POST["dimensi"]);
            $stok=input($_POST["stok"]);
            $rak=input($_POST["rak"]);

            $tanggal=date("Y-m-d");

            $ekstensi_diperbolehkan	= array('png','jpg');
            $gambar_buku = $_FILES['gambar_buku']['name'];
            $x = explode('.', $gambar_buku);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['gambar_buku']['size'];
            $file_tmp = $_FILES['gambar_buku']['tmp_name'];	

            if (!empty($gambar_buku)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    if($ukuran < 1044070){	
                        //Mengupload gambar
                        move_uploaded_file($file_tmp, 'gambar/'.$gambar_buku);
                        $sql="insert into buku (kode_buku,judul_buku,kategori_buku,penulis,penerbit,tahun,gambar_buku,halaman,dimensi,stok,rak) values
                        ('$kode','$judul_buku','$kategori_buku','$penulis','$penerbit','$tahun','$gambar_buku','$halaman','$dimensi','$stok','$rak')";
                    }
                }
            }else {
                $gambar_buku="gambar_default.png";
                $sql="insert into buku (kode_buku,judul_buku,kategori_buku,penulis,penerbit,tahun,gambar_buku,halaman,dimensi,stok,rak) values
                ('$kode','$judul_buku','$kategori_buku','$penulis','$penerbit','$tahun','$gambar_buku','$halaman','$dimensi','$stok','$rak')";
            }

            $simpan_buku=mysqli_query($kon,$sql);

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_buku) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../dist/halaman.php?page=pustaka&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../dist/halaman.php?page=pustaka&add=gagal");
            }
        }
    }
      // mengambil data buku dengan kode paling besar
      include '../../config/database.php';
      $query = mysqli_query($kon, "SELECT max(id_buku) as kodeTerbesar FROM buku");
      $data = mysqli_fetch_array($query);
      $id_buku = $data['kodeTerbesar'];
      $id_buku++;
      $huruf = "B";
      $kodebuku = $huruf . sprintf("%04s", $id_buku);

?>
<form action="pustaka/tambah.php" method="post" enctype="multipart/form-data">
    <!-- rows -->
    <div class="row">
        <div class="col-sm-10">
            <div class="form-group">
                <label>Judul Buku:</label>
                <input name="judul_buku" type="text" class="form-control" placeholder="Masukan judul buku" required>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Kode:</label>
                <h3><?php echo $kodebuku; ?></h3>
                <input name="kode" value="<?php echo $kodebuku; ?>" type="hidden" class="form-control">
            </div>
        </div>
    </div>
    <!-- rows -->                 
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori_buku" class="form-control">
                <?php
                    $sql="select * from kategori_buku order by id_kategori_buku asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_kategori_buku']; ?>"><?php echo $data['nama_kategori_buku']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Penulis:</label>
                <select name="penulis" class="form-control">
                <?php
                    
                    $sql="select * from penulis order by id_penulis asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_penulis']; ?>"><?php echo $data['nama_penulis']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>
    <!-- rows -->
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Penerbit:</label>
                <select name="penerbit" class="form-control">
                <?php
                    $sql="select * from penerbit order by id_penerbit asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_penerbit']; ?>"><?php echo $data['nama_penerbit']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tahun Terbit:</label>
                <input name="tahun" type="number" class="form-control" placeholder="Masukan tahun" required>
            </div>
        </div>
    </div>
    <!-- rows -->                 
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Halaman:</label>
                        <input name="halaman" type="number" class="form-control" placeholder="Masukan jumlah halaman" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Dimensi:</label>
                        <input name="dimensi" type="text" class="form-control" placeholder="Masukan dimensi" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jumlah Stok:</label>
                        <input name="stok" type="number" class="form-control" placeholder="Masukan stok" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Posisi Rak:</label>
                        <input name="rak" type="text" class="form-control" placeholder="Masukan posisi rak" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- rows -->   
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div id="msg"></div>
                <label>Gambar Buku:</label>
                <input type="file" name="gambar_buku" class="file" >
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload Gambar" id="file">
                        <div class="input-group-append">
                            <button type="button" id="pilih_gambar" class="browse btn btn-dark">Pilih</button>
                        </div>
                    </div>
                <img src="../src/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
    </div>

    <!-- rows -->   
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
             <button type="submit" name="tambah_anggota" class="btn btn-success">Tambah</button>
            </div>
        </div>
    </div>

</form>

<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>
<script>
    $(document).on("click", "#pilih_gambar", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
    });
    $('input[type="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("preview").src = e.target.result;
    };
    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
    });
</script>
