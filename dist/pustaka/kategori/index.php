<script>
    $('title').text('Data kategori_buku');
</script>


<main>
    <div class="container-fluid">
        <h2 class="mt-4">Data Kategori Buku</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Data Kategori Buku</li>
        </ol>

        <?php
            //Validasi untuk menampilkan pesan pemberitahuan saat user menambah kategori_buku
            if (isset($_GET['add'])) {
                if ($_GET['add']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Kategori Buku telah ditambah!</div>";
                }else if ($_GET['add']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Kategori Buku gagal ditambahkan!</div>";
                }    
            }

            if (isset($_GET['edit'])) {
                if ($_GET['edit']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Kategori Buku telah diupdate!</div>";
                }else if ($_GET['edit']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Kategori Buku gagal diupdate!</div>";
                }    
            }
            if (isset($_GET['hapus'])) {
                if ($_GET['hapus']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Kategori Buku telah dihapus!</div>";
                }else if ($_GET['hapus']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Kategori Buku gagal dihapus!</div>";
                }    
            }
        ?>

        <div class="card mb-4">
          <div class="card-header py-3">
            <!-- Tombol tambah kategori_buku -->
            <button  class="btn-tambah btn btn-dark btn-icon-split"><span class="text">Tambah</span></button>
          </div>
            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="tabel_kategori" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Kode</th>
                          <th>Nama Kategori</th>
                          <th width="10%">Opsi</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                              // include database
                              include '../config/database.php';
                              // perintah sql untuk menampilkan daftar kategori_buku yang berelasi dengan tabel kategori kategori_buku
                              $sql="select * from kategori_buku order by kode_kategori_buku asc";
                              $hasil=mysqli_query($kon,$sql);
                              $no=0;
                              //Menampilkan data dengan perulangan while
                              while ($data = mysqli_fetch_array($hasil)):
                              $no++;
                          ?>
                          <tr>
                              <td><?php echo $no; ?></td>
                              <td><?php echo $data['kode_kategori_buku']; ?></td>
                              <td><?php echo $data['nama_kategori_buku']; ?></td>
                              <td>
                                  <button class="btn-edit btn btn-warning btn-circle" id_kategori_buku="<?php echo $data['id_kategori_buku']; ?>" kode_kategori_buku="<?php echo $data['kode_kategori_buku']; ?>"><i class="fas fa-edit"></i></button>
                                  <a href="pustaka/kategori/hapus-kategori.php?id_kategori_buku=<?php echo $data['id_kategori_buku']; ?>" class="btn-hapus btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
                              </td>
                          </tr>
                          <!-- bagian akhir (penutup) while -->
                          <?php endwhile; ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function(){
        $('#tabel_kategori').DataTable();
    });
</script>

<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title" id="judul"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div id="tampil_data">
                 <!-- Data akan di load menggunakan AJAX -->                   
            </div>  
        </div>
  
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>

<script>

    // Tambah kategori_buku
    $('.btn-tambah').on('click',function(){
        $.ajax({
            url: 'pustaka/kategori/tambah-kategori.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Kategori Buku Baru';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });


    // fungsi edit kategori_buku
    $('.btn-edit').on('click',function(){

        var id_kategori_buku = $(this).attr("id_kategori_buku");
        var kode_kategori_buku = $(this).attr("kode_kategori_buku");
        $.ajax({
            url: 'pustaka/kategori/edit-kategori.php',
            method: 'post',
            data: {id_kategori_buku:id_kategori_buku},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit Kategori Buku #'+kode_kategori_buku;
            }
        });
            // Membuka modal
        $('#modal').modal('show');
    });


    // fungsi hapus kategori_buku
    $('.btn-hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus kategori buku ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>

