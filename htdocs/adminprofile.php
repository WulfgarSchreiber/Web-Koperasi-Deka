<?php
require 'config.php';
require 'sessionadmin.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['owner'])) {
    header("Location: ownlogin.php");
    exit();
}

// Ambil username dari sesi
$adminname = $_SESSION['owner'];

// Query untuk mengambil data user berdasarkan sesi login
$sql = "SELECT adminname, nama, telepon FROM admin WHERE adminname = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminname);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah data ditemukan
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container emp-profile">
        <form method="post">
            <div class="row">
                <div class="col-md-4 text-center">
                    <br>
                    <br>
                    <a href="dashboard.php" class="btn btn-primary">&larr; Kembali</a>
                    <a href="ubah_password_admin.php" class="btn btn-dark">Ubah Password</a>
                </div>
                <div class="col-md-8">
                    <div class="profile-head">
                        <h5><?php echo htmlspecialchars($data['adminname']); ?></h5>
                    </div>
                    <div class="profile-tab mt-4">
                        <div class="row mb-2">
                            <div class="col-md-6 font-weight-bold">Username</div>
                            <div class="col-md-6"> <?php echo htmlspecialchars($data['adminname']); ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6 font-weight-bold">Nama</div>
                            <div class="col-md-6"> <?php echo htmlspecialchars($data['nama']); ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6 font-weight-bold">No. Telepon</div>
                            <div class="col-md-6"> <?php echo htmlspecialchars($data['telepon']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
