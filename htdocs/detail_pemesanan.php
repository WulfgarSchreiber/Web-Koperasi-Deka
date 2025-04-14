<?php 
    include ("func.php");
    require 'sessionadmin.php';

    if (!isset($_GET['idpemesanan'])) {
        echo "ID pemesanan tidak ditemukan!";
        exit;
    }
    
    $idpemesanan = $_GET['idpemesanan'];
    
    // Ambil data pemesanan
    $q = $db->prepare("SELECT * FROM pemesanan WHERE idpemesanan = ?");
    $q->execute([$idpemesanan]);
    $pemesanan = $q->fetch();
    
    if (!$pemesanan) {
        echo "Data pemesanan tidak ditemukan!";
        exit;
    }
    
    // Ambil detail pemesanan dan periksa jumlah
    $qDetail = $db->prepare("SELECT d.*, p.nama_produk, p.stok, p.harga AS harga_satuan FROM detail_pemesanan d 
                             JOIN produk p ON d.idproduk = p.id WHERE d.idpemesanan = ? ORDER BY d.tanggal");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script untuk mengatur tombol pada produk -->
    <script> 
            function konfirmasiPesanan(button, id) {
                if (confirm('Konfirmasi pesanan sekarang juga?')) {
                    fetch(`proses_pemesanan.php?action=konfirmasi&id=${id}`)
                        .then(response => response.text())
                        .then(data => {
                            // Simpan ID produk yang sudah dikonfirmasi di localStorage
                            localStorage.setItem(`confirmed_${id}`, true);
                            button.closest('td').innerHTML = '<span class="badge badge-success">Terkonfirmasi</span>';
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            function checkConfirmedProducts() {
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const id = row.querySelector('td:nth-child(2)').textContent; // Ambil ID produk dari kolom kedua
                    if (localStorage.getItem(`confirmed_${id}`)) {
                        // Jika produk sudah dikonfirmasi, sembunyikan tombol
                        const actionCell = row.querySelector('td:last-child');
                        actionCell.innerHTML = '<span class="badge badge-success">Terkonfirmasi</span>';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', checkConfirmedProducts);
    </script>
</head>
<body class="sb-nav-fixed">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Detail Pemesanan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="pemesanan.php">Pemesanan</a></li>
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
                                        <th>Tersedia</th>
                                        <th>Harga</th>
                                        <th>Tanggal</th>
                                        <th></th>
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
                                        <td class="tab-col"><?php echo $detail['stok']; ?></td>
                                        <td class="tab-col">Rp&nbsp;<?php echo number_format($detail['harga']); ?></td>
                                        <td class="tab-col"><?php echo $detail['tanggal']; ?></td>
                                        <td class="tab-col">
                                            <?php if ($detail['jumlah'] <= $detail['stok']) { ?>
                                                <?php if ($detail['status'] !== 'Terkonfirmasi') { ?>
                                                    <button class="btn btn-success btn-sm" onclick="konfirmasiPesanan(this, <?php echo $detail['id']; ?>)">Konfirmasi</button>
                                                    <a class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?');" href="hapus_produk.php?id=<?php echo $detail['id']; ?>&idpemesanan=<?php echo $idpemesanan; ?>">Hapus</a>
                                                <?php } else { ?>
                                                    <span class="badge badge-success">Terkonfirmasi</span>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">Jumlah Melebihi Stok</span>
                                                <a class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?');" href="hapus_produk.php?id=<?php echo $detail['id']; ?>&idpemesanan=<?php echo $idpemesanan; ?>">Hapus</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-left">Total Harga</th>
                                            <th>Rp&nbsp;<?php echo number_format($totalHarga); ?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-left">Pindahkan ke Pemesanan Selesai</th>
                                            <th>
                                                <a class="btn btn-primary btn-sm" onclick="return confirm('Konfirmasi pemesanan ini?');" href="konfirmasi_detail_pemesanan.php?idpemesanan=<?php echo $detail['idpemesanan']; ?>">Konfirmasi Pemesanan</a>
                                                <a class="btn btn-danger float-center btn-sm" role="button" onclick="return confirm('Anda yakin ingin mengahpus data ini?');" href="form.action.php?action=hapuspemesanan&idpemesanan=<?php echo $detail['idpemesanan']; ?>">Hapus</a>
                                            </th> 
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
<!-- Modal Edit Jumlah Produk -->
<script>
    function bukaModalEdit(idProduk, jumlah, namaProduk) {
        document.getElementById('editIdProduk').value = idProduk;
        document.getElementById('editJumlah').value = jumlah;
        document.getElementById('editNamaProduk').value = namaProduk;
        $('#editJumlahModal').modal('show');
    }

    function simpanPerubahan() {
        const idProduk = document.getElementById('editIdProduk').value;
        const jumlah = document.getElementById('editJumlah').value;
        fetch(`edit_jumlah.php?action=edit_jumlah&id=${idProduk}&jumlah=${jumlah}`, {
            method: 'POST'
        })
        .then(response => response.text())
        .then(data => {
            $('#editJumlahModal').modal('hide');
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const idProduk = row.querySelector('td:nth-child(2)').textContent;
            const namaProduk = row.querySelector('td:nth-child(3)').textContent;
            const jumlah = row.querySelector('td:nth-child(4)').textContent;
            const actionCell = row.querySelector('td:last-child');
            const editButton = document.createElement('button');
            editButton.className = 'btn btn-warning btn-sm';
            editButton.textContent = 'Edit';
            editButton.onclick = () => bukaModalEdit(idProduk, jumlah, namaProduk);
            actionCell.appendChild(editButton);
        });
    });
</script>
<script>
    function sesuaikanStok(button, id, stok, hargaSatuan) {
        if (confirm('Sesuaikan jumlah pesanan dengan stok yang tersedia?')) {
            fetch(`proses_pemesanan.php?action=sesuaikan&id=${id}&stok=${stok}&hargaSatuan=${hargaSatuan}`)
                .then(response => response.text())
                .then(data => {
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
        }
    }
</script>
<div class="modal fade" id="editJumlahModal" tabindex="-1" aria-labelledby="editJumlahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJumlahModalLabel">Edit Jumlah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editJumlahForm">
                    <input type="hidden" id="editIdProduk" name="idProduk">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" id="editNamaProduk" name="nama_produk" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editJumlah">Jumlah</label>
                        <input type="number" class="form-control" id="editJumlah" name="jumlah" min="1" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanPerubahan()">Simpan</button>
            </div>
        </div>
    </div>
</div>

</html>
 