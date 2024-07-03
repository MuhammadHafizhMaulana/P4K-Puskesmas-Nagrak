<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL dan apakah valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = intval($_GET['id']);

    // Function untuk merubah format tanggal
    function formatTanggal($tanggal_input) {
        $timestamp = strtotime($tanggal_input);
        return date("d M Y", $timestamp);
    }

    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = "SELECT * FROM kesehatan_user WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $kesehatan_userresult = mysqli_stmt_get_result($stmt);

    if (!$kesehatan_userresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM pembiayaan WHERE id_user = ?";
    $stmt_pembiayaan = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_pembiayaan, "i", $id_user);
    mysqli_stmt_execute($stmt_pembiayaan);
    $pembiayaanresult = mysqli_stmt_get_result($stmt_pembiayaan);

    if (!$pembiayaanresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM sarpras WHERE id_user = ?";
    $stmt_sarpras = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_sarpras, "i", $id_user);
    mysqli_stmt_execute($stmt_sarpras);
    $sarprasresult = mysqli_stmt_get_result($stmt_sarpras);

    if (!$sarprasresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM kb WHERE id_user = ? ORDER BY tanggal_input DESC";
    $stmt_kb = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_kb, "i", $id_user);
    mysqli_stmt_execute($stmt_kb);
    $kbresult = mysqli_stmt_get_result($stmt_kb);

    if (!$kbresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT `nama` FROM user WHERE id = ?";
    $stmt_user = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_user, "i", $id_user);
    mysqli_stmt_execute($stmt_user);
    $nameResult = mysqli_stmt_get_result($stmt_user);

    if (!$nameResult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $kesehatan = mysqli_fetch_assoc($kesehatan_userresult);
    $pembiayaan = mysqli_fetch_assoc($pembiayaanresult);
    $sarpras = mysqli_fetch_assoc($sarprasresult);
    $kb = mysqli_fetch_assoc($kbresult);

    if (isset($kesehatan['tanggal_input'])) {
        $kesehatan['tanggal_input'] = formatTanggal($kesehatan['tanggal_input']);
    }
    
    // Jika data pengguna ditemukan, tampilkan halaman
    if ($kesehatan || $pembiayaan || $sarpras || $kb) {
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
        <h1 style="font-weight: bold;">Detail Data User</h1>
        <br><br>
        <div class="w-100">
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Goldar User</h3>
                <a class="" href="kesehatan_user.php?id=<?php echo $id_user; ?>">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
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
                    <?php echo isset($kesehatan['goldar']) ? $kesehatan['goldar'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Usia Kandungan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($kesehatan['usia_kandungan']) ? $kesehatan['usia_kandungan'] : '-' ?> Minggu
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Status Goldar</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($kesehatan['status']) ? ucwords($kesehatan['status']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Terakhir User Update</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($kesehatan['tanggal_input']) ? ucwords($kesehatan['tanggal_input']) : '-' ?>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Pembayaran</h3>
            </div>
            <br>
            <div class="row">
                <div class="col-5 text-start">Jenis Pembayaran</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($pembiayaan['jenis_pembayaran']) ? $pembiayaan['jenis_pembayaran'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Status</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($pembiayaan['status']) ? $pembiayaan['status'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Jenis Tabungan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($pembiayaan['jenis_tabungan']) ? $pembiayaan['jenis_tabungan'] : '-' ?>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Sarpras</h3>
            </div>
            <br>
            <div class="row">
                <div class="col-5 text-start">Transportasi</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($sarpras['transportasi']) ? $sarpras['transportasi'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nama Sopir</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($sarpras['nama_supir']) ? $sarpras['nama_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">No Sopir</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($sarpras['no_supir']) ? $sarpras['no_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nama Pendamping</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($sarpras['nama_pendamping']) ? $sarpras['nama_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">NO Pendamping</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($sarpras['no_pendamping']) ? $sarpras['no_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Rumah Sakit Tujuan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($sarpras['tujuan']) ? $sarpras['tujuan'] : '-' ?>
                </div>
            </div>
            <br><br>
           
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data KB User</h3>
                <a href="kb_user.php?id_user=<?php echo $id_user; ?>" class="btn btn-primary">Edit</a>
            </div>
            <br>

            <div class="row">
                <div class="col-5 text-start">Tujuan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($kb['tujuan']) ? $kb['tujuan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Metode</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($kb['jenis']) ? $kb['jenis'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Tanggal Input</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($kb['tanggal_input']) ? $kb['tanggal_input'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-4 text-start">Deskripsi</div>
                <div class="col-1">:</div>
                <div class="col-7 text-start">
                    <textarea rows="10" cols="40"
                        disabled><?php echo isset($kb['deskripsi']) ? ucwords($kb['deskripsi']) : '-' ?></textarea>
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
    mysqli_stmt_close($stmt_pembiayaan);
    mysqli_stmt_close($stmt_sarpras);
    mysqli_stmt_close($stmt_kb);
    mysqli_stmt_close($stmt_user);

// Tutup koneksi
mysqli_close($connect);
} else {
    echo "ID tidak valid atau tidak disediakan.";
}
?>