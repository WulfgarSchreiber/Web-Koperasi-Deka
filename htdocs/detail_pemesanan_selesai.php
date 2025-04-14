<?php 
    include ("func.php");

    if (!isset($_GET['idpemesanan'])) {
        echo "ID pemesanan tidak ditemukan!";
        exit;
    }

    $idpemesanan = $_GET['idpemesanan'];
    
    // Ambil data pemesanan
    $q = $db->prepare("SELECT * FROM pesanan_selesai WHERE idpemesanan = ?");
    $q->execute([$idpemesanan]);
    $pemesanan = $q->fetch();

    if (!$pemesanan) {
        echo "Data pemesanan tidak ditemukan!";
        exit;
    }

    // Ambil detail pemesanan dengan nama produk
    $qDetail = $db->prepare("SELECT d.*, p.nama_produk FROM detail_pemesanan d JOIN produk p ON d.idproduk = p.id WHERE d.idpemesanan = ? ORDER BY d.tanggal");
    $qDetail->execute([$idpemesanan]);
    $details = $qDetail->fetchAll();

    // Hitung total harga
    $totalHarga = 0;
    foreach ($details as $detail) {
        $totalHarga += $detail['harga'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Detail Pemesanan</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function printModalBody() {
                // Menyimpan data nota ke database
                saveNotaData();
            }
            
            // Print data yang sudah tersimpan
            function saveNotaData() {
                // Mengambil nilai tunai dan kembalian dari form
                const tunai = document.getElementById('tunai').value;
                const kembalian = document.getElementById('kembalian').value;
                const idpemesanan = '<?php echo $idpemesanan; ?>';
                
                // Memeriksa apakah nilai valid
                if (!tunai || parseInt(tunai) <= 0) {
                    alert('Mohon masukkan jumlah tunai yang valid!');
                    return;
                }
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'save_nota.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onload = function() {
                    if (this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        if (response.success) {
                            // After successful save, print the receipt
                            printReceipt();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('Terjadi kesalahan saat menyimpan data.');
                    }
                };
                
                const data = 'idpemesanan=' + encodeURIComponent(idpemesanan) + 
                             '&tunai=' + encodeURIComponent(tunai) + 
                             '&kembalian=' + encodeURIComponent(kembalian);
                
                xhr.send(data);
            }
            
            function printReceipt() {
                let modalBody = document.querySelector('#myModal .modal-body').cloneNode(true);
                
                // Mengambil bagian modal untuk diprint
                let formElement = modalBody.querySelector('form');
                if (formElement) {
                    formElement.parentNode.removeChild(formElement);
                }
                
                // Mengambil nilai tunai dan kembalian untuk ditampilkan
                const tunaiValue = document.getElementById('tunaiDisplay').value;
                const kembalianValue = document.getElementById('kembalianDisplay').value;
                
                // Menampilkan tunai dan kembalian pada preview
                let tableFooter = modalBody.querySelector('tfoot');
                let tunaiRow = document.createElement('tr');
                tunaiRow.innerHTML = `
                    <th colspan="3" class="text-left">Tunai</th>
                    <th colspan="3">Rp&nbsp;${tunaiValue}</th>
                `;
                tableFooter.appendChild(tunaiRow);
                
                let kembalianRow = document.createElement('tr');
                kembalianRow.innerHTML = `
                    <th colspan="3" class="text-left">Kembalian</th>
                    <th colspan="3">Rp&nbsp;${kembalianValue}</th>
                `;
                tableFooter.appendChild(kembalianRow);
                
                // Elemen dan data yang akan dipirint
                let printWindow = window.open('', '', 'width=600,height=600');
                printWindow.document.write('<html><head><title>&nbsp;</title>');
                printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<div class="container">');
                printWindow.document.write('<h3 class="text-center my-3">Nota Pemesanan</h3><br>');
                printWindow.document.write('<h3 class="text-left my-3">Koperasi Dua Kelinci</h3>');
                printWindow.document.write('<p class="text-left my-3">PT. Dua Kelinci Pati</p>');
                printWindow.document.write('<p class="text-left my-3">Jl. Raya Pati-Kudus KM 6, Pati, 59163</p>');
                printWindow.document.write(modalBody.innerHTML);
                printWindow.document.write('<h5 class="text-center my-3">--Terima Kasih--</h5>');
                printWindow.document.write('<h5 class="text-center my-3">---Selamat Belanja Kembali---</h5>');
                printWindow.document.write('</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
            
            // Kalkulasi otomatis untuk menghitung kembalian
            function calculateKembalian() {
                let tunai = parseInt(document.getElementById('tunai').value) || 0;
                let totalHarga = <?php echo $totalHarga; ?>;
                let kembalian = tunai - totalHarga;
                
                if (kembalian < 0) {
                    kembalian = 0;
                    document.getElementById('kembalianDisplay').value = 0;
                    document.getElementById('kembalian').value = 0;
                } else {
                    document.getElementById('kembalianDisplay').value = formatRupiah(kembalian.toString());
                    document.getElementById('kembalian').value = kembalian;
                }
            }
        </script>
    </head>

    <body class="sb-nav-fixed">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Detail Pemesanan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="pemesanan_selesai.php">Pemesanan Selesai</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2>Detail Pemesanan #<?php echo $idpemesanan; ?></h2>
                            <p>Tanggal: <?php echo $pemesanan['tanggal']; ?></p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        foreach ($details as $detail) { ?>
                                        <tr>
                                            <td class="tab-col"><?php echo $no++;?></td>
                                            <td class="tab-col"><?php echo $detail['idproduk']; ?></td>
                                            <td><?php echo $detail['nama_produk']; ?></td>
                                            <td class="tab-col"><?php echo $detail['jumlah']; ?></td>
                                            <td class="tab-col">Rp<?php echo number_format($detail['harga']); ?></td>
                                            <td class="tab-col"><?php echo $detail['tanggal']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-left">Total Harga</th>
                                            <th colspan="5">Rp&nbsp;<?php echo number_format($totalHarga); ?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-left"><button class="btn btn-sm btn-dark" data-target="#myModal" data-toggle="modal">Cetak Nota</button></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
    <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
            <h3>Nota Pemesanan</h3>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <div class="modal-header">
        </div>
        <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($details as $detail) { ?>
                <tr>
                    <td class="tab-col"><?php echo $no++;?></td>
                    <td><?php echo $detail['nama_produk']; ?></td>
                    <td class="tab-col"><?php echo $detail['jumlah']; ?></td>
                    <td class="tab-col">Rp<?php echo number_format($detail['harga']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-left">Total Harga</th>
                    <th colspan="3">Rp&nbsp;<?php echo number_format($totalHarga); ?></th>
                </tr>
            </tfoot>
        </table>
        <form id="notaForm">
            <input type="hidden" name="idpemesanan" value="<?php echo $idpemesanan; ?>">
            <div class="form-group row">
                <label for="tunai" class="col-sm-2 col-form-label">Tunai</label>
                <div class="col-sm-10">
                    <input type="text" id="tunaiDisplay" class="form-control" placeholder="Tunai" required>
                    <input type="hidden" id="tunai" name="tunai">
                </div>
            </div>
            <div class="form-group row">
                <label for="kembalian" class="col-sm-2 col-form-label">Kembali</label>
                <div class="col-sm-10">
                    <input type="text" id="kembalianDisplay" class="form-control" placeholder="Kembalian" readonly>
                    <input type="hidden" id="kembalian" name="kembalian">
                </div>
            </div>
        </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="printModalBody()">Cetak</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Menambah pemisah ribuan pada harga -->
  <script>
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.getElementById('tunaiDisplay').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\./g, '').replace(/\D/g, '');
        document.getElementById('tunaiDisplay').value = formatRupiah(value);
        document.getElementById('tunai').value = value;
        calculateKembalian();
    });
  </script>
</html>