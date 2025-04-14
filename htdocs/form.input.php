<?php
    //Untuk terhubung dg database
    include("func.php");
    //Check jika ada id yang dibawa
    if(!empty($_GET["id"])){
      //jika ada, cek idnya di database
      $id = $_GET["id"];
      $q=$db->prepare("select * from produk where nama_produk=?");
      $q->execute([$id]);

      //jika ditemukan di database, maka tampilkan datanya
      if($q->rowCount()> 0){
        $r= $q->fetch();
        $nama_produk=$r["nama_produk"];
        $kategori=$r["kategori"];
        $stok=$r["stok"];
        $harga=$r["harga"];
        $gambar=$r["gambar"];
        
        $button="ubah";
      
      //jika tidak ditemukan, maka kembali ke tabel dg pesan
      }else{
        $_SESSION["notif"]="Maaf, Data tidak ditemukan";
        header("Location: table.php");
        die();
      }
    
    //jika tidak membawa id, berarti itu perintah tambah / simpan
    }else{
      $nama_produk="";
      $kategori="";
      $stok="";
      $button="simpan";
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Input Produk</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/navbar-fixed/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="navbar-top-fixed.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href="#">Input Produk</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="table.php">Kembali <span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
  <div class="jumbotron">
    <h1>Data Barang</h1>
    <br>

    <!-- Form Menambah Produk -->
    <form method="POST" action="form.action.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Nama Produk</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Nama Produk" name="nama_produk" required value="<?php echo $nama_produk; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Kategori</label>
        <div class="col-sm-10">
            <input type="radio" name="kategori" value="Produk Dua Kelinci" <?php echo ($kategori == 'Produk Dua Kelinci') ? 'checked' : ''; ?>>
            <label for="html">Produk Dua Kelinci</label><br>
            <input type="radio" name="kategori" value="Peralatan Rumah Tangga" <?php echo ($kategori == 'Peralatan Rumah Tangga') ? 'checked' : ''; ?>>
            <label for="css">Peralatan Rumah Tangga</label><br>
            <input type="radio" name="kategori" value="Lainnya" <?php echo ($kategori == 'Lainnya') ? 'checked' : ''; ?>>
            <label for="javascript">Lainnya</label>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Jumlah Stok</label>
        <div class="col-sm-10">
            <input type="number" name="stok" required class="form-control" placeholder="Jumlah Stok" value="<?php echo $stok; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Harga /pcs</label>
        <div class="col-sm-10">
            <input type="hidden" id="harga_raw" name="harga">
            <input type="text" id="harga_display" required class="form-control" placeholder="Harga Produk" value="<?php echo number_format($harga, 0, ',', '.'); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Gambar</label>
        <div class="col-sm-10">
            <input type="file" required name="gambar" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary" name="simpan">Simpan Data</button>
        </div>
    </div>
</form>



  </div>
</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Fungsi untuk menambahkan pemisah ribuan pada mata uang -->
<script>
const hargaDisplay = document.getElementById('harga_display');
const hargaRaw = document.getElementById('harga_raw');

hargaDisplay.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\./g, '').replace(/\D/g, '');
    hargaDisplay.value = new Intl.NumberFormat('id-ID').format(value);
    hargaRaw.value = value;
});

document.querySelector('form').addEventListener('submit', function () {
    let value = hargaDisplay.value.replace(/\./g, '');
    hargaRaw.value = value;
});
</script>
<script>window.jQuery || document.write('<script src="/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script><script src="/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script></body>
</html>
 