<?php
include ("func.php");
?>
<html>
<head>
  <title>Cetak Pengeluaran</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
			<h2>Download</h2>
				<div class="data-tables datatable-dark">
				<!-- Awal bagian yang akan dicetak -->
                <table class="table table-bordered" id="mauexport" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">ID Produk</th>
                                                <th scope="col">Nama Produk</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Jumlah</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Penanggung Jawab</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                                        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

                                        $query = "SELECT keluar.nproduk, keluar.tanggal, keluar.jumlah, keluar.keterangan, keluar.pjawab, produk.nama_produk 
                                                FROM keluar 
                                                JOIN produk ON keluar.nproduk = produk.id";

                                        if ($startDate && $endDate) {
                                            $query .= " WHERE keluar.tanggal BETWEEN :startDate AND :endDate";
                                        }

                                        $query .= " ORDER BY keluar.tanggal DESC";

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
                                            <td><?php echo $r["pjawab"]; ?></td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                </table>
                <!-- Akhir bagian yang akan dicetak -->
					<a class="btn btn-secondary" href="pengeluaran.php">Kembali</a>
				</div>
</div>
	
<!-- Convert dan download sebagai dokumen -->
<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv','excel', 'pdf'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>
                                    
                                    