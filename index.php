<?php
// Konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "disposisiuam";

// Koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

// Deklarasi variabel
$id     = "";
$idjab  = "";
$nama   = "";
$kodejab = "";
$atasan = "";
$error    = "";
$sukses = "";
$q        = "";

// Mengambil operasi yang diminta dari URL
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Proses penghapusan data
if ($op == 'delete') {
    $id   = $_GET['id'];
    $sql  = "DELETE FROM jabatan WHERE idjab = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    $pesan = $q ? "Sukses berhasil hapus data" : "Gagal melakukan delete data";
    $sukses = $pesan;
}

// Proses tambah data
if (isset($_POST['simpan'])) {
    $idjab      = $_POST['idjab_tambah'];
    $atasan = "";

    switch ($idjab) {
        case 2:
            $nama = "Rektor";
            $kodejab = "RK";
            $atasan = 0; // Tidak ada atasan
            break;
        case 3:
            $nama = "Wakil Rektor 1";
            $kodejab = "WR1";
            $atasan = 2; // ID atasan Rektor
            break;
        case 4:
            $nama = "Dekan FST";
            $kodejab = "DK_FST";
            $atasan = 3; // ID atasan Wakil Rektor 1
            break;
        case 5:
            $nama = "Dekan FEB";
            $kodejab = "DK_FEB";
            $atasan = 3; // ID atasan Wakil Rektor 1
            break;
        case 6:
            $nama = "Dekan FIK";
            $kodejab = "DK_FIK";
            $atasan = 3; // ID atasan Wakil Rektor 1
            break;
        default:
            $error = "ID Jabatan tidak valid";
            break;
    }

    if ($idjab && $nama && $kodejab && $atasan !== "") {
        $sql = "INSERT INTO jabatan (idjab, nama, kodejab, atasan, date) VALUES ('$idjab', '$nama', '$kodejab', '$atasan', NOW())";
        $q   = mysqli_query($koneksi, $sql);
        $pesan = $q ? "Data berhasil disimpan" : "Data gagal disimpan";
        $sukses = $pesan;
    } else {
        $error = "Silahkan masukkan semua data";
    }
}

// Proses update data
if (isset($_POST['update'])) {
    $idjab      = $_POST['idjab'];
    $nama     = $_POST['nama'];
    $kodejab   = isset($_POST['kodejab']) ? $_POST['kodejab'] : '';
    $atasan = isset($_POST['atasan']) ? $_POST['atasan'] : '';

    if ($idjab && $nama && $kodejab && $atasan !== "") {
        $sql = "UPDATE jabatan SET nama='$nama', kodejab='$kodejab', atasan='$atasan', date=NOW() WHERE idjab = '$idjab'";
        $q   = mysqli_query($koneksi, $sql);
        $pesan = $q ? "Data berhasil diupdate" : "Data gagal diupdate";
        $sukses = $pesan;
    } else {
        $error = "Silahkan masukkan semua data";
    }
}


