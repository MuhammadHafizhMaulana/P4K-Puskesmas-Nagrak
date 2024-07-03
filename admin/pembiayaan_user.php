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
    $query = "SELECT * FROM pembiayaan WHERE id_user = ?";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembiayaan User</title>
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
        <h1 style="font-weight: bold;">Data Pembiayaan User</h1>
        <br>
        <?php if ($data) { ?>
        <div class="w-100">
            <div class="row">
                <div class="col-5 text-start">Nama</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-"; ?>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-5 text-start">Jenis Pembayaran</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                <?php echo $data['jenis_pembayaran'] ? $data['jenis_pembayaran'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Saldo Tabungan</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['saldo_tabungan'] ? $data['saldo_tabungan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Nomor BPJS</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['nomor_bpjs'] ? $data['nomor_bpjs'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 text-start">Deskripsi</div>
                <div class="col-1">:</div>
                <div class="col-6 text-start">
                    <?php echo $data['deskripsi'] ? ucwords($data['deskripsi']) : '-' ?>
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
    mysqli_close($connect);
} else {
    echo "ID tidak ditemukan.";
}
?>
