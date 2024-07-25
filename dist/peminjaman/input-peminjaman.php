<script>
    $('title').text('Input Peminjaman');
</script>
<?php
    // mengambil data penjualan dengan kode paling besar
    include '../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_peminjaman) as id_peminjaman_terbesar FROM peminjaman");
    $data = mysqli_fetch_array($query);
    $id_peminjaman = $data['id_peminjaman_terbesar'];
    $id_peminjaman++;
    $kode_peminjaman = sprintf("%05s", $id_peminjaman);

?>
<main>
    <input type="hidden" name="kode_peminjaman" value="<?php echo $kode_peminjaman; ?>"/>
        <div class="container-fluid">
            <h2 class="mt-4">Input Peminjaman #<?php echo $kode_peminjaman; ?></h2>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Input Peminjaman #<?php echo $kode_peminjaman; ?></li>
            </ol>
            <div class="card shadow mb-1">
                <div class="card-body">
                    <div class="collapse show">
                        <!-- form -->
                        <?php if (!isset($_GET['anggota']) && !isset($_GET['search'])): ?>
                        <div class="alert alert-info">
                            Silahkan pilih Anggota dibawah ini atau cari anggota:
                        </div>
                        <?php endif; ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="get" class="mb-0">
                            <input type="hidden" name="page" value="input-peminjaman"/>
                            <div class="form-row align-items-end">
                                <div class="col-md-4 mb-2 mb-md-0">
                                        <div class="form-group mb-0">
                                            <input type="text" class="form-control" name="search" placeholder="Cari Nama Anggota" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                        </div>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <div class="form-group mb-0">
                                        <select class="form-control" name="anggota" id="anggota">
                                            <?php
                                            include '../config/database.php';
                                            // Perintah SQL untuk menampilkan semua data pada tabel anggota atau berdasarkan pencarian
                                            $search = "";
                                            if (isset($_GET['search'])) {
                                                $search = trim($_GET['search']);
                                                $sql = "SELECT * FROM anggota WHERE nama_anggota LIKE '%$search%'";
                                            } else {
                                                $sql = "SELECT * FROM anggota";
                                            }

                                            $hasil = mysqli_query($kon, $sql);
                                            if (mysqli_num_rows($hasil) == 0) {
                                                echo "<option>Tidak ada data ditemukan</option>";
                                            } else {
                                                while ($data = mysqli_fetch_array($hasil)) {
                                                    $selected = (isset($_GET['anggota']) && $_GET['anggota'] == $data['kode_anggota']) ? "selected" : "";
                                                    echo "<option value='{$data['kode_anggota']}' $selected>{$data['kode_anggota']} - {$data['nama_anggota']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2 mb-md-0">
                                    <button type="submit" class="btn btn-primary w-100"><span class="text"><i class="fas fa-search fa-sm"></i> Cari</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                <?php if (isset($_GET['anggota'])): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php

                            //Kosongkan kerangjang belanja
                                unset($_SESSION["cart_buku"]);
                                include '../config/database.php';
                                $kode_anggota=addslashes(trim($_GET['anggota']));
                                $query1 = mysqli_query($kon, "SELECT * FROM anggota where kode_anggota='$kode_anggota'");
                                $cek = mysqli_num_rows($query1);

                                if ($cek<=0){
                                    echo "";
                                    exit;
                                }
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
                                    <?php echo $data1['nama_anggota'];?> saat ini sedang meminjam sebanyak <?php echo $jumlah_pinjam; ?> buku. Maksimal buku yang dapat dipinjam adalah <?php echo $maksimal_peminjaman; ?>
                                </div>
                            <?php }else{ ?>
                                <div class="alert alert-warning">
                                    <?php echo $data1['nama_anggota'];?> saat ini telah mencapai batas maksimal peminjaman. Kembalikan terlebih dahulu untuk dapat melakukan peminjaman berikutnya.
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">                                    
                                        <div class="col-sm-12">
                                            <div class="card">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">Profil Anggota</h6>
                                                    </div>
                                                    <div class="card-body">
                                                    <?php
                                                        include '../config/database.php';
                                                        $kode_anggota=$_GET['anggota'];
                                                        $query = mysqli_query($kon, "SELECT * FROM anggota where kode_anggota='$kode_anggota'");
                                                        $data = mysqli_fetch_array($query);     
                                                    ?>

                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Kode</td>
                                                                    <td width="70%">: <?php echo $data['kode_anggota'];?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Nama</td>
                                                                    <td width="70%">: <?php echo $data['nama_anggota'];?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>No Telp</td>
                                                                    <td width="70%">: <?php echo $data['no_telp'];?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Email</td>
                                                                    <td width="70%">: <?php echo $data['email'];?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <button type="button" kode_anggota="<?php echo $data['kode_anggota'];?>" class="btn btn-dark" id="lihat_riwayat_peminjaman">Lihat Riwayat Peminjam</button>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-8">
                            <div id="tampil_cart"></div>
                            <?php
                                include '../config/database.php';
                                            
                                $hasil=mysqli_query($kon,"select waktu_peminjaman from aturan_perpustakaan limit 1");
                                $data = mysqli_fetch_array($hasil); 
                                $waktu_pinjam=$data['waktu_peminjaman'];
                                $tgl=date('d-m-Y H:i');
                                $tanggal_pinjam = date("d/m/Y",strtotime($tgl));
                                $tanggal_kembali=date("d/m/Y",strtotime("+".$waktu_pinjam." day",strtotime($tgl)));
                            ?>

                                <div class="form-group">
                                    <span class="badge badge-info" >Tanggal Pinjam &nbsp;&nbsp;: <?php echo $tanggal_pinjam; ?></span>
                                </div>


                                <div class="form-group">
                                    <span class="badge badge-info" >Tanggal Kembali : <?php echo $tanggal_kembali; ?></span>
                                </div>
                               
                                <div class="form-group">
                                    <a href="peminjaman/simpan.php?kode_anggota=<?php echo $_GET['anggota'];?>"  id="tombol_simpan_peminjaman" class="btn btn-success float-right"> Simpan</a>
                                </div>
           
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
</main>

<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-xl">
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

    $(function() {
        $("#kode_anggota").autocomplete({
            source: 'peminjaman/autocomplete.php',
            select : showResult, 
            focus : showResult, 
            change :showResult 
        });

        function showResult(event, ui) { 
            $('#kode_anggota').val(ui.item.label);
            var kode_anggota = $('#kode_anggota').val();
                $.ajax({
                url: 'peminjaman/ambil-anggota.php',
                method: 'post',
                data: {kode_anggota:kode_anggota},
                success:function(data){
                    $('#tampil_data_anggota').html(data);
                }
            });

            $.ajax({
                url: 'peminjaman/info-peminjaman.php',
                method: 'post',
                data: {kode_anggota:kode_anggota},
                success:function(data){
                    $('#info_peminjaman').html(data);
                }
            }); 

        } 
    });

    //Lihat Riawayat Peminjaman
    $('#lihat_riwayat_peminjaman').on('click',function(){
        var kode_anggota = $(this).attr("kode_anggota");
        $.ajax({
            url     : 'peminjaman/riwayat-peminjaman.php',
            method  : 'post',
            data    : {kode_anggota:kode_anggota},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Lihat Riwayat Peminjaman';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });

    //Menampilkan cart atau kerangjang belanja
    $(document).ready(function(){
        $.ajax({
            type	: 'POST',
            url     : 'peminjaman/cart.php',
            data	: '',
            cache	: false,
            success	: function(data){
                $("#tampil_cart").html(data);
            }
        });
    });

</script>