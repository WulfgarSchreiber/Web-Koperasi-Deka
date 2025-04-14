<?php
require 'config.php';
require 'session.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['admin'];

// Query untuk mengambil data user berdasarkan sesi login
$sql = "SELECT username, nama, alamat, telepon FROM login WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
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
                    <br>
                    <a href="index.php" class="btn btn-sm btn-primary">&larr; Kembali</a>
                    <a href="ubah_password.php" class="btn btn-sm btn-dark">Ubah Password</a>
                    <a href="ubahprofile.php" class="btn btn-sm btn-dark">Ubah profile</a>
                </div>
                <div class="col-md-8 text-center">
                    <div class="profile-head">
                        <h5><span style="font-family: 'Poor Richard'; font-size: 40px"><?php echo htmlspecialchars($data['username']); ?></span></h5>
                    </div>
                    <div class="profile-tab mt-4">
                        <div class="row mb-2">
                            <div class="col-md-6 font-weight-bold">Username</div>
                            <div class="col-md-6"> <?php echo htmlspecialchars($data['username']); ?></div>
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
