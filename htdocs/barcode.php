<?php
// Menjalankan library QRCode
require 'vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Menghubungkan ke database
include ("func.php");

// Mengambail data pemesanan
$idpemesanan = isset($_GET['idpemesanan']) ? $_GET['idpemesanan'] : '';
$q = $db->prepare("SELECT * FROM pemesanan WHERE idpemesanan = ?");
$q->execute([$idpemesanan]);
$r = $q->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Scan Pemesanan</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Scan Pemesanan</h3>
                                </div>
                                <div class="card-body text-center">
                                    <!-- Generate QR Code yang sebelumnya sudah dijalankan -->
                                    <?php if ($r) {
                                        $url = "http://end-oghoren.liveblog365.com/detail_pemesanan.php?idpemesanan=" . $r['idpemesanan'];
                                        $qrCode = QrCode::create($url);
                                        $writer = new PngWriter();
                                        $result = $writer->write($qrCode);
                                        $qrImage = $result->getDataUri();
                                    ?>
                                        <img src="<?php echo $qrImage; ?>" alt="QR Code" class="img-fluid" width="250" height="250">
                                        <p class="mt-3">SCAN ID PEMESANAN: <?php echo $r['idpemesanan']; ?></p>
                                    <?php } else { ?>
                                        <p class="text-danger">ID Pemesanan tidak ditemukan.</p>
                                    <?php } ?>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="myorder.php">Kembali</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
