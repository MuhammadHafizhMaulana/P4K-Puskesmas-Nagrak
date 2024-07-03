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


    $proccessIsSuccess = null;
    $message = "";

    if (isset($_GET['success'])) {
        $proccessIsSuccess = true;
        if ($_GET['success'] == "update_successful") {
            $message = "Anda berhasil mengedit golongan darah.";
        }
    } elseif (isset($_GET['error'])) {
        $proccessIsSuccess = false;
        if ($_GET['error'] == "update_failed") {
            $message = "Edit golongan darah gagal dilakukan.";
        }
    }

        // Function untuk merubah format tanggal
    function formatTanggal($tanggal_input)
    {
        $timestamp = strtotime($tanggal_input);
        $tanggal_format = date("d M Y", $timestamp);

        return $tanggal_format;
    }

    // Memisahkan jenis penolong dan nama penolong
    $penolong_data = isset($data['penolong']) ? explode(' + ', $sarprasData['penolong']) : ['', ''];
    $jenis_penolong = trim($penolong_data[0]);
    $nama_penolong = trim($penolong_data[1]);
    mysqli_close($connect);

    $usg_data = isset($data['usg']) ? explode(' + ', $data['usg']) : ['', '', '', ''];
    $status_usg = isset($usg_data[0]) ? $usg_data[0] : '';
    $tanggal_usg = isset($usg_data[1]) ? $usg_data[1] : '';
    $umur_usg = isset($usg_data[2]) ? $usg_data[2] : '';
    $hasil_usg = isset($usg_data[3]) ? $usg_data[3] : '';

    $tanggal_usg = formatTanggal($tanggal_usg);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/adminKesehatanUser&DetailPendonor.css">
</head>
<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="landing.php">Dashboard</a>
                    <a class="nav-link" href="data_user.php">User</a>
                    <a class="nav-link" href="listKesehatanUser.php">Kesehatan User</a>
                    <a class="nav-link" href="pendonor.php">Pendonor</a>
                    <a class="nav-link" href="profile_admin.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="boxKesehatanUser">
        <h1 style="font-weight: bold;">Data Sarpras User</h1>
        <br>
        <?php if ($data) { ?>
        <div class="w-100">
            <div class="row">
                <div class="col-5 text-start">Nama Pasien</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Transportasi</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($data['transportasi']) ? ucwords($data['transportasi']) : "-"; ?>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-5 text-start">Nama Supir</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                <?php echo $data['nama_supir'] ? $data['nama_supir'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nomor Supir</div>
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
                <div class="col-5 text-start">Nomor Pendamping</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['no_pendamping'] ? ucwords($data['no_pendamping']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Rumah Sakit Tujuan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Jenis Penolong</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nama Penolong</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">USG</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Tanggal USG</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Umur Kehamilan saat USG</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Kondisi USG</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <h2>User atas nama <?php echo $ambil_nama['nama'] ?> belum melakukan penginputan data</h2>
        <?php } ?>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="../proses/update_golongan_darah.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUpdateModalLabel">Konfirmasi Edit Golongan Darah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mengedit golongan darah user ini?
                    <input id="idUser" name="idUser" type="hidden" value="<?php echo $id; ?>">
                    <input id="valueGoldar" name="valueGoldar" type="hidden">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
    <br><br>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-B4gt1jrGC7Jh4x04U+XrGJ1AS5HTuCJO3uuTS5IhmztgYOSMYnABzA6YkAi9d8dB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9EX3SI5l6tKHwpQN91aVQZyyzRXfuwW8Q+4MGbR2xF6qnGSiNk5h9g" crossorigin="anonymous"></script>
    <script>
        const proccessIsSuccess = "<?php echo $proccessIsSuccess; ?>";
        const message = "<?php echo $message; ?>";

        if (proccessIsSuccess && message) {
            if (proccessIsSuccess == 1) {
                alert(message);
            } else {
                alert(message);
            }
        }

        function editGoldar() {
            document.getElementById("goldarGet").disabled = false;
            document.getElementById("btn-edit-goldar").setAttribute("data-bs-toggle", "modal");
            document.getElementById("btn-edit-goldar").setAttribute("data-bs-target", "#confirmUpdateModal");
            const goldarElement = document.getElementById("goldarGet");
            goldarElement.addEventListener("change", () => {
                const valueGoldar = goldarElement.value;
                document.getElementById("valueGoldar").value = valueGoldar;
            });
        }
    </script>
</body>
</html>

<?php
    // Tutup statement dan koneksi di sini
    mysqli_stmt_close($stmt);
} else {
    echo "ID tidak ditemukan.";
}
?>
