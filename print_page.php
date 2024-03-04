<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "disposisiuam";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Periksa apakah parameter ID diberikan dalam URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Query untuk mengambil data jabatan berdasarkan ID
    $query = "SELECT * FROM jabatan WHERE idjab = ?";
    
    // Persiapkan statement
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameter
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Eksekusi statement
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_assoc($result);
    } else {
        echo "Data jabatan tidak ditemukan.";
        exit; // Keluar dari skrip jika data tidak ditemukan
    }
} else {
    echo "ID jabatan tidak diberikan.";
    exit; // Keluar dari skrip jika ID tidak diberikan
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Jabatan UAM</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://uam.ac.id/wp-content/uploads/2022/07/logo_horizontal-768x307.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4CAF50;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0 15px;
            width: 100%;
        }
        .table th, .table td {
            padding: 20px;
            text-align: center;
        }
        .table thead th {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            border: none;
        }
        .table tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        .table-hover tbody tr:hover {
            background-color: #e2e6ea;
        }
        .print-btn {
            text-align: center;
            margin-top: 30px;
        }
        .print-btn button {
            padding: 15px 40px;
            font-size: 18px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .print-btn button:hover {
            background-color: #45a049;
        }
        @media print {
            h2 {
                text-align: left;
            }
            .table th, .table td {
                border: none !important;
                padding: 15px;
            }
            .table thead th {
                background-color: #4CAF50 !important;
            }
            .table tbody tr:nth-of-type(odd) {
                background-color: #f8f9fa !important;
            }
            .table-hover tbody tr:hover {
                background-color: #e2e6ea !important;
            }
            body {
                background-color: #fff !important;
                color: #000 !important;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>DATA JABATAN UAM</h2>
        <?php
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "disposisiuam";

        $koneksi = mysqli_connect($host, $user, $pass, $db);

        if (!$koneksi) {
            die("Koneksi ke database gagal: " . mysqli_connect_error());
        }

        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $query = "SELECT * FROM jabatan WHERE idjab = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) > 0){
                $data = mysqli_fetch_assoc($result);
            } else {
                echo "Data jabatan tidak ditemukan.";
                exit;
            }
        } else {
            echo "ID jabatan tidak diberikan.";
            exit;
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">ID Jabatan</th>
                        <th onclick="sortTable(1)">Nama Jabatan</th>
                        <th onclick="sortTable(2)">Kode Jabatan</th>
                        <th onclick="sortTable(3)">Atasan</th>
                        <th onclick="sortTable(4)">Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['idjab']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['kodejab']; ?></td>
                        <td><?php echo $data['atasan']; ?></td>
                        <td><?php echo $data['date']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="print-btn">
            <button onclick="window.print()">Print Sekarang</button>
        </div>
    </div>

    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector('table');
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[n];
                    y = rows[i + 1].getElementsByTagName("td")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++; 
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</body>
</html>



