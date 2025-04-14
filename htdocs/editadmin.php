<?php
// Koneksi ke database
require 'func.php';

if(!empty($_GET["id"])){
  //Cek nama admin pada database admin
  $id = $_GET["id"];
  $q=$db->prepare("select * from admin where adminname=?");
  $q->execute([$id]);

  //Jika ditemukan di database, maka tampilkan datanya
  if($q->rowCount()> 0){
    $r= $q->fetch();
    
    $button="ubah";
  
  //Jika tidak ditemukan, maka kembali ke tabel dg pesan
  }else{
    $_SESSION["notif"]="Maaf, Data tidak ditemukan";
    header("Location: pengelola.php");
    die();
  }

}

// Pastikan ada data admin yang dikirim
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data admin berdasarkan username
    $q = $db->prepare("SELECT * FROM admin WHERE adminname = ?");
    $q->execute([$id]);
    $admin = $q->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo "Admin tidak ditemukan!";
        exit;
    }
} else {
    echo "Admin Produk tidak diberikan!";
    exit;
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
    <title>Edit Data Admin</title>

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
  <a class="navbar-brand" href="#">Edit Data Admin</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="pengelola.php">Kembali <span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
  <div class="jumbotron">
    <h1>Edit Data User</h1>
    <br>

    <form method="POST" action="proses_edit_admin.php" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $admin['id']; ?>">
      
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="Username" name="adminname" required value="<?= $admin['adminname']; ?>" readonly>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="Nama" name="nama" required value="<?= $admin['nama']; ?>">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Telepon</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" placeholder="Telepon" name="telepon" required value="<?= $admin['telepon']; ?>">
        </div>
      </div> 
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
          <input type="password" name="password" id="Mypassword" required class="form-control" placeholder="Password" value="<?= $admin['password']; ?>">
          <input type="checkbox" onclick="myFunction()">Lihat Password
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-10">
          <button type="submit" class="btn btn-primary" name="ubah">Simpan Data</button>
        </div>
      </div>
    </form>
  </div>
</main>
<!-- Script untuk menyembunyikan atau menampilkan password -->
<script>
function myFunction() {
  var x = document.getElementById("Mypassword");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script><script src="/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script></body>
</html>