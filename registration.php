<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="registration.css">
</head>
<body>
    <div class="card-content">
        <?php
        session_start();

        require_once "database.php";

        if(isset($_POST["submit"])) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $jabatan = $_POST["jabatan"];
            $token = $_POST["token"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if (empty($username) OR empty($email) OR empty($password) OR empty($jabatan) OR empty($token)) {
                array_push($errors,"Semua bidang yang diperlukan"); //All fields are required
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors,"Email tidak valid"); //Email is not valid
            }
            if (strlen($password)<8) {
                array_push($errors,"Kata sandi harus sepanjang 8 karakter"); //Password must be at 8 character long
            }

            // Validasi jabatan dan token (anda bisa menyesuaikan validasi ini dengan kebutuhan anda)
            if (!($jabatan === "Rektor" && $token === "token_rektor" ||
                $jabatan === "Wakil Rektor 1" && $token === "token_wakilrektor1" ||
                $jabatan === "Dekan FST" && $token === "token_dekanfst" ||
                $jabatan === "Dekan FEB" && $token === "token_dekanfeb" ||
                $jabatan === "Dekan FIK" && $token === "token_dekanfik")) {
                array_push($errors, "Token tidak valid");
            }

            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount>0) {
                array_push($errors,"Email sudah ada!");
            }

            if (count($errors)>0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                // Memasukkan data ke dalam database
                $sql = "INSERT INTO users (username, email, password, jabatan) VALUE (?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt,$sql);

                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt,"ssss",$username,$email,$passwordHash,$jabatan);
                    mysqli_stmt_execute($stmt);
                    $_SESSION['username'] = $username; // Mengatur session untuk username
                    $_SESSION['email'] = $email; // Mengatur session untuk email
                    header("Location: login.php"); // Redirect ke halaman login setelah registrasi berhasil
                    exit();
                } else {
                    die("Ada yang salah");
                }
            } 
        }
        ?>

        <form class="card-form" action="registration.php" method="post">
            <header>
                <h1>REGISTER</h1>
                <div class="underline-title"></div>
            </header>
            
            <main class="form-group form-content">
                <label for="username">Username</label>
                <input type="text" name="username" required>
                <div class="form-border"></div>
                
                <label for="email">Email</label>
                <input type="email" name="email" required>
                <div class="form-border"></div>
                
                <label for="password">Password</label>
                <input type="password" name="password" required>
                <div class="form-border"></div>

                <!-- <label for="jabatan">Jabatan</label> -->
                <select name="jabatan" id="jabatan" required class="input-jabatan">
                    <option value="">Pilih Jabatan</option>
                    <option value="Rektor">Rektor</option>
                    <option value="Wakil Rektor 1">Wakil Rektor 1</option>
                    <option value="Dekan FST">Dekan FST</option>
                    <option value="Dekan FEB">Dekan FEB</option>
                    <option value="Dekan FIK">Dekan FIK</option>
                </select>
                <div class="form-border"></div>

                <label for="token">Token</label>
                <input type="text" name="token" required>
                <div class="form-border"></div>
            </main>

            <footer>
                <input class="submit-btn" type="submit" value="Register" name="submit">
                <a href="login.php" class="signin">Sudah punya akun?</a>
            </footer>
        </form>
    </div>
</body>
</html>
