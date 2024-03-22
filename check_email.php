<?php
require_once "database.php";

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $exists = mysqli_num_rows($result) > 0;

    echo json_encode(array('exists' => $exists));
}
?>
