<?php 
    // Menghubungkan ke database
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
        <title>List User</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
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
                        <h1 class="mt-4">List User</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Lihat User</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">No. Telepon</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $q=$db->prepare("select * from login order by username desc");
                                        $q->execute();
                                        $no=0;
                                        while($r=$q->fetch()){ $no++;
                                        ?>
                                        <tr>
                                            <td class="tab-col" scope="row"><?php echo $no;?></td>
                                            <td><?php echo $r["nama"];?></td>
                                            <td><?php echo $r["username"];?></td>
                                            <td class="tab-col"><?php echo $r["telepon"];?></td>
                                            <td class="tab-col"><a class="btn btn-success" href="edituser.php?action=ubah&id=<?php echo $r["username"]; ?>">Edit</a></td>
                                        </tr>
                                        <?php } ?>

                                        </tbody>
                                    </table>
                                    <br><button type="button" class="btn btn-sm btn-dark" onclick="window.print()">
                                            Cetak
                                    </button>
                                </div>
                            </div>
                        </div>
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
    </body>
</html>
