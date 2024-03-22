<?php
session_start();

require_once "database.php";

$errors = array(); // Inisialisasi array untuk menyimpan pesan error

// Jika pengguna sudah login dan cookie "Ingat Saya" tersimpan
if (isset($_COOKIE["remember_email"]) && !isset($_SESSION["email"])) {
    $email = $_COOKIE["remember_email"];

    $sql = "SELECT * FROM users WHERE email = '$email' AND remember_token = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        // Set session untuk pengguna
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $email;

        // Redirect ke halaman utama setelah login otomatis
        header("Location: http://192.168.100.156/jabatanuam/utama.php");
        exit();
    }
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Set session untuk pengguna
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $email;

            // Set cookie "Ingat Saya" jika checkbox dicentang
            if (isset($_POST["rememberMe"])) {
                $cookie_name = "remember_email";
                $cookie_value = $email;
                $cookie_expire = time() + (10 * 365 * 24 * 60 * 60); // Cookie berlaku selama 10 tahun
                setcookie($cookie_name, $cookie_value, $cookie_expire, "/");

                // Simpan nilai email ke dalam database
                $sql = "UPDATE users SET remember_token = '$cookie_value' WHERE email = '$email'";
                mysqli_query($conn, $sql);
            }

            // Redirect ke halaman utama setelah login berhasil
            header("Location: http://192.168.100.153/jabatanuam/utama.php");
            exit();
        } else {
            $errors[] = "Password tidak cocok"; // Tambahkan pesan error jika password tidak cocok
        }
    } else {
        $errors[] = "Email tidak cocok"; // Tambahkan pesan error jika email tidak cocok
    }
}


// Hapus cookie dan data di database saat pengguna logout atau mengklik "Lupa Password"
if (isset($_POST["logout"]) || isset($_POST["forgotPassword"])) {
    // Hapus cookie "Ingat Saya"
    setcookie("remember_email", "", time() - 3600, "/");

    // Hapus nilai email dari database
    $email = $_SESSION["email"];
    $sql = "UPDATE users SET remember_token = NULL WHERE email = '$email'";
    mysqli_query($conn, $sql);

    // Redirect ke halaman login setelah logout
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="card-content">
        <form action="login.php" method="post" class="card-form">
            <!-- JUDUL | START -->
            <header>
                <h1>LOGIN</h1>
                <div class="underline-title"></div>
            </header>
            <!-- JUDUL | END -->

            <!-- FORM INPUT | START -->
            <main class="form-group form-content">
                <!-- Label - Input = Email -->
                <label for="email">Email</label>
                <input type="email" name="email" required><div id="emailStatus"></div>
                <div class="form-border"></div>
                <!-- Label - Input = Password -->
                <label for="password">Password</label>
                <div class="password-input">
                    <input type="password" name="password" id="password" required>
                    <span toggle="#password" class="toggle-password"><i class="fas fa-eye"></i></span>
                </div>
                <div class="form-border"></div>
                <div class="wrap-rememberme">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Remember Me</label>
                </div>
            </main>
            <!-- FORM INPUT | END -->

            <!-- BUTTTON | START -->
            <footer>
                <input class="submit-btn" type="submit" value="Login" name="login">
                <a href="registration.php" class="signup">Belum punya akun?</a>
            </footer>
            <!-- BUTTTON | END -->
        </form>
    </div>

    <script defer>
        const togglePassword = document.querySelector('.toggle-password');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.addEventListener("DOMContentLoaded", function() {
            <?php if (count($errors) > 0) : ?>
                <?php foreach ($errors as $error) : ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '<?php echo $error; ?>',
                        showConfirmButton: false,
                        timer: 1500
                    });
                <?php endforeach; ?>
            <?php endif; ?>

        });

        document.querySelector('input[name="email"]').addEventListener('input', function() {
            let email = this.value;
            if (email !== '') {
                fetch('check_email.php?email=' + email)
                    .then(response => response.json())
                    .then(data => {
                        let emailStatus = document.getElementById('emailStatus');
                        if (data.exists) {
                            emailStatus.innerHTML = '<i class="fas fa-check-circle"></i>';
                        } else {
                            emailStatus.innerHTML = '<i class="fas fa-times-circle"></i>';
                        }
                    });
            } else {
                document.getElementById('emailStatus').innerHTML = '';
            }
        });
    </script>

</body>

</html>