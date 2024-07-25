<?php
session_start();
include '../../config/database.php';

// Process the search request if present
$search = "";
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

// Prepare the list of books already in the cart
$kode_buku = "";
if (!empty($_SESSION["cart_buku"])) {
    foreach ($_SESSION["cart_buku"] as $item) {
        $kode = $item["kode_buku"];
        $kode_buku .= "'$kode',";
    }
    $kode_buku = substr($kode_buku, 0, -1);
}

// SQL query to get the list of books
$sql = "SELECT * FROM buku WHERE stok >= 1";
if (!empty($kode_buku)) {
    $sql .= " AND kode_buku NOT IN($kode_buku)";
}
if (!empty($search)) {
    $sql .= " AND judul_buku LIKE '%$search%'";
}
$hasil = mysqli_query($kon, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <!-- Search Form -->
    <form id="searchForm" method="post" action="">
        <div class="form-group">
            <label for="search">Cari Judul Buku:</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Masukkan judul buku" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <!-- Book List -->
    <div class="row" id="bookList">
        <?php while ($data = mysqli_fetch_array($hasil)): ?>
        <div class="col-sm-2">
            <div class="card">
                <div class="card bg-basic">
                    <img class="card-img-top" src="../dist/pustaka/gambar/<?php echo $data['gambar_buku']; ?>" alt="Card image cap">
                    <div class="card-body text-center">
                        <div class="title">
                            <?php echo $data['judul_buku']; ?>
                        </div>
                        <button type="button" class="btn-pilih-buku btn btn-dark btn-block" data-kode_buku="<?php echo $data['kode_buku']; ?>">Pilih</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var search = $('#search').val();

        $.ajax({
            url: '', // The same page
            method: 'POST',
            data: { search: search },
            success: function(response) {
                var newContent = $(response).find('#bookList').html();
                $('#bookList').html(newContent);
            }
        });
    });

    $(document).on('click', '.btn-pilih-buku', function() {
        var kode_buku = $(this).data("kode_buku");

        $.ajax({
            url: 'peminjaman/cart.php',
            method: 'POST',
            data: { kode_buku: kode_buku, aksi: 'pilih_buku' },
            success: function(data) {
                $('#tampil_cart').html(data);
            }
        });
    });
});
</script>

</body>
</html>
