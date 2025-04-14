<?php 
    include ("func.php");
    require 'sessionadmin.php';

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
        <title>Pemasukan Produk</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script>
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
                        <h1 class="mt-4">Pemasukan Produk</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pemasukan Produk</li>
                        </ol>
                        <div class="card mb-4">
                            <a type="button" class="btn btn-primary" href="masuk.php">Tambah Pemasukan</a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Filter Tanggal</h5>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="startDate">Tanggal Mulai</label>
                                                <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] : ''; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="endDate">Tanggal Akhir</label>
                                                <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] : ''; ?>">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="pemasukan.php" class="btn btn-secondary">Clear Filter</a>
                                    </form>
                                </div>
                            </div>
                                <div class="table-responsive">
                                <button class="btn btn-sm btn-primary float-right" onclick="refreshPage()">Refresh</button>
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">ID Produk</th>
                                                <th scope="col">Nama Produk</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Jumlah</th>
                                                <th scope="col">Penyetor</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                                        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

                                        $query = "SELECT masuk.nproduk, masuk.tanggal, masuk.jumlah, masuk.keterangan, produk.nama_produk 
                                                FROM masuk 
                                                JOIN produk ON masuk.nproduk = produk.id";

                                        if ($startDate && $endDate) {
                                            $query .= " WHERE masuk.tanggal BETWEEN :startDate AND :endDate";
                                        }

                                        $query .= " ORDER BY masuk.tanggal DESC";

                                        $q = $db->prepare($query);

                                        if ($startDate && $endDate) {
                                            $q->bindParam(':startDate', $startDate);
                                            $q->bindParam(':endDate', $endDate);
                                        }

                                        $q->execute();
                                        $no = 0;
                                        while ($r = $q->fetch()) { 
                                            $no++;
                                        ?>
                                        <tr>
                                            <td class="tab-col" scope="row"><?php echo $no; ?></td>
                                            <td class="tab-col"><?php echo $r["nproduk"]; ?></td>
                                            <td><?php echo $r["nama_produk"]; ?></td>
                                            <td class="tab-col"><?php echo $r["tanggal"]; ?></td>
                                            <td class="tab-col"><?php echo $r["jumlah"]; ?></td>
                                            <td><?php echo $r["keterangan"]; ?></td>
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
                        <a class="btn btn-sm btn-primary" href="exportpemasukan.php">Download</a>
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
        <script>
            function refreshPage() {
                location.reload();
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
