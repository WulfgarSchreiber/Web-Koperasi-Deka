<?php

include 'func.php'; // Menghubungkan ke database database

// Pastikan pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['admin'];
?>
<main>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pesanan Saya</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid">
    <h1 class="mt-4">Pesanan Saya</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">&larr; Kembali</a></li>
    </ol>

    <h3>Pesanan Aktif</h3>
    <div class="accordion" id="ordersAccordion">
        <?php
        $q = $db->prepare("SELECT * FROM pemesanan WHERE userid=? ORDER BY tanggal DESC");
        $q->execute([$user_id]);
        $no = 0;
        if ($q->rowCount() > 0) {
            while ($r = $q->fetch()) { $no++;
        ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $no; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $no; ?>" aria-expanded="false" aria-controls="collapse<?php echo $no; ?>">
                    #<?php echo $no; ?> - Tanggal: <?php echo $r["tanggal"]; ?> - Status: <strong>&nbsp;<?php echo $r["status"]; ?></strong>
                </button>
            </h2>
            <div id="collapse<?php echo $no; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $no; ?>" data-bs-parent="#ordersAccordion">
                <div class="accordion-body">
                    <?php if ($r["status"] !== "Lunas") { ?>
                        <a class="btn btn-success btn-sm" href="pay.php">Bayar Sekarang</a>
                    <?php } ?>
                    <a class="btn btn-dark btn-sm" href="invoice.php?idpemesanan=<?php echo $r['idpemesanan']; ?>">Lihat</a>
                    <a class="btn btn-dark btn-sm" href="barcode.php?idpemesanan=<?php echo $r['idpemesanan']; ?>">Tampilkan Barcode</a>
                    <?php if ($r["status"] !== "Lunas") { ?>
                        <a class="btn btn-danger btn-sm" role="button" onclick="return confirm('Anda yakin ingin menghapus data ini?');" href="form.action.php?action=hapuspemesananuser&idpemesanan=<?php echo $r["idpemesanan"]; ?>">Batalkan Pesanan</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } } else { ?>
            <p class="text-muted">Tidak ada pesanan aktif.</p>
        <?php } ?>
    </div>

    <h3 class="mt-5">Pesanan Selesai</h3>
    <div class="accordion" id="completedOrdersAccordion">
        <?php
        $q_done = $db->prepare("SELECT * FROM pesanan_selesai WHERE user=? ORDER BY tanggal DESC");
        $q_done->execute([$user_id]);
        $no_done = 0;
        if ($q_done->rowCount() > 0) {
            while ($r_done = $q_done->fetch()) { $no_done++;
        ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingDone<?php echo $no_done; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDone<?php echo $no_done; ?>" aria-expanded="false" aria-controls="collapseDone<?php echo $no_done; ?>">
                    #<?php echo $no_done; ?> - Tanggal: <?php echo $r_done["tanggal"]; ?> - Status: <strong>&nbsp;<?php echo $r_done["status"]; ?></strong>
                </button>
            </h2>
            <div id="collapseDone<?php echo $no_done; ?>" class="accordion-collapse collapse" aria-labelledby="headingDone<?php echo $no_done; ?>" data-bs-parent="#completedOrdersAccordion">
                <div class="accordion-body">
                    <a class="btn btn-dark btn-sm" href="invoice.php?idpemesanan=<?php echo $r_done['idpemesanan']; ?>">Lihat</a>
                </div>
            </div>
        </div>
        <?php } } else { ?>
            <p class="text-muted">Tidak ada pesanan selesai.</p>
        <?php } ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</main>
