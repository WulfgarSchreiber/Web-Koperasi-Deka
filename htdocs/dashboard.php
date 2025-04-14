<?php 
require "config.php";
include ("func.php");
require 'sessionadmin.php';

// Query untuk mendapatkan total pemasukan produk
$query_pemasukan = "SELECT SUM(jumlah) AS total_pemasukan FROM masuk";
$result_pemasukan = mysqli_query($conn, $query_pemasukan);
$row_pemasukan = mysqli_fetch_assoc($result_pemasukan);
$total_pemasukan = $row_pemasukan['total_pemasukan'] ?? 0;

// Query untuk mendapatkan total pengeluaran produk
$query_pengeluaran = "SELECT SUM(jumlah) AS total_pengeluaran FROM keluar";
$result_pengeluaran = mysqli_query($conn, $query_pengeluaran);
$row_pengeluaran = mysqli_fetch_assoc($result_pengeluaran);
$total_pengeluaran = $row_pengeluaran['total_pengeluaran'] ?? 0;

// Query untuk mendapatkan total pemesanan
$query_pemesanan = "SELECT COUNT(*) AS total_pemesanan FROM pemesanan";
$result_pemesanan = mysqli_query($conn, $query_pemesanan);
$row_pemesanan = mysqli_fetch_assoc($result_pemesanan);
$total_pemesanan = $row_pemesanan['total_pemesanan'] ?? 0;

// Query untuk mendapatkan total pengajuan produk user
$query_pengajuan = "SELECT COUNT(*) AS total_pengajuan FROM jual";
$result_pengajuan = mysqli_query($conn, $query_pengajuan);
$row_pengajuan = mysqli_fetch_assoc($result_pengajuan);
$total_pengajuan = $row_pengajuan['total_pengajuan'] ?? 0;

// Query untuk mendapatkan total jumlah produk yang ada
$query_total_produk = "SELECT COUNT(*) AS total_produk FROM produk";
$result_total_produk = mysqli_query($conn, $query_total_produk);
$row_total_produk = mysqli_fetch_assoc($result_total_produk);
$total_produk = $row_total_produk['total_produk'] ?? 0;

// Query untuk mendapatkan total stok produk keseluruhan
$query_stok = "SELECT SUM(stok) AS total_stok FROM produk";
$result_stok = mysqli_query($conn, $query_stok);
$row_stok = mysqli_fetch_assoc($result_stok);
$total_stok = $row_stok['total_stok'] ?? 0;

// Query untuk mendapatkan total user
$query_user = "SELECT COUNT(*) AS total_user FROM login";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);
$total_user = $row_user['total_user'] ?? 0;

// Query untuk mendapatkan total admin/pengelola
$query_admin = "SELECT COUNT(*) AS total_admin FROM admin";
$result_admin = mysqli_query($conn, $query_admin);
$row_admin = mysqli_fetch_assoc($result_admin);
$total_admin = $row_admin['total_admin'] ?? 0;

