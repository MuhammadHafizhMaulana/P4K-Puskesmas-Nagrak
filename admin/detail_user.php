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

    // Memisahkan jenis penolong dan nama penolong
    $penolong_data = isset($sarpras['penolong']) ? explode(' + ', $sarpras['penolong']) : ['', ''];
    $jenis_penolong = trim($penolong_data[0]);
    $nama_penolong = trim($penolong_data[1]);

    $usg_data = isset($sarpras['usg']) ? explode(' + ', $sarpras['usg']) : ['', '', '', ''];
    $status_usg = isset($usg_data[0]) ? $usg_data[0] : '';
    $tanggal_usg = isset($usg_data[1]) ? $usg_data[1] : '';
    $umur_usg = isset($usg_data[2]) ? $usg_data[2] : '';
    $hasil_usg = isset($usg_data[3]) ? $usg_data[3] : '';

    if ($sarpras) {
        if (!empty($ksarpras['tanggal_usg'])) {
            $kesehatan['tanggal_usg'] = formatTanggal($kesehatan['tanggal_usg']);
        } else {
            $kesehatan['tanggal_usg'] = '-';
        }
    }
    
    if ($kesehatan) {
        if (!empty($kesehatan['tanggal_input'])) {
            $kesehatan['tanggal_input'] = formatTanggal($kesehatan['tanggal_input']);
        } else {
            $kesehatan['tanggal_input'] = '-';
        }
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
    <link rel="stylesheet" href="../css/adminKesehatanUserDanDetailPendonor.css">
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
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
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
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['goldar']) ? $kesehatan['goldar'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Usia Kandungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['usia_kandungan']) ? $kesehatan['usia_kandungan'] : '-' ?> Minggu
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Status Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['status']) ? ucwords($kesehatan['status']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Terakhir User Update</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['tanggal_input']) ? ucwords($kesehatan['tanggal_input']) : '-' ?>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Pembayaran</h3>
                <a class="" href="pembiayaan_user.php?id=<?php echo $id_user; ?>">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
            <br>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Jenis Pembayaran</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['jenis_pembayaran']) ? $pembiayaan['jenis_pembayaran'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Status</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['status']) ? $pembiayaan['status'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Jenis Tabungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['jenis_tabungan']) ? $pembiayaan['jenis_tabungan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Saldo Tabungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['saldo_tabungan']) ? 'Rp. '. $pembiayaan['saldo_tabungan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-4 text-start fw-bolder fw-bolder">Deskripsi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-7 ms-2 mb-2 m-sm-0  text-start">
                    <textarea rows="10" cols="40"
                        disabled><?php echo isset($pembiayaan['deskripsi']) ? ucwords($pembiayaan['deskripsi']) : '-' ?></textarea>
                </div>
            </div>
            <br><br>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data Sarpras</h3>
            </div>
            <br>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Transportasi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['transportasi']) ? $sarpras['transportasi'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama Supir</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['nama_supir']) ? $sarpras['nama_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">No Supir</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['no_supir']) ? $sarpras['no_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama Pendamping</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['nama_pendamping']) ? $sarpras['nama_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">No Pendamping</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['no_pendamping']) ? $sarpras['no_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Rumah Sakit Tujuan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['tujuan']) ? $sarpras['tujuan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Jenis Penolong</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($jenis_penolong) ? $jenis_penolong : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama Penolong</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($nama_penolong) ? $nama_penolong : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($status_usg) ? $status_usg : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Tanggal USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($tanggal_usg) ? $tanggal_usg : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Umur Kandungan USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($umur_usg) ? $umur_usg : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Hasil USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($hasil_usg) ? $hasil_usg : '-' ?>
                </div>
            </div>
            
            <br><br>
           
            <div class="d-flex justify-content-between align-items-end">
                <h3 class=" text-start m-0" style="font-weight: bold;">Detail Data KB User</h3>
                <a href="kb_user.php?id_user=<?php echo $id_user; ?>" class="btn btn-primary">Edit</a>
            </div>
            <br>

            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Tujuan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['tujuan']) ? $kb['tujuan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Metode</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['jenis']) ? $kb['jenis'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Tanggal Input</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['tanggal_input']) ? $kb['tanggal_input'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-4 text-start fw-bolder fw-bolder">Deskripsi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-7 ms-2 mb-2 m-sm-0  text-start">
                    <textarea rows="10" cols="40"
                        disabled><?php echo isset($kb['deskripsi']) ? ucwords($kb['deskripsi']) : '-' ?></textarea>
                </div>
            </div>
        </div>

        <script src="../js/adminKesehatanUserDanDetailPendonor.js"></script>

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