<?php
// Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data) {
    if ($data === null) return "";
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Cek apakah ada kiriman form dari method post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();

    include "../config/database.php";

    mysqli_query($kon, "START TRANSACTION");

    $query = mysqli_query($kon, "SELECT max(id_anggota) as kodeTerbesar FROM anggota");
    $data = mysqli_fetch_array($query);
    $id_anggota = $data['kodeTerbesar'];
    $id_anggota++;
    $huruf = "A";
    $kode_anggota = $huruf . sprintf("%03s", $id_anggota);

    $nama_anggota = input($_POST["nama"] ?? "");
    $nomor_telp = input($_POST["nomor_telp"] ?? "");
    $email = input($_POST["email"] ?? "");
    $alamat = input($_POST["alamat"] ?? "");
    $tempat_lahir = input($_POST["tempat_lahir"] ?? "");
    $tanggal_lahir = input($_POST["tanggal_lahir"] ?? "");
    $jenis_kelamin = input($_POST["jenis_kelamin"] ?? "");
    $username = input($_POST["username"] ?? "");
    $password = input(md5($_POST["password"] ?? ""));
    $status = 1;
    $level = "Anggota";

    $simpan_anggota = mysqli_query($kon, "INSERT INTO anggota (kode_anggota, nama_anggota, no_telp, email, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin) VALUES ('$kode_anggota', '$nama_anggota', '$nomor_telp', '$email', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin')");
    $simpan_pengguna = mysqli_query($kon, "INSERT INTO pengguna (kode_pengguna, username, password, status, level) VALUES ('$kode_anggota', '$username', '$password', '$status', '$level')");

    // Kondisi apakah berhasil atau tidak dalam mengeksekusi query di atas
    if ($simpan_anggota && $simpan_pengguna) {
        mysqli_query($kon, "COMMIT");
        header("Location:login.php?daftar=berhasil");
    } else {
        mysqli_query($kon, "ROLLBACK");
        header("Location:daftar.php?daftar=gagal");
    }
}
?>
<!DOCTYPE html>
<?php 
include '../config/database.php';
$hasil = mysqli_query($kon, "SELECT * FROM profil_aplikasi ORDER BY nama_aplikasi DESC LIMIT 1");
$data = mysqli_fetch_array($hasil); 
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $data['nama_aplikasi'];?></title>
    <link href="../src/templates/css/styles.css" rel="stylesheet" />
    <script src="../src/js/font-awesome/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Buat Akun Baru</h3></div>
                                <div class="card-body">
                                    <?php 
                                    if (isset($_GET['daftar'])) {
                                        if ($_GET['daftar']=='gagal'){
                                            echo"<div class='alert alert-warning'><strong>Gagal!</strong> Pendaftaran akun gagal. Coba sekali lagi atau hubungi CS!</div>";
                                        }   
                                    }
                                    ?>
                                    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1">Nama</label>
                                                    <input class="form-control py-4" name="nama" type="text" placeholder="Masukan nama" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1">Nomor Telp</label>
                                                    <input class="form-control py-4" name="nomor_telp" type="number" placeholder="Masukan nomor telp" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1">Email</label>
                                            <input class="form-control py-4" type="email" name="email" aria-describedby="emailHelp" placeholder="Masukan email" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1">Alamat</label>
                                            <input class="form-control py-4" name="alamat" type="text" placeholder="Masukan alamat" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1">Tempat Lahir</label>
                                            <input class="form-control py-4" name="tempat_lahir" type="text" placeholder="Masukan tempat lahir" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1">Tanggal Lahir</label>
                                            <input class="form-control py-4" name="tanggal_lahir" type="date" required />
                                        </div>
                                            <div class="form-group">
                                            <label>Jenis Kelamin:</label>
                                            <select class="form-control" name="jenis_kelamin" required>
                                                <option>Pilih</option>
                                                <option value="1">Laki-laki</option>
                                                <option value="2">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1">Username</label>
                                                    <input class="form-control py-4" name="username" id="username" type="text" placeholder="Masukan username" required />
                                                    <div id="info_username"> </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1">Password</label>
                                                    <input class="form-control py-4" name="password" type="password" placeholder="Masukan password" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-4 mb-0"> <button class="btn btn-warning btn-block" id="submit" type="submit">Submit</button></div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="login.php">Sudah memiliki akun? Login sekarang!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; <?php echo $data['nama_aplikasi'];?> <?php echo date('Y');?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="../src/js/jquery/jquery-3.5.1.min.js"></script>
    <script src="../src/plugin/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../src/js/scripts.js"></script>
</body>
</html>

<script>
    // Untuk cek ketersediaan username
    $("#username").bind('keyup', function () {
        var username = $('#username').val();
        $.ajax({
            url: 'anggota/cek-username.php',
            method: 'POST',
            data: {username: username},
            success: function(data) {
                $('#info_username').show();
                $('#info_username').html(data);
            }
        });
    });
</script>