$username = $_SESSION['owner'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard</title>
        <link rel="stylesheet" href="css/adminlte.css" />
        <link href="css/styles.css" rel="stylesheet" />
        <link
          rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
          crossorigin="anonymous"
        />
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
                        <a class="dropdown-item" href="adminprofile.php"><?php echo $username ?></a>
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
            <main class="app-main">
                  <!--begin::App Content Header-->
                  <div class="app-content-header">
                    <!--begin::Container-->
                    <div class="container-fluid">
                      <!--begin::Row-->
                      <div class="row">
                        <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
                      </div>
                      <!--end::Row-->
                    </div>
                    <!--end::Container-->
                  </div>
                  <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">
                      <!-- Info boxes -->
                      <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                          <div class="info-box">
                            <span class="info-box-icon text-bg-light shadow-sm">
                              <a href="pemasukan.php" class="bi bi-calendar-plus"></a>
                            </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Produk Masuk</span>
                              <span class="info-box-number">
                                <?php echo number_format($total_pemasukan); ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                          <div class="info-box">
                            <span class="info-box-icon text-bg-light shadow-sm">
                              <a href="pengeluaran.php" class="bi bi-calendar-minus"></a>
                            </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Produk Keluar</span>
                              <span class="info-box-number">
                                <?php echo number_format($total_pengeluaran); ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <!-- fix for small devices only -->
                        <!-- <div class="clearfix hidden-md-up"></div> -->
                        <div class="col-12 col-sm-6 col-md-3">
                          <div class="info-box">
                            <span class="info-box-icon text-bg-light shadow-sm">
                              <a href="pemesanan.php" class="bi bi-cart-fill"></a>
                            </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Pemesanan</span>
                              <span class="info-box-number">
                                <?php echo $total_pemesanan; ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                          <div class="info-box">
                            <span class="info-box-icon text-bg-light shadow-sm">
                              <a href="request.php" class="bi bi-card-checklist"></a>
                            </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Pengajuan User</span>
                              <span class="info-box-number">
                                <?php echo $total_pengajuan; ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                      <!--begin::Row-->
                      <div class="row">
                        <!-- Start col -->
                        <div class="col-md-8">
                          <!--begin::Row-->
                          <div class="row g-4 mb-4">
                            <div class="col-md-6">
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">       
                              <!-- /.card -->
                            </div>
                            <!-- /.col -->
                          </div>
                          <!--end::Row-->
                          <!--begin::Latest Order Widget-->
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Pesanan Terkini</h3>
                              <div class="card-tools">
                              </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                              <div class="table-responsive">
                                <table class="table table-bordered m-0" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th scope="col">User</th>
                                      <th scope="col">Total Produk</th>
                                      <th scope="col">Harga</th>
                                      <th scope="col">Tanggal</th>
                                      <th scope="col">Aksi</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  <?php
                                        $q=$db->prepare("select * from pemesanan order by tanggal desc LIMIT 5");
                                        $q->execute();
                                        $no=0;
                                        while($r=$q->fetch()){ $no++;
                                  ?>
                                    <tr>
                                            <td><?php echo $r["userid"];?></td>
                                            <td class="tab-col"><?php echo $r["totalproduk"];?></td>
                                            <td class="tab-col">Rp <?php echo number_format($r["harga"]);?></td>
                                            <td class="tab-col status"><?php echo $r["tanggal"];?></td>
                                            <td class="tab-col">
                                            <?php if ($r["status"] !== "Lunas") { ?>
                                                <a class="btn btn-success float-center btn-sm" onclick="return confirm('Konfirmasi pesanan sekarang juga?');" href="konfirmasi_pemesanan.php?idpemesanan=<?php echo $r['idpemesanan']; ?>">Konfirmasi</a>
                                            <?php } ?>
                                            <a class="btn btn-dark float-center btn-sm" href="detail_pemesanan.php?idpemesanan=<?php echo $r['idpemesanan']; ?>">Detail</a>
                                            
                                            </td>
                                    </tr>
                                  <?php } ?>  
                                  </tbody>
                                </table>
                              </div>
                              <!-- /.table-responsive -->
                            </div>
                          </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                          <!-- Info Boxes Style 2 -->
                          <div class="info-box mb-3 text-bg-warning">
                            <span class="info-box-icon"> <i class="bi bi-bar-chart-line-fill"></i> </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Stok</span>
                              <span class="info-box-number"><?php echo number_format($total_stok); ?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                          <div class="info-box mb-3 text-bg-success">
                            <span class="info-box-icon"> <i class="bi bi-people-fill"></i> </span>
                            <div class="info-box-content">
                              <span class="info-box-text">User</span>
                              <span class="info-box-number"><?php echo $total_user; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                          <div class="info-box mb-3 text-bg-danger">
                            <span class="info-box-icon"> <i class="bi bi-people-fill"></i> </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Pengelola</span>
                              <span class="info-box-number"><?php echo $total_admin; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <div class="info-box mb-3 text-bg-primary">
                            <span class="info-box-icon"> <i class="bi bi-list-ul"></i> </span>
                            <div class="info-box-content">
                              <span class="info-box-text">Produk</span>
                              <span class="info-box-number"><?php echo $total_produk; ?> Produk</span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                          <!-- /.info-box -->
                          <!-- /.card -->
                        </div>
                            <div class="row" style="margin-top: 20px">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><h3 class="card-title">Penjualan Perbulan</h3></div>
                                        <div class="card-body" style="margin-top: 20px;"> <!-- Tambahkan margin-top -->
                                            <canvas id="revenue-chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><h3 class="card-title">Penjualan Harian (7 Hari Terakhir)</h3></div>
                                        <div class="card-body" style="margin-top: 20px;"> <!-- Tambahkan margin-top -->
                                            <canvas id="daily-sales-chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- /.col -->
                      </div>
                      <!--end::Row-->
                    </div>
                    <!--end::Container-->
                  </div>
                  <!--end::App Content-->
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("get_sales_data.php")
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById("revenue-chart").getContext("2d");
                    new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                            datasets: [{
                                label: "Penjualan",
                                backgroundColor: "rgb(43, 170, 255)",
                                borderColor: "rgb(0, 255, 221)",
                                borderWidth: 1,
                                data: data
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                })
                .catch(error => console.error("Error:", error));

                fetch("daily_sales.php") // Ambil data dari server
                    .then(response => response.json())
                    .then(data => {
                        const days = data.map(item => item.hari);
                        const totals = data.map(item => item.total);
                        
                        const ctx = document.getElementById('daily-sales-chart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: days,
                                datasets: [{
                                    label: 'Barang Terjual',
                                    data: totals,
                                    borderColor: 'rgb(0, 255, 42)',
                                    backgroundColor: 'rgba(72, 255, 0, 0.33)',
                                    borderWidth: 2,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'top' }
                                },
                                scales: {
                                    x: { title: { display: true, text: 'Hari' } },
                                    y: { title: { display: true, text: 'Jumlah Terjual' }, beginAtZero: true }
                                }
                            }
                        });
                    });
            });
        </script>

    </body>
</html>
 