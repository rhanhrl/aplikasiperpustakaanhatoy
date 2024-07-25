<?php
session_start();
$kategori="";
$penulis="";
$penerbit="";

if (isset($_POST['kategori_buku'])) {
	foreach ($_POST['kategori_buku'] as $value)
	{
		$kategori .= "'$value'". ",";
	}
	$kategori = substr($kategori,0,-1);
}else {
    $kategori = "0"; 
}

if (isset($_POST['penulis'])) {
	foreach ($_POST['penulis'] as $value)
	{
		$penulis .= "'$value'". ",";
	}
	$penulis = substr($penulis,0,-1);
}

if (isset($_POST['penerbit'])) {
	foreach ($_POST['penerbit'] as $value)
	{
		$penerbit .= "'$value'". ",";
	}
	$penerbit = substr($penerbit,0,-1);

}
?>

<div class="row">
    <div class="col-sm-2">
        <div class="form-group">
        <?php 
            if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):
        ?>
            <button type="button" id="btn-tambah-buku" class="btn btn-success"><span class="text"><i class="fas fa-book fa-sm"></i> Tambah Buku</span></button>
        <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">

<?php         
    // include database
    include '../../config/database.php';



    if (isset($_POST['kategori_buku']) and !isset($_POST['penulis']) and !isset($_POST['penerbit'])){
        $sql="select * from buku where kategori_buku in($kategori)";
    }else if (isset($_POST['kategori_buku']) and isset($_POST['penulis']) and !isset($_POST['penerbit'])){
        $sql="select * from buku where kategori_buku in($kategori) and penulis in($penulis)";
    }else if (isset($_POST['kategori_buku']) and !isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from buku where kategori_buku in($kategori) and penerbit in($penerbit)";
    }else if (isset($_POST['kategori_buku']) and isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from buku where kategori_buku in($kategori) and penulis in($penulis) and penerbit in($penerbit)";
    }else if (!isset($_POST['kategori_buku']) and isset($_POST['penulis']) and !isset($_POST['penerbit'])){
        $sql="select * from buku where penulis in($penulis)";
    }else if (!isset($_POST['kategori_buku']) and isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from buku where penulis in($penulis) and penerbit in($penerbit)";
    }else if (!isset($_POST['kategori_buku']) and !isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from buku where penerbit in($penerbit)";
    }else{
        $sql="select * from buku";
    }

    $hasil=mysqli_query($kon,$sql);
    $cek=mysqli_num_rows($hasil);

    if ($cek<=0){
        echo"<div class='col-sm-12'><div class='alert alert-warning'>Data tidak ditemukan!</div></div>";
        exit;
    }
    $no=0;
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;
?>
<div class="col-sm-2">
    <div class="card">

        <div class="card bg-basic">
            <img class="card-img-top" src="../dist/pustaka/gambar/<?php echo $data['gambar_buku'];?>"  alt="Card image cap">
            <div class="card-body text-center">
                <div class="title">
                    <h6> <?php echo $data['judul_buku'];?> </h6>
                </div>

            <?php 
                if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):
            ?>
                <button  type="button" class="btn-detail-buku btn btn-info" id_buku="<?php echo $data['id_buku'];?>"  kode_buku="<?php echo $data['kode_buku'];?>" ><span class="text">Detail</i></span></button>
				<button  type="button" class="btn-edit-buku btn btn-warning" id_buku="<?php echo $data['id_buku'];?>" kode_buku="<?php echo $data['kode_buku'];?>" ><span class="text">Ubah</span></button>
				<a href="pustaka/hapus.php?id_buku=<?php echo $data['id_buku']; ?>&gambar_buku=<?php echo $data['gambar_buku']; ?>" class="btn-hapus btn btn-danger" >Hapus</i></a>
            <?php endif; ?>
            <?php 
                if ($_SESSION['level']=='Anggota' or $_SESSION['level']=='anggota'):
            ?>
             <button  type="button" class="btn-detail-buku btn btn-info btn-block" id_buku="<?php echo $data['id_buku'];?>"  kode_buku="<?php echo $data['kode_buku'];?>" ><span class="text">Detail</span></button>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>


<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <!-- Bagian header -->
        <div class="modal-header">
            <h4 class="modal-title" id="judul"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Bagian body -->
        <div class="modal-body">
            <div id="tampil_data">

            </div>  
        </div>
        <!-- Bagian footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>


<script>
    // Tambah buku
    $('#btn-tambah-buku').on('click',function(){
        $.ajax({
            url: 'pustaka/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Buku Baru';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });

    // Melihat detail buku
    $('.btn-detail-buku').on('click',function(){
		var id_buku = $(this).attr("id_buku");
        var kode_buku = $(this).attr("kode_buku");
        $.ajax({
            url: 'pustaka/detail.php',
            method: 'post',
			data: {id_buku:id_buku},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Detail Buku #'+kode_buku;
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });

    // Edit buku
    $('.btn-edit-buku').on('click',function(){
		var id_buku = $(this).attr("id_buku");
		var kode_buku = $(this).attr("kode_buku");
        $.ajax({
            url: 'pustaka/edit.php',
            method: 'post',
			data: {id_buku:id_buku},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit buku #'+kode_buku;
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });


       // fungsi hapus karyawan
    $('.btn-hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus buku ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
