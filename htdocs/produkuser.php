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
        <title>Produk Terkonfirmasi</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
    </head>
    <body class="sb-nav-fixed">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Produk Terkonfirmasi</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="request.php">Pengajuan Penjualan</a></li>
                        <li class="breadcrumb-item active">Produk Terkonfirmasi</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>User</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga /pcs</th>
                                            <th>Harga (USER)</th>
                                            <th>Profit Koperasi</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q=$db->prepare("select * from produk_user");
                                        $q->execute();
                                        $no=0;
                                        while($r=$q->fetch()){ $no++;
                                        ?>
                                        <tr>
                                            <td class="tab-col" scope="row"><?php echo $no;?></td>
                                            <td><?php echo $r["user"];?></td>
                                            <td><?php echo $r["namaproduk"];?></td>
                                            <td><?php echo $r["kategori"];?></td>
                                            <td class="tab-col"><?php echo number_format($r["hargajual"],0);?></td>
                                            <td class="tab-col"><?php echo number_format($r["harga"],0);?></td>
                                            <td class="tab-col"><?php echo number_format($r["profit"],0);?></td>
                                            <td class="tab-col"><a class="btn btn-sm btn-success" href="edit_produk_user.php?id=<?php echo $r["id"]; ?>">Edit</a>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>