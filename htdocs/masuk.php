<?php
    
    require 'config.php';

    //Check jika ada id yang dibawa
    if(!empty($_GET["idmasuk"])){
      //jika ada, cek idnya di database
      $id = $_GET["idmasuk"];
      $q=$db->prepare("select * from masuk where nproduk=?");
      $q->execute([$id]);

      //jika ditemukan di database, maka tampilkan datanya
      if($q->rowCount()> 0){
        $r= $q->fetch();
        $nproduk=$r["nproduk"];
        $keterangan=$r["keterangan"];
        $jumlah=$r["jumlah"];
      
      //jika tidak ditemukan, maka kembali ke tabel dg pesan
      }else{
        $_SESSION["notif"]="Maaf, Data tidak ditemukan";
        header("Location: table.php");
        die();
      }
    
    //jika tidak membawa id, berarti itu perintah tambah / simpan
    }else{
      $nproduk="";
      $keterangan="";
      $button="bmasuk";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jumlah = (int)$_POST['jumlah'];
    
        $sql = "SELECT * FROM produk WHERE nama_produk='$nama_produk'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Setelah data dipindah ke tabel masuk, maka tambahkan jumlah stok
            $newJumlah = ($type == "in") ? $row['stok'] + $jumlah : $row['jumlah'] - $stok;
            $updateSQL = "UPDATE produk SET jumlah=$newJumlah WHERE nama_produk='$nama_produk'";
            $conn->query($updateSQL);
        } else {
            $insertSQL = "INSERT INTO produk (nama_produk, stok) VALUES ('$nama_produk', $stok)";
            $conn->query($insertSQL);
        }
    }

    $sql = "SELECT id, nama_produk FROM produk"; // Ganti dengan nama tabel dan kolom yang sesuai
    $result = $conn->query($sql);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Input Pemasukan Produk</title>

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
  <a class="navbar-brand" href="#">Pemasukan Stok</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="pemasukan.php">Kembali <span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
  <div class="jumbotron">
    <h1>Data Barang</h1>
    <br>
    <form method="POST" action="form.action.php">
                <input type="hidden" name="id" value="<?php echo $idmasuk; ?>">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">ID Produk</label>
                    <div class="col-sm-10">
                        <input type="text" name="nproduk" id="produk" class="form-control" placeholder="ID Produk" list="produkList" oninput="autofill(this.value)">
                        <datalist id="produkList">
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "' data-nama='" . $row['nama_produk'] . "'>" . $row['nama_produk'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Data tidak tersedia</option>";
                            }
                            ?>
                        </datalist>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Nama Produk</label>
                    <div class="col-sm-10">
                        <input type="text" id="nama_produk" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="number" name="jumlah" required class="form-control" placeholder="Jumlah" value="<?php echo $jumlah; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Penyetor</label>
                    <div class="col-sm-10">
                        <input type="text" name="keterangan" required class="form-control" placeholder="Penyetor" value="<?php echo $keterangan; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary" name="<?php echo $button; ?>" value="in">Simpan Data</button>
                    </div>
                </div>
    </form>
  </div>
</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script>
        function autofill(id) {
            let namaProdukInput = document.getElementById("nama_produk");
            let jumlahInput = document.getElementById("jumlah");
            let submitBtn = document.getElementById("submit");

            let option = document.querySelector(`#produkList option[value='${id}']`);
            if (option) {
                let namaProduk = option.getAttribute("data-nama");
                namaProdukInput.value = namaProduk;
            } else {
                namaProdukInput.value = "";
            }
        }
</script>
      <script>window.jQuery || document.write('<script src="/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script><script src="/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script></body>
</html>
  