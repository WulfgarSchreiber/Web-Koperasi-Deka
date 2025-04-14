<?php
// Menghubungkan ke database
require 'config.php';
require 'session.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['admin'];

//Memerikasa apakah password lama atau baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ambil password lama dari database
    $sql = "SELECT password FROM login WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Mengganti password yang lama degnan password yang baru
    if ($user && $old_password === $user['password']) {
        if ($new_password === $confirm_password) {
            $update_sql = "UPDATE login SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password, $username);
            if ($update_stmt->execute()) {
                echo "<script>alert('Password berhasil diperbarui!'); window.location.href='profile.php';</script>";
            } else {
                echo "<script>alert('Gagal memperbarui password!');</script>";
            }
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
        }
    } else {
        echo "<script>alert('Password lama salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">Ubah Password</div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Ubah Password</button>
                    <a href="profile.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
