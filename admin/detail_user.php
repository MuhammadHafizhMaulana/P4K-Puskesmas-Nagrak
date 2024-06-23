<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = "SELECT ku.*, kb.*, p.*, s.* FROM kesehatan_user ku 
              INNER JOIN kb ON ku.id_user = kb.id_user 
              INNER JOIN pembiayaan p ON ku.id_user = p.id_user 
              INNER JOIN sarpras s ON ku.id_user = s.id_user 
              WHERE ku.id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $query = "SELECT `nama` FROM user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $nameResult = mysqli_stmt_get_result($stmt);
    
    // Periksa apakah data ditemukan
    if (mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        $data = mysqli_fetch_assoc($result);

        // Function untuk merubah format tanggal
        function formatTanggal($tanggal_input) {
            $timestamp = strtotime($tanggal_input);
            $tanggal_format = date("d M Y", $timestamp);
            return $tanggal_format;
        }

        $data['tanggal_input'] = formatTanggal($data['tanggal_input']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/adminKesehatanUser&DetailPendonor.css">
    <style>
        h3 {
            text-align: left;
            /* Atur teks menjadi rata kiri */
        }
    </style>
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                        fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="landing.php">Dashboard</a>
                    <a class="nav-link" href="data_user.php">User</a>
                    <a class="nav-link" href="listKesehatanUser.php">Kesehatan User</a>
                    <a class="nav-link" href="pendonor.php">Pendonor</a>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="boxKesehatanUser">
        <h1 style="font-weight: bold; ">Detail Data User</h1>
        <br><br>
        <div class="w-100">
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Goldar User</h3>
                <a class="" href="form_pembiayaan.php">
                    <button type="button" class="btn btn-primary">Edit</button>
            </div>

            </a>
            <div class="row">
                <div class="col-5 text-start">Nama</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php
                        if (mysqli_num_rows($nameResult) > 0) {
                            // Ambil data pengguna
                            $ambil_nama = mysqli_fetch_assoc($nameResult);
                            echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-";
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Goldar</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['goldar'] ? $data['goldar'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Usia Kandungan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['usia_kandungan'] ? $data['usia_kandungan'] : '-' ?> Minggu
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Status Goldar</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['status'] ? ucwords($data['status']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Terakhir User Update</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tanggal_input'] ? ucwords($data['tanggal_input']) : '-' ?>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Pembayaran</h3>
                <a class="" href="form_pembiayaan.php">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
            <br>
            <div class="row">
                <div class="col-5 text-start">Jenis Pembayaran</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['jenis_pembayaran'] ? $data['jenis_pembayaran'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Status</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['status'] ? $data['status'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Jenis Tabungan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['jenis_tabungan'] ? $data['jenis_tabungan'] : '-' ?>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Sarpras</h3>
                <a class="" href="form_pembiayaan.php">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
            <br>
            <div class="row">
                <div class="col-5 text-start">Transportasi</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['transportasi'] ? $data['transportasi'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nama Sopir</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['nama_supir'] ? $data['nama_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">No Sopir</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['no_supir'] ? $data['no_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nama Pendamping</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['nama_pendamping'] ? $data['nama_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">NO Pendamping</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['no_pendamping'] ? $data['no_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Rumah Sakit Tujuan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? $data['tujuan'] : '-' ?>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data KB User</h3>
                <a class="" href="form_pembiayaan.php">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
            <br>
            <div class="row">
                <div class="col-5 text-start">Tujuan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? $data['tujuan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Metode</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['jenis'] ? $data['jenis'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Deskripsi</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <textarea disabled><?php echo $data['deskripsi'] ? ucwords($data['deskripsi']) : '-' ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/adminKesehatanUser&DetailPendonor.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
<?php
    } else {
        echo "Data pengguna tidak ditemukan.";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    echo "ID tidak ditemukan dalam URL.";
}

// Tutup koneksi
mysqli_close($connect);
?>