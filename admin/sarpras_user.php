<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = "SELECT * FROM sarpras WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    // Query untuk mengambil nama pengguna
    $query = "SELECT `nama` FROM user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $nameResult = mysqli_stmt_get_result($stmt);
    $ambil_nama = mysqli_fetch_assoc($nameResult);

        // Function untuk merubah format tanggal
    function formatTanggal($tanggal_input)
    {
        $timestamp = strtotime($tanggal_input);
        $tanggal_format = date("d M Y", $timestamp);

        return $tanggal_format;
    }

    // Memisahkan jenis penolong dan nama penolong
    $penolong_data = isset($data['penolong']) ? explode(' + ', $data['penolong']) : ['', ''];
    $jenis_penolong = trim($penolong_data[0]);
    $nama_penolong = trim($penolong_data[1]);
    mysqli_close($connect);

    $usg_data = isset($data['usg']) ? explode(' + ', $data['usg']) : ['', '', '', ''];
    $status_usg = isset($usg_data[0]) ? $usg_data[0] : '';
    $tanggal_usg = isset($usg_data[1]) ? $usg_data[1] : '';
    $umur_usg = isset($usg_data[2]) ? $usg_data[2] : '';
    $hasil_usg = isset($usg_data[3]) ? $usg_data[3] : '';

    if ($data && !empty($tanggal_usg)) {
            $tanggal_usg = formatTanggal($tanggal_usg);
        }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sarpras User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/generalForm.css">
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
                <img src="../assets/logo-puskesmas-nagrak.png" alt="Logo Puskesmas Nagrak">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                        fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link" href="landing.php">Dashboard</a>
                    <a class="nav-link active" href="data_user.php">User</a>
                    <a class="nav-link" href="listKesehatanUser.php">Kesehatan User</a>
                    <a class="nav-link" href="pendonor.php">Pendonor</a>
                    <a class="nav-link" href="profile_admin.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">
                        <button type="button" class="btn btn-outline-danger">Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        <h1 style="font-weight: bold;">Data Sarpras User</h1>
        <?php if ($data) { ?>
        <br>
        <div class="w-100">
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Nama Pasien</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Transportasi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($data['transportasi']) ? ucwords($data['transportasi']) : "-"; ?>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Nama Supir</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['nama_supir'] ? $data['nama_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Nomor Supir</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['no_supir'] ? $data['no_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Nama Pendamping</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['nama_pendamping'] ? $data['nama_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Nomor Pendamping</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['no_pendamping'] ? ucwords($data['no_pendamping']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Rumah Sakit Tujuan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Jenis Penolong</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $jenis_penolong ? ucwords($jenis_penolong) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Nama Penolong</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $nama_penolong ? ucwords($nama_penolong) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $status_usg ? ucwords($status_usg) : '-' ?>
                </div>
            </div>
            <?php if ($data['usg'] != 'belum') { ?>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Tanggal USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $tanggal_usg ? ucwords($tanggal_usg) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Umur Kehamilan saat USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $umur_usg ? ucwords($umur_usg) .' Minggu' : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 text-start fw-bolder fw-bolder">Kondisi USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-5 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $hasil_usg ? ucwords($hasil_usg) : '-' ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <br>
        <div class="alert alert-primary text-center">
            <h6>User atas nama <?php echo $ambil_nama['nama'] ?> belum melakukan penginputan data</h6>
        </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>

<?php
    // Tutup statement dan koneksi di sini
    mysqli_stmt_close($stmt);
}
?>