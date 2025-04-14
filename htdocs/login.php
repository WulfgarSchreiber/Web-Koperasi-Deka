<?php

require 'config.php';

$error_message = "";

if (isset($_POST["login"])) {
    $input = $_POST['input']; // Username atau nomor telepon
    $password = $_POST['password'];

    // Cek apakah input cocok dengan username atau nomor telepon di database
    $query = mysqli_query($conn, "SELECT * FROM login WHERE username = '$input' OR telepon = '$input'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        // Jika username atau nomor telepon ditemukan, cek password
        if ($password == $user['password']) { 
            $_SESSION['admin'] = $user['username'];
            header('location:index.php');
            exit();
        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "User tidak ditemukan! Silakan daftar terlebih dahulu.";
    }
}

// Jika username atau nomor telepon dan password sudah benar, maka akan ditujukan ke halaman index
if (isset($_SESSION['admin'])) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login Page</title>
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
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
                                    <h3 class="text-center font-weight-light my-4">Login - User</h3>
                                    <label class="switch">
                                        <input type="checkbox">
                                        <a href="ownlogin.php" class="slider round"></a>
                                    </label>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputUsername">Username atau Telepon</label>
                                            <input class="form-control py-4" id="input" name="input" type="text" placeholder="Username atau Telepon" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Password" required />
                                        </div>                  
                                        <?php if ($error_message): ?>
                                            <p class="error-message"><?php echo $error_message; ?></p>
                                        <?php endif; ?>               
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">                                      
                                            <button class="btn btn-primary" name="login">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="register.php">Belum punya akun? Daftar sekarang!</a></div>
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
 