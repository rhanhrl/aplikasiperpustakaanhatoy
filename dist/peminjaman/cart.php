<?php
session_start();
    if (isset($_POST['kode_buku'])) {
        $kode_buku=$_POST['kode_buku'];
           
        include '../../config/database.php';
        $sql= "SELECT * from buku p
        inner join penulis s on s.id_penulis=p.penulis
        inner join penerbit t on t.id_penerbit=p.penerbit
        where p.kode_buku='$kode_buku'";
        $query = mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($query);
        $judul_buku=$data['judul_buku'];
        $nama_penulis=$data['nama_penulis'];
        $nama_penerbit=$data['nama_penerbit'];
        $tahun=$data['tahun'];
    }else {
        $kode_buku="";
    }
    if (isset($_POST['aksi'])) {
        $aksi=$_POST['aksi'];
    }else {
        $aksi="";
    }


    //Memasukan data ke dalam array
    if (isset($_POST['aksi'])) {
    $itemArray = array($data['kode_buku']=>array('kode_buku'=>$kode_buku,'judul_buku'=>$judul_buku,'nama_penulis'=>$nama_penulis,'nama_penerbit'=>$nama_penerbit,'tahun'=>$tahun));
    }
    switch($aksi) {	
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
                        if($_POST["kode_buku"] == $k)
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
            <div class="col-sm-3">
                <div class="form-group">
                    <button type="button" name="tombol_pilih_buku" id="tombol_pilih_buku" class="btn btn-primary">Pilih Buku</button>
                </div>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Perbit</th>
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
                        <td><?php echo $item["judul_buku"]; ?></td>
                        <td><?php echo $item["nama_penulis"]; ?></td>
                        <td><?php echo $item["nama_penerbit"]; ?></td>
                        <td><?php echo $item["tahun"]; ?></td>
                       
                        <td><button type="button" kode_buku="<?php echo $item["kode_buku"]; ?>"  class="hapus_buku btn btn-danger btn-circle"  ><i class="fas fa-trash"></i></button></td>
                    </tr>
                <?php 
                    endforeach;
                    endif;
                ?>
                </tbody>
            </table>
            <?php 
            if ($_SESSION["maksimal_peminjaman"] <= $jum){
                echo "<script> document.getElementById('tombol_pilih_buku').disabled = true; </script>";
        
                echo"<span class='text-danger'>Telah mencapai batas maksimal peminjaman</span>";
            }
            ?>
        </div>
    </div>
</div>
<script>

    //Fungsi untuk menghapus penyewaan mobil dari cart (keranjang belanja)
    $('.hapus_buku').on('click',function(){
        var kode_buku = $(this).attr("kode_buku");
        var aksi ='hapus_buku';
        $.ajax({
            url: 'peminjaman/cart.php',
            method: 'POST',
            data:{kode_buku:kode_buku,aksi:aksi},
            success:function(data){
                $('#tampil_cart').html(data);
            }
        }); 
    });

    //Fungsi untuk menampilkan pemberitahuan caart masih kosong saat pengguna mengklik tombol selanjutnya
    $('#simpan_peminjaman').on('click',function(){
        var kode_buku=$(".kode_buku").val();

        if(kode_buku==null) {
            alert('Belum ada buku yang diilih');
            return false;
        }

    });

    // edit pembayaran
    $('#tombol_pilih_buku').on('click',function(){
        var id_buku = $(this).attr("id_buku");
        var kode_buku = $(this).attr("kode_buku");
        $.ajax({
            url: 'peminjaman/daftar-pustaka.php',
            method: 'post',
            data: {id_buku:id_buku},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Pilih Buku';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>