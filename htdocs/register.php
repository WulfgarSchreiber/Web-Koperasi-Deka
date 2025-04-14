<?php

require 'config.php';

// Proses pendaftaran jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]); // Password disimpan tanpa hashing
    $nama = trim($_POST["nama"]);
    $telepon = trim($_POST["telepon"]);

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password) || empty($nama) || empty($telepon)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    } else {
        // Periksa apakah username sudah terdaftar
        $checkUser = $conn->prepare("SELECT id FROM login WHERE username = ?");
        $checkUser->bind_param("s", $username);
        $checkUser->execute();
        $checkUser->store_result();

        if ($checkUser->num_rows > 0) {
            echo "<script>alert('Username sudah terdaftar! Gunakan username lain.');</script>";
            $checkUser->close();
        } else {
            $checkUser->close();

            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO login (username, password, nama, telepon) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $nama, $telepon);

            if ($stmt->execute()) {
                echo "<script>alert('Registrasi berhasil!');</script>";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('Terjadi kesalahan. Coba lagi!');</script>";
            }

            $stmt->close();
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register User</title>
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
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Register</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="small mb-1" for="username">Username</label>
                                                <input class="form-control py-4" id="username" name="username" type="username" placeholder="Username" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="password">Password</label>
                                                <input class="form-control py-4" id="password" name="password" type="password" placeholder="Password" />
                                            </div>                                 
                                            <div class="form-group">
                                                <label class="small mb-1" for="username">Nama Lengkap</label>
                                                <input class="form-control py-4" id="username" name="nama" type="text" placeholder="Nama Lengkap" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="username">No. Telepon</label>
                                                <input class="form-control py-4" id="username" name="telepon" type="number" placeholder="No. Telpon" />
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">                                               
                                                <button class="btn btn-primary" name="register">Daftar</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="login.php">Sudah punya akun? Masuk sekarang!</a></div>
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
 