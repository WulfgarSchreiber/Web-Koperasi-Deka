<?php

require 'config.php';

$data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableData'])) {
    $data = json_decode($_POST['tableData'], true);
}

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
                                                <th scope="col" style="width: 300px;">Nama Produk</th>
                                                <th scope="col" style="width: 200px;">Tanggal</th>
                                                <th scope="col">Pemasukan</th>
                                                <th scope="col">Pengeluaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['no']) ?></td>
                                            <td><?= htmlspecialchars($row['id_produk']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                            <td><?= htmlspecialchars($row['pemasukan']) ?></td>
                                            <td><?= htmlspecialchars($row['pengeluaran']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <!-- Akhir bagian yang akan dicetak -->
					<a class="btn btn-secondary" href="kartu_stok.php">Kembali</a>
				</div>
</div>
	
<!-- Convert dan download sebagai file dokumen -->
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
                                    
                                    