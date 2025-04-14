<?php
    
    require 'config.php';
    include ("func.php");

    // Ambil username admin dari sesi
    $adminid = $_SESSION['owner'];

    // Ambil nama lengkap admin berdasarkan username
    $queryAdmin = $db->prepare("SELECT nama FROM admin WHERE adminname = :username");
    $queryAdmin->bindParam(':username', $adminid);
    $queryAdmin->execute();
    $adminData = $queryAdmin->fetch();
    $adminName = $adminData ? $adminData['nama'] : 'Tidak Diketahui';


    //Check jika ada id yang dibawa
    if(!empty($_GET["idkeluar"])){
      //jika ada, cek idnya di database
      $id = $_GET["idkeluar"];
      $q=$db->prepare("select * from keluar where nproduk=?");
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
      $button="bkeluar";
    }

    // Setelah data form disimpan maka stok produk akan dikurangi
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jumlah = (int)$_POST['jumlah'];
    
        $sql = "SELECT * FROM produk WHERE nama_produk='$nama_produk'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newJumlah = ($type == "in") ? $row['stok'] + $jumlah : $row['jumlah'] - $stok;
            $updateSQL = "UPDATE produk SET jumlah=$newJumlah WHERE nama_produk='$nama_produk'";
            $conn->query($updateSQL);
        } else {
            $insertSQL = "INSERT INTO produk (nama_produk, stok) VALUES ('$nama_produk', $stok)";
            $conn->query($insertSQL);
        }
    }

    $sql = "SELECT id, nama_produk, stok FROM produk"; // Ganti dengan nama tabel dan kolom yang sesuai
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
    <title>Input Pengeluaran Produk</title>

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
  <a class="navbar-brand" href="#">Pengeluaran Stok</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="pengeluaran.php">Kembali <span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
    <div class="jumbotron">
        <h1>Data Barang</h1>
        <br>
        <form method="POST" action="form.action.php">
            <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ID Produk</label>
                    <div class="col-sm-10">
                        <input type="text" name="nproduk" id="produk" class="form-control" placeholder="ID Produk" list="produkList" oninput="autofill(this.value)">
                        <datalist id="produkList">
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>" data-nama="<?php echo $row['nama_produk']; ?>" data-stok="<?php echo $row['stok']; ?>">
                                    <?php echo $row['nama_produk']; ?>
                                </option>
                            <?php } ?>
                        </datalist>
                    </div>
            </div>
            <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Produk</label>
                    <div class="col-sm-10">
                        <input type="text" id="nama_produk" class="form-control" readonly>
                    </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Stok Tersedia</label>
                <div class="col-sm-10">
                    <input type="text" id="stok" class="form-control" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jumlah</label>
                <div class="col-sm-10">
                    <input type="number" name="jumlah" id="jumlah" required class="form-control" placeholder="Jumlah" min="1">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Keterangan</label>
                <div class="col-sm-10">
                    <select name="keterangan" required class="form-control">
                        <option value="">-</option>
                        <option value="Barang Terjual">Barang Terjual</option>
                        <option value="Barang Rusak">Barang Rusak</option>
                        <option value="Barang Hilang">Barang Hilang</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Penanggung Jawab</label>
                    <div class="col-sm-10">
                        <input type="text" id="pjawab" name="pjawab" placeholder="Penanggung Jawab" class="form-control" value="<?php echo $adminName; ?>" readonly>
                    </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" id="submit" class="btn btn-primary" name="<?php echo $button; ?>" value="in">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
        function autofill(id) {
            let stokInput = document.getElementById("stok");
            let namaProdukInput = document.getElementById("nama_produk");
            let jumlahInput = document.getElementById("jumlah");
            let submitBtn = document.getElementById("submit");

            let option = document.querySelector(`#produkList option[value='${id}']`);
            if (option) {
                let stok = option.getAttribute("data-stok");
                let namaProduk = option.getAttribute("data-nama");
                stokInput.value = stok;
                namaProdukInput.value = namaProduk;

                if (stok <= 0) {
                    jumlahInput.disabled = true;
                    submitBtn.disabled = true;
                } else {
                    jumlahInput.disabled = false;
                    submitBtn.disabled = false;
                    jumlahInput.max = stok;
                }
            } else {
                stokInput.value = "";
                namaProdukInput.value = "";
                jumlahInput.disabled = true;
                submitBtn.disabled = true;
            }
        }
</script>
</body>
</html>
 