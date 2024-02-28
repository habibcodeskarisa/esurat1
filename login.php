<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="login.css">
</head>

<body>


    <div class="card-content">
        <?php
        session_start();

        require_once "database.php";

        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    $_SESSION['username'] = $user['username']; // Mengatur session untuk username
                    $_SESSION['email'] = $email; // Mengatur session untuk email
                    header("Location: http://192.168.100.254/jabatanuam/index.php"); // Redirect ke halaman index setelah login berhasil
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Password tidak cocok</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email tidak cocok</div>";
            }
        }
        ?>



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
                <input type="email" name="email" required>
                <div class="form-border"></div>
                <!-- Label - Input = Password -->
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <div class="form-border"></div>
                <div class="wrap-link-pw">
                    <button type="button" id="showPassword" class="show-pass">Show Password</button>
                    <!-- <a href="forgot-password.php" class="forgot-pass">Lupa password?</a> -->
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
        document.getElementById("showPassword").addEventListener("mousedown", function() {
            document.getElementById("password").type = "text";
        });

        document.getElementById("showPassword").addEventListener("mouseup", function() {
            document.getElementById("password").type = "password";
        });
    </script>

</body>

</html>