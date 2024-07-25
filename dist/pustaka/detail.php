<?php
session_start();
?>
<div class="card">
    <?php 
        include '../../config/database.php';
        $id_buku=$_POST["id_buku"];
        $sql="select * from buku p 
        inner join kategori_buku k on k.id_kategori_buku=p.kategori_buku
        inner join penulis s on s.id_penulis=p.penulis
        inner join penerbit t on t.id_penerbit=p.penerbit
        where p.id_buku=$id_buku limit 1";
        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil);

    ?>
    <!-- Card Body -->
    <div class="card-body">
    <?php if ($data['stok']<=0): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-warning">
                Mohon maaf stok buku sedang kosong
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-sm-6">
            <img class="card-img-top" src="pustaka/gambar/<?php echo $data['gambar_buku'];?>" alt="Card image">
        </div>
        <div class="col-sm-6">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Judul</td>
                        <td width="78%">: <?php echo $data['judul_buku'];?></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td width="78%">: <?php echo $data['nama_kategori_buku'];?></td>
                    </tr>
                    <tr>
                        <td>Penulis</td>
                        <td width="78%">: <?php echo $data['nama_penulis'];?></td>
                    </tr>
                    <tr>
                        <td>Penerbit</td>
                        <td width="78%">: <?php echo $data['nama_penerbit'];?></td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td width="78%">: <?php echo $data['tahun'];?></td>
                    </tr>
                    <tr>
                        <td>Halaman</td>
                        <td width="78%">: <?php echo $data['halaman'];?></td>
                    </tr>
                    <tr>
                        <td>Jumlah Stok</td>
                        <td width="78%">: <?php echo $data['stok'];?></td>
                    </tr>
                    <tr>
                        <td>Posisi Rak</td>
                        <td width="78%">: <?php echo $data['rak'];?></td>
                    </tr>
                    <?php if ($data['stok']>=1): ?>
                    <tr>
                        <td colspan="2">  
                            <?php if ($_SESSION['level']=='Anggota' or $_SESSION['level']=='anggota'): ?>
                            <a href="halaman.php?page=keranjang&kode_buku=<?php echo $data['kode_buku']; ?>&aksi=pilih_buku"  class="btn btn-dark btn-block"> Masukan Keranjang</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
