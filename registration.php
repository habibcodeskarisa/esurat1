<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
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
    $passwordRepeat = $_POST["repeat_password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $errors = array();

    if (empty($username) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
        array_push($errors,"Semua bidang yang diperlukan"); //All fields are required
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors,"Email tidak valid"); //Email is not valid
    }
    if (strlen($password)<8) {
        array_push($errors,"Kata sandi harus sepanjang 8 karakter"); //Password must be at 8 character long
    }
    if ($password!==$passwordRepeat) {
        array_push($errors,"Kata sandi tidak cocok"); //Password does not match
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
        // kami akan memasukkan data ke dalam database
        $sql = "INSERT INTO users (username, email, password) VALUE (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);

        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"sss",$username,$email,$passwordHash);
            mysqli_stmt_execute($stmt);
            $_SESSION['username'] = $username; // Mengatur session untuk username
            $_SESSION['email'] = $email; // Mengatur session untuk email
            header("Location: login.php"); // Redirect ke halaman index setelah login berhasil
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

                <label for="repeat-password">Repeat Password</label>
                <input type="text" name="repeat_password" required>
                <div class="form-border"></div>

                <!-- <a href="#">
                    <legend class="forgot-pass">Lupa password?</legend>
                </a> -->
            </main>

            <footer>
                <input class="submit-btn" type="submit" value="Register" name="submit">
                <a href="login.php" class="signin">Sudah punya akun?</a>
            </footer>
        </form>
    </div>
</body>
</html>