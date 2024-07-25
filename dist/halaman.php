<?php 
  session_start();
  if (!$_SESSION["id_pengguna"]){
        header("Location:login.php");
  }else {

    include '../config/database.php';
    $id_pengguna=$_SESSION["id_pengguna"];
    $username=$_SESSION["username"];

    $hasil=mysqli_query($kon,"select username from pengguna where id_pengguna=$id_pengguna");
    $data = mysqli_fetch_array($hasil); 
    $username_db=$data['username'];

    if ($username!=$username_db){
        session_unset();
        session_destroy();
        header("Location:login.php");
    }
  }

?>

<?php
  include '../config/database.php';
  $hasil=mysqli_query($kon,"select * from profil_aplikasi order by nama_aplikasi desc limit 1");
  $data = mysqli_fetch_array($hasil); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        
        <title><?php echo "Perpustakaan SMA Hayatan Thayyibah "?></title>
        <link href="../src/templates/css/styles.css" rel="stylesheet" />
        <link href="../src/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


        <link href="../src/plugin/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="../src/js/font-awesome/all.min.js" crossorigin="anonymous"></script>
        <script src="../src/js/jquery/jquery-3.5.1.min.js"></script>
        <script src="../src/plugin/chart/Chart.js"></script>
        <script src="../src/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="../src/plugin/datatables/dataTables.bootstrap4.min.js"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
                    <!-- Navbar Brand with Logo -->
            <div class="navbar-brand" href="#"><img src="../src/img/logo126.png" alt="Logo" width="80%"></div>
                    
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
             
            </form>

            <div class="sb-nav-footer" style="color: black;">
                        <div  >
                        <?php 
                            if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):
                                echo $_SESSION["nama_karyawan"];
                            endif; 
                            if ($_SESSION['level']=='Anggota' or $_SESSION['level']=='anggota'):
                                echo $_SESSION["nama_anggota"];
                            endif; 
                        ?>
                        </div>
                       
            </div>

            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="halaman.php?page=profil">Profil</a>
                        <a class="dropdown-item" href="#"  data-toggle="modal" data-target="#logoutModal" >Logout</a>
                    </div>
                </li>
            </ul>   
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                    <?php 
                        if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):
                    ?>
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="halaman.php?page=dashboard">
                                <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="halaman.php?page=daftar-peminjaman">
                                <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                                Peminjaman
                            </a>
               
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Laporan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="halaman.php?page=laporan-peminjaman"><i class="fas fa-book"></i>  &nbsp; Peminjaman</a>
                                    <a class="nav-link" href="halaman.php?page=laporan-pustaka"><i class="fas fa-grip-horizontal"></i> &nbsp; Buku</a>
                                    <a class="nav-link" href="halaman.php?page=laporan-anggota"><i class="fas fa-user-tag"></i> &nbsp; Anggota</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePustaka" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Buku
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePustaka" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="halaman.php?page=pustaka"><i class="fas fa-book"></i>  &nbsp; Daftar Buku</a>
                                    <a class="nav-link" href="halaman.php?page=kategori"><i class="fas fa-grip-horizontal"></i> &nbsp; Kategori</a>
                                    <a class="nav-link" href="halaman.php?page=penulis"><i class="fas fa-user-tag"></i> &nbsp; Penulis</a>
                                    <a class="nav-link" href="halaman.php?page=penerbit"><i class="fas fa-building"></i> &nbsp; Penerbit</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengguna" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Pengguna
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePengguna" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="halaman.php?page=anggota"><i class="fas fa-user"></i>  &nbsp; Anggota</a>
                                    <a class="nav-link" href="halaman.php?page=karyawan"><i class="fas fa-user-tie"></i> &nbsp; Karyawan</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="halaman.php?page=aplikasi">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                Pengaturan Aplikasi
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php 
                        if ($_SESSION['level']=='Anggota' or $_SESSION['level']=='anggota'):
                        ?>
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="halaman.php?page=dashboard">
                                <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="halaman.php?page=pustaka">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Buku
                            </a>
                            <a class="nav-link" href="halaman.php?page=keranjang">
                                <div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div>
                                Keranjang
                            </a>
                            <a class="nav-link" href="halaman.php?page=peminjaman-saya">
                                <div class="sb-nav-link-icon"><i class="fas fa-list-alt"></i></div>
                                Peminjaman
                            </a>
                            <a class="nav-link" href="halaman.php?page=keterlambatan-saya">
                                <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                                Keterlambatan
                            </a>
                            <a class="nav-link" href="halaman.php?page=denda-saya">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-money-bills"></i></div>
                                Denda
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                </nav>
            </div>
            <div id="layoutSidenav_content">
                <?php 
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    
                        switch ($page) {
                            case 'dashboard':
                                include "dashboard/index.php";
                                break;
                            case 'anggota':
                                include "anggota/index.php";
                                break;
                            case 'karyawan':
                                include "karyawan/index.php";
                                break;
                            case 'pustaka':
                                include "pustaka/index.php";
                                break;
                            case 'penulis':
                                include "pustaka/penulis/index.php";
                                break;
                            case 'penerbit':
                                include "pustaka/penerbit/index.php";
                                break;
                            case 'kategori':
                                include "pustaka/kategori/index.php";
                                break;
                            case 'input-peminjaman':
                                include "peminjaman/input-peminjaman.php";
                                break;
                            case 'daftar-peminjaman':
                                include "peminjaman/index.php";
                                break;
                            case 'detail-peminjaman':
                                include "peminjaman/detail-peminjaman.php";
                                break;
                            case 'laporan-peminjaman':
                                include "laporan/peminjaman/laporan-peminjaman.php";
                                break;
                            case 'laporan-pustaka':
                                include "laporan/pustaka/laporan-pustaka.php";
                                break;
                            case 'laporan-anggota':
                                include "laporan/anggota/laporan-anggota.php";
                                break;
                            case 'laporan-pendapatan':
                                include "laporan/pendapatan/laporan-pendapatan.php";
                                break;
                            case 'keranjang':
                                include "keranjang/index.php";
                                break;
                            case 'booking':
                                include "keranjang/booking.php";
                                break;
                            case 'peminjaman-saya':
                                include "peminjaman/anggota/index.php";
                                break;
                            case 'keterlambatan-saya':
                                include "peminjaman/anggota/keterlambatan.php";
                                break;
                            case 'denda-saya':
                                include "peminjaman/anggota/denda.php";
                                break;
                            case 'profil':
                                include "profil/index.php";
                            break;
                            case 'aplikasi':
                                include "aplikasi/index.php";
                            break;
                        default:
                            echo "<center><h3>Maaf. Halaman tidak di temukan !</h3></center>";
                            break;
                        }
                    }
                ?>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                        <?php 
                            include '../config/database.php';
                            $hasil=mysqli_query($kon,"select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");
                            $data = mysqli_fetch_array($hasil); 
                        ?>
                        <div class="text-muted">Copyright &copy; <?php echo $data['nama_aplikasi'];?> <?php echo date('Y');?></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="../src/js/scripts.js"></script>
        <script src="../src/plugin/select2/select2.min.js"></script>
        <link href="../src/plugin/select2/select2.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="../src/js/jquery-ui/jquery-ui.js"></script>
        <link href="../src/js/jquery-ui/jquery-ui.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="../src/plugin/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
          <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Keluar Aplikasi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin keluar?</div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-warning" href="logout.php">Logout</a>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>
