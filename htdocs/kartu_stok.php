<?php 

//Menghubungkan ke database
require 'config.php';
require 'sessionadmin.php';

// Ambil data filter
$filter_produk = isset($_GET['kode_produk']) ? $_GET['kode_produk'] : '';
$filter_tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

$data = [];

if ($filter_produk || $filter_tanggal_mulai || $filter_tanggal_akhir) {
    // Query untuk mengambil data pemasukan dan pengeluaran hanya jika ada filter
    $query = "SELECT tanggal, nproduk, 
                 SUM(stok_masuk) AS stok_masuk, 
                 SUM(stok_keluar) AS stok_keluar, 
                 MAX(id_pengeluaran) AS id_pengeluaran 
          FROM (
              SELECT tanggal, nproduk, jumlah AS stok_masuk, 0 AS stok_keluar, NULL AS id_pengeluaran FROM masuk
              UNION ALL
              SELECT tanggal, nproduk, 0 AS stok_masuk, jumlah AS stok_keluar, idkeluar AS id_pengeluaran FROM keluar
          ) AS transaksi 
          WHERE 1=1";

    if ($filter_produk) {
        $query .= " AND nproduk = '$filter_produk'";
    }
    
    if ($filter_tanggal_mulai && !$filter_tanggal_akhir) {
        $query .= " AND DATE(tanggal) = DATE('$filter_tanggal_mulai')";
    } elseif ($filter_tanggal_mulai && $filter_tanggal_akhir) {
        $query .= " AND tanggal BETWEEN '$filter_tanggal_mulai 00:00:00' AND '$filter_tanggal_akhir 23:59:59'";
    }

    $query .= " GROUP BY tanggal, nproduk ORDER BY tanggal";
    $data = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
}

$no = 0;

// Ambil saldo awal untuk setiap produk
$produk_saldo = [];
$produk_query = $conn->query("SELECT id, nama_produk, stok FROM produk");
while ($row = $produk_query->fetch_assoc()) {
    $produk_saldo[$row['id']] = [
        'nama_produk' => $row['nama_produk'],
        'saldo' => $row['stok']
    ];
}

$sql = "SELECT id, nama_produk FROM produk"; 
$result = $conn->query($sql);

$adminid = $_SESSION['owner'];

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Kartu Stok Barang Koperasi</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script><script>
            function printTable() {
                var printContents = document.querySelector('.table-responsive').innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = '<html><head><title>Cetak</title></head><body>' + printContents + '</body></html>';
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="dashboard.php">DK Koperasi</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="adminprofile.php"><?php echo $adminid ?></a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="dashboard.php">
                                <div></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="table.php">
                                <div class="fa fa-chart-bar"></div>
                                Stok Produk
                            </a>
                            <a class="nav-link" href="pemasukan.php">
                                <div class="fa fa-chart-bar"></div>
                                Pemasukan
                            </a>
                            <a class="nav-link" href="pengeluaran.php">
                                <div class="fa fa-chart-bar"></div>
                                Pengeluaran
                            </a>
                            <a class="nav-link" href="kartu_stok.php">
                                <div class="fa fa-chart-bar"></div>
                                Kartu Stok
                            </a>
                            <a class="nav-link" href="userview.php">
                                <div class="fa fa-user"></div>
                                Lihat User
                            </a>
                            <a class="nav-link" href="pengelola.php">
                                <div class="fa fa-user"></div>
                                Admin Koperasi
                            </a>
                            <a class="nav-link" href="request.php">
                                <div class="fa fa-address-card"></div>
                                Pengajuan Penjualan
                            </a>
                            <a class="nav-link" href="pemesanan.php">
                                <div class="fa fa-address-card"></div>
                                Pemesanan
                            </a>
                            <a class="nav-link" href="pemesanan_selesai.php">
                                <div class="fa fa-check"></div>
                                Pemesanan Selesai
                            </a>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Laporan Kartu Stok</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Kartu Stok</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                            <form method="GET" class="mb-4">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <label>ID Produk</label>
                                        <input placeholder="Semua" type="text" name="kode_produk" class="form-control" list="produkList" value="<?= htmlspecialchars($filter_produk) ?>">
                                        <datalist id="produkList">
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['id'] ."'>" . $row['nama_produk'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Data tidak tersedia</option>";
                                            }
                                            ?>
                                        </datalist>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Nama Produk</label>
                                        <input type="text" name="nama_produk" class="form-control" id="nama_produk" readonly value="<?= htmlspecialchars($produk_saldo[$filter_produk]['nama_produk'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($filter_tanggal_mulai) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tanggal Akhir</label>
                                        <input type="date" name="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($filter_tanggal_akhir) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block float-right">Cari</button>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label>
                                        <a type="reset" href="kartu_stok.php" class="btn btn-secondary btn-block float-left">Reset</a>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">ID Produk</th>
                                                <th scope="col" style="width: 300px;">Nama Produk</th>
                                                <th scope="col" style="width: 200px;">Tanggal</th>
                                                <th scope="col">Pemasukan</th>
                                                <th scope="col">Pengeluaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php foreach ($data as $row) { 
                                            $no++;
                                            $kode_produk = $row['nproduk'];
                                            $saldo = &$produk_saldo[$kode_produk]['saldo'];
                                            $saldo += $row['stok_masuk'] - $row['stok_keluar'];
                                        ?>
                                        <tr>
                                            <td class="tab-col" scope="row"><?php echo $no;?></td>
                                            <td class="tab-col"><?= $kode_produk ?></td>
                                            <td><?= $produk_saldo[$kode_produk]['nama_produk'] ?></td>
                                            <td class="tab-col"><?= $row['tanggal']?></td>
                                            <td class="tab-col"><?= $row['stok_masuk'] > 0 ? '+' . $row['stok_masuk'] : '-' ?></td>
                                            <td class="tab-col">
                                                <?php if ($row['stok_keluar'] > 0): ?>
                                                    <a href="detail_pengeluaran.php?id=<?= $row['id_pengeluaran'] ?>">-<?= $row['stok_keluar'] ?></a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-dark" onclick="printTable()">
                            Cetak
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="getTableData()">Download</button>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const produkInput = document.querySelector('input[name="kode_produk"]');
                const namaProdukInput = document.getElementById('nama_produk');
                
                produkInput.addEventListener('input', function() {
                    const idProduk = this.value;
                    const produkList = <?= json_encode($produk_saldo) ?>;

                    if (produkList[idProduk]) {
                        namaProdukInput.value = produkList[idProduk].nama_produk;
                    } else {
                        namaProdukInput.value = '';
                    }
                });
            });
        </script>
        <script>
            function getTableData() {
                const table = document.querySelector('.table');
                const rows = table.querySelectorAll('tbody tr');
                const data = [];

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowData = {
                        no: cells[0].innerText,
                        id_produk: cells[1].innerText,
                        nama_produk: cells[2].innerText,
                        tanggal: cells[3].innerText,
                        pemasukan: cells[4].innerText,
                        pengeluaran: cells[5].innerText
                    };
                    data.push(rowData);
                });

                // Kirim data ke halaman ekspor
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'exportkartustok.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tableData';
                input.value = JSON.stringify(data);
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </body>
</html>
 