<script>
    $('title').text('Keranjang Buku');
</script>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Keranjang Buku</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar buku yang telah dimasukan ke keranjang</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php

                            include '../config/database.php';
                            $kode_anggota=$_SESSION['kode_pengguna'];
                            $query1 = mysqli_query($kon, "SELECT * FROM anggota where kode_anggota='$kode_anggota'");
                            $data1 = mysqli_fetch_array($query1);    
                            
                            $query3 = mysqli_query($kon, "SELECT * FROM detail_peminjaman d inner join peminjaman p on d.kode_peminjaman=p.kode_peminjaman where p.kode_anggota='$kode_anggota' and d.status='1'");
                            $jumlah_pinjam = mysqli_num_rows($query3);

                            $query4=mysqli_query($kon,"select maksimal_peminjaman from aturan_perpustakaan limit 1");
                            $data4 = mysqli_fetch_array($query4); 
                            $maksimal_peminjaman=$data4['maksimal_peminjaman']-$jumlah_pinjam;

                            if ($maksimal_peminjaman < 0){
                                $maksimal_peminjaman=0;
                            }

                            $_SESSION["maksimal_peminjaman"]=$maksimal_peminjaman;

                        ?>

                        <?php if ($maksimal_peminjaman!=0){?>
                            <div class="alert alert-info">
                            Hi <?php echo $data1['nama_anggota'];?> saat ini kamu dapat melakukan peminjaman maksimal sebanyak <?php echo $maksimal_peminjaman; ?> buku.
                            </div>
                        <?php }else{ ?>
                            <div class="alert alert-warning">
                                Hi <?php echo $data1['nama_anggota'];?> saat ini kamu telah mencapai batas maksimal peminjaman. Kembalikan terlebih dahulu buku yang sedang dipinjam agar dapat melakukan peminjaman berikutnya.
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <a href="halaman.php?page=pustaka"  id="tombol_pilih_buku" class="btn btn-dark">Pilih Buku</a>
                        </div>
                    </div>
                </div>
                <?php
                    if (isset($_GET['kode_buku'])) {

                        $kode_buku=$_GET['kode_buku'];
                        
                        include '../config/database.php';
                        $sql= "SELECT * from buku p
                        inner join penulis s on s.id_penulis=p.penulis
                        inner join penerbit t on t.id_penerbit=p.penerbit
                        inner join kategori_buku k on k.id_kategori_buku=p.kategori_buku
                        where p.kode_buku='$kode_buku'";

                        $query = mysqli_query($kon,$sql);
                        $data = mysqli_fetch_array($query);

                        $judul_buku=$data['judul_buku'];
                        $nama_kategori_buku=$data['nama_kategori_buku'];
                        $nama_penulis=$data['nama_penulis'];
                        $nama_penerbit=$data['nama_penerbit'];
                        $tahun=$data['tahun'];

                    }else {
                        $kode_buku="";
                    }

                    if (isset($_GET['aksi'])) {
                        $aksi=$_GET['aksi'];
                    }else {
                        $aksi="";
                    }


                    //Memasukan data ke dalam array
                    if (isset($_GET['aksi'])) {
                    $itemArray = array($data['kode_buku']=>array('kode_buku'=>$kode_buku,'judul_buku'=>$judul_buku,'nama_kategori_buku'=>$nama_kategori_buku,'nama_penulis'=>$nama_penulis,'nama_penerbit'=>$nama_penerbit,'tahun'=>$tahun));
                    }
                    switch($aksi){	
                        //Fungsi untuk menambah penyewaan kedalam cart
                        case "pilih_buku":
                        if(!empty($_SESSION["cart_buku"])) {
                            if(in_array($data['kode_buku'],array_keys($_SESSION["cart_buku"]))) {
                                foreach($_SESSION["cart_buku"] as $k => $v) {
                                        if($data['kode_buku'] == $k) {
                                            $_SESSION["cart_buku"] = array_merge($_SESSION["cart_buku"],$itemArray);
                                        }
                                }
                            } else {
                                $_SESSION["cart_buku"] = array_merge($_SESSION["cart_buku"],$itemArray);
                            }
                        } else {
                            $_SESSION["cart_buku"] = $itemArray;
                        }
                        break;
                        //Fungsi untuk menghapus penyewaan dari cart
                        case "hapus_buku":
                            if(!empty($_SESSION["cart_buku"])) {
                                foreach($_SESSION["cart_buku"] as $k => $v) {
                                        if($_GET["kode_buku"] == $k)
                                            unset($_SESSION["cart_buku"][$k]);
                                        if(empty($_SESSION["cart_buku"]))
                                            unset($_SESSION["cart_buku"]);
                                }
                            }
                        break;
                    }
                ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $no=0;
                                    $jum=0;
                        
                                    if(!empty($_SESSION["cart_buku"])):
                                    foreach ($_SESSION["cart_buku"] as $item):
                                        $no++;
                                        $jum+=1;
                                ?>
                                    <input type="hidden" name="kode_buku[]" class="kode_buku" value="<?php echo $item["kode_buku"]; ?>"/>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $item["kode_buku"]; ?></td>
                                        <td><?php echo $item["judul_buku"]; ?></td>
                                        <td><?php echo $item["nama_kategori_buku"]; ?></td>
                                        <td><?php echo $item["nama_penulis"]; ?></td>
                                        <td><?php echo $item["nama_penerbit"]; ?></td>
                                        <td><?php echo $item["tahun"]; ?></td>
                                        <td><a href="halaman.php?page=keranjang&kode_buku=<?php echo $item['kode_buku']; ?>&aksi=hapus_pustaka" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                <?php 
                                    endforeach;
                                    endif;
                                ?>
                                </tbody>
                            </table>
                            <div id="pesan"> </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php if(!empty($_SESSION["cart_buku"])): ?>
                            <a href="keranjang/submit.php" id="ajukan" class="btn btn-success"> Ajukan Sekarang</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php 
    if ($jum<$_SESSION["maksimal_peminjaman"]){
        echo "<script>  $('#tombol_pilih_buku').show(); </script>";
        echo "<script>  $('#ajukan').show(); </script>";
    } else if ($jum==$_SESSION["maksimal_peminjaman"]){
    ?>
        <script>  
            $('#tombol_pilih_buku').hide(); 
            $('#ajukan').show();
            $('#pesan').html("<span class='text-danger'>Telah mencapai batas maksimal peminjaman</span>"); 
        </script>
    <?php 
    }else {
    ?>
        <script>  
            $('#tombol_pilih_buku').hide(); 
            $('#ajukan').hide();
            $('#pesan').html("<span class='text-warning'>Tidak boleh melebihi batas peminjaman. Kurangi salah satu buku dalam keranjang</span>"); 
        </script>
    <?php
    }
?>

<script>
   // konfirmasi pengajuan
   $('#ajukan').on('click',function(){
        konfirmasi=confirm("Apakah anda yakin ingin mengajukan peminjaman buku ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>