if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: http://192.168.100.240/login-register/login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Data Jabatan UAM</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://uam.ac.id/wp-content/uploads/2022/07/logo_horizontal-768x307.png" type="image/x-icon">
    <!-- Custom CSS -->
    <style>
        /* Gaya kustom */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .card .card-header {
            margin: 0 16px -19px!important;
        }

        .header-name {
            background-color: #343a40;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center; /* Menyusun tombol Tambah Data secara vertikal di tengah */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .header-name span {
            font-size: 25px;
            font-family: 'Poppins', sans-serif;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .search-form {
            margin-bottom: 10px;
        }
        
        .card .search-form .input-group {
            margin-bottom: -10px;
        }

        .card .search-form .input-group .btn-primary {
            width: 100px;
            margin-right: -600px!important;
        }

        .header-name .btn-success {
            margin: 5px -585px 5px 0px!important; /* Membuat tombol Tambah Data lebih kecil */
            padding: 8px 15px;
            font-size: 14px;
        }

        .table {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* TABLE UTAMA BAGIAN AKSI */
        .table-warning tr .th-aksi-utama {
            /* width: 509px; */
            width: 520px;
        }
        
        .table .btn {
            margin: 1px -5px;
        }

        .wrap-table-data .tr-table-utama .td-aksi-utama {
            display: flex;
            justify-content: space-evenly;
        }

        /* Responsif CSS */
        @media only screen and (max-width: 767px) {
            .mx-auto {
                padding: 0 10px;
            }

            .input-group {
                width: 100%;
                margin-bottom: 10px;
            }

            .header-name .btn-success {
                margin: 10px 0;
            }

            .edit-btn,
            .delete-btn {
                margin: 5px 0;
            }

            .btn {
                margin: 0 5px;
            }
            .btn-tambah {
                margin: 0 5px;
                padding: 4px 8px;
                font-size: 14px;
                height: auto;
                line-height: 1.5;
                min-width: auto;
            }

            /* Penyesuaian posisi tombol tambah data */
            .header-name .btn-tambah {
                order: -1; /* Mengubah urutan tombol */
            }

            /* Penyesuaian posisi judul */
            .header-name img {
                order: -2; /* Mengubah urutan judul */
            }

            /* Mengurangi ruang kosong di kolom aksi */
            .table .btn {
                margin: 5px;
            }

        }
    </style>
</head>
<body>
    <div class="mx-auto">
        <!-- Modal Tambah Data -->
        <div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="idjab_tambah" class="form-label">Pilih Jabatan</label>
                                <select class="form-select" id="idjab_tambah" name="idjab_tambah" required>
                                    <option value="2">Rektor</option>
                                    <option value="3">Wakil Rektor 1</option>
                                    <option value="4">Dekan FST</option>
                                    <option value="5">Dekan FEB</option>
                                    <option value="6">Dekan FIK</option>
                                </select>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten utama -->
        <div class="card mt-4"> 
            <div class="card-header header-name bg-dark text-light">
                <div class="d-flex align-items-center">
                    <img src="https://uam.ac.id/wp-content/uploads/2022/07/logo_horizontal-1024x410.png" width="120px" height="48px" class="logo me-3" alt="UAM Logo">
                    <h3 class="header-title mb-0">Data Jabatan UAM</h3>
                </div>
                <form class="search-form form-inline me-3" method="GET" action="index.php">
                    <div class="input-group">
                        <input type="text" class="form-control search-input" placeholder="Cari Berdasarkan Nama...." name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
                        <button class="btn btn-primary" type="submit">🔎 Cari</button>
                    </div>
                </form>
                <button type="button" class="btn btn-success btn-tambah me-3" data-bs-toggle="modal" data-bs-target="#modalTambahData">➕ Tambah Data</button>
                <form method="post" name="logout">
                    <!-- Menggunakan ID untuk tombol logout -->
                    <button type="button" class="btn btn-danger" id="btn-logout">Logout</button>
                </form>

            </div>


            <div class="card-body card-wrap table-responsive">
                <table class="table table-light table-bordered table-hover">
                    <thead class="table-warning text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">ID Jabatan</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Kode Jabatan</th>
                            <th scope="col">Atasan</th>
                            <th scope="col">Tanggal Input</th>
                            <th class="th-aksi-utama" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="wrap-table-data"> 
                        <?php
                        $q_data = isset($_GET['q']) ? $_GET['q'] : '';
                        if ($q_data != '') {
                            $q_data = "SELECT * FROM jabatan WHERE nama LIKE '%$q_data%' ORDER BY idjab";
                        } else {
                            $q_data = "SELECT * FROM jabatan ORDER BY idjab";
                        }

                        $query = mysqli_query($koneksi, $q_data);
                        $no = 1;
                        while ($r_data = mysqli_fetch_array($query)) {
                            $idjab = $r_data['idjab'];
                            $nama = $r_data['nama'];
                            $kodejab = $r_data['kodejab'];
                            $atasan = $r_data['atasan'];
                            $tanggal = date("d M Y H:i:s", strtotime($r_data['date']));
                        ?>
                        <tr class="tr-table-utama">
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $idjab ?></td>
                            <td><?php echo $nama ?></td>
                            <td><?php echo $kodejab ?></td>
                            <td><?php echo $atasan ?></td>
                            <td><?php echo $tanggal ?></td>
                            <td class="td-aksi-utama">
                                <button type="button" class="btn btn-info btn-sm" data-idjab="<?php echo $idjab ?>" onclick="window.location.href='print_page.php?id=<?php echo $idjab ?>'">🖨️ Print Data Jabatan</button>
                                <button type="button" class="btn btn-success btn-sm" onclick="copyToClipboard('<?php echo $idjab.'/'.$nama.'/'.$kodejab.'/'.$atasan ?>')">Copy Nomor Surat</button>
                                <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#modalEditData" 
                                    data-idjab="<?php echo $idjab ?>" data-nama="<?php echo $nama ?>" data-kodejab="<?php echo $kodejab ?>" data-atasan="<?php echo $atasan ?>">✏️ Edit</button>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $idjab ?>">🗑️ Hapus</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="modalEditData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="update.php">
                        <input type="hidden" id="idjab_edit" name="idjab_edit">
                        <div class="mb-3">
                            <label for="idjab_edit" class="form-label">ID Jabatan</label>
                            <input type="text" class="form-control" id="idjab_edit" name="idjab_edit" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_edit" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama_edit" name="nama_edit" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kodejab_edit" class="form-label">Kode Jabatan</label>
                            <input type="text" class="form-control" id="kodejab_edit" name="kodejab_edit" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="atasan_edit" class="form-label">Atasan</label>
                            <input type="text" class="form-control" id="atasan_edit" name="atasan_edit" required readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="updateData">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Script -->
    <script>
        // Script untuk konfirmasi sebelum menghapus data
        $('.delete-btn').click(function(){
            var id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php?op=delete&id='+id;
                }
            });
        });

        // Function untuk menyalin nomor surat ke clipboard
        function copyToClipboard(text) {
            var tempInput = document.createElement("input");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Nomor surat telah disalin!',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Mengisi nilai input pada modal edit saat tombol "Edit" ditekan
        $('.edit-btn').click(function(){
            var idjab = $(this).data('idjab');
            var nama = $(this).data('nama');
            var kodejab = $(this).data('kodejab');
            var atasan = $(this).data('atasan');

            // Populate the fields in the modal edit form
            $('#idjab_edit').val(idjab);
            $('#nama_edit').val(nama);
            $('#kodejab_edit').val(kodejab);
            
            // Check if atasan is not empty, then populate the atasan_edit field
            if(atasan !== "") {
                $('#atasan_edit').val(atasan);
            } else {
                $('#atasan_edit').val(''); // Clear the field if atasan is empty
            }

            $('#modalEditData').modal('show');
        });

        // Handle update data
        $('#updateData').click(function(){
            var idjab = $('#idjab_edit').val();
            var nama = $('#nama_edit').val();
            var kodejab = $('#kodejab_edit').val();

            Swal.fire({
                icon: 'success',
                title: 'Data berhasil diupdate!',
                showConfirmButton: false,
                timer: 1500
            });

            $('#modalEditData').modal('hide');
        });
        // Function untuk menampilkan konfirmasi saat logout
        $('#btn-logout').click(function(){
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna menekan Ya, logout
                    // Lakukan pengalihan halaman ke halaman login
                    window.location.href = 'http://192.168.100.240/login-register/login.php';
                }
            });
        });

    </script>

</body>
</html>
