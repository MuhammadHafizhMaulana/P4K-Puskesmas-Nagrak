<?php

session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}
include './proses/koneksi.php';

$nomorHPPendonor = "";
$namaPendonor = "";

if (isset($_SESSION['new_donor_name']) && isset($_SESSION['new_donor_hp'])) {
    $namaPendonor = $_SESSION['new_donor_name'];
    $nomorHPPendonor = $_SESSION['new_donor_hp'];

    // Clear session variables to prevent reuse
    unset($_SESSION['new_donor_name']);
    unset($_SESSION['new_donor_hp']);
}

$id = $_SESSION['id'];
$query = "SELECT `nama`, `nomorHP` FROM `user` WHERE `id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nama, $nomorHP);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pendonor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/tambahPendonor.css">
</head>

<body>

    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                <div class="navbar-nav ms-auto">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formTambahPendonor">
        <h1 style="font-weight: bold; font-size: xxx-large">
            Form Pendonor Darah
        </h1>
        <br />
        <?php
        if (isset($_GET['gagal'])) {
            if ($_GET['gagal'] == "nomorHP") {
                echo "<div class='alert alert-danger'>Pendonor dengan nomor HP tersebut telah terdaftar.</div>";
            }
        }
        ?>
        <p>
            Daftarkan pendonor darah
        </p>
        <?php
        if ($nomorHPPendonor && $namaPendonor) {
            // Memeriksa apakah data tertentu telah dikirimkan
        ?>
            <form action="proses/tambah_pendonor_proses.php" method="post">
                <input type="hidden" id="nama" name="nama" value="<?php echo strtoupper($namaPendonor); ?>" required>
                <input type="hidden" id="nomorHP" name="nomorHP" value="<?php echo strtoupper($nomorHPPendonor); ?>" required>
                <div class="form-group">
                    <label for="goldar">Golongan Darah Pendonor</label>
                    <select id="goldar" name="goldar" class="form-select" aria-label="Default select example" required>
                        <option value="-">Belum Mengetahui</option>
                        <option value="a+">A+</option>
                        <option value="o+">O+</option>
                        <option value="b+">B+</option>
                        <option value="ab+">AB+</option>
                        <option value="a-">A-</option>
                        <option value="o-">O-</option>
                        <option value="b-">B-</option>
                        <option value="ab-">AB-</option>
                    </select>
                </div>
                <br />
                <button id="submitPendonor" onclick="openSpinner()" type="submit" class="btn btn-danger">INPUT</button>
                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Form Pengajuan Pengecekan Golongan Darah Pendonor</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning" role="alert">
                                    Setelah tombol "Input" ditekan, anda akan diarahkan ke whatsapp untuk mengirim pesan permintaan pengecekan golongan darah ini kepada dokter.
                                </div>
                                <div class="d-none">
                                    <input id="namaUser" value="<?php echo strtoupper($nama) ?>" type="text" disabled>
                                    <input id="nomorHPUser" value="<?php echo strtoupper($nomorHP) ?>" type="text" disabled>
                                </div>
                                <div style="width: 100%;" class="form-group">
                                    <label for="waktu_pengecekan_goldar">Tentukan tanggal pengecekan golongan darah pendonor</label>
                                    <input style="width: 100%;" type="date" class="form-control" id="waktu_pengecekan_goldar" name="waktu_pengecekan_goldar">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button id="submitJadwal" disabled onclick="openSpinner()" type="submit" data-bs-dismiss="modal" class="btn btn-primary">Input</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php
        } else {
        ?>
            <form action="proses/cek_pendonor_proses.php" method="post">
                <div class="form-group">
                    <label for="nama">Nama Pendonor</label>
                    <input oninput="checkButtonSubmitNamaNomorForm()" type="text" class="form-control" id="nama" name="nama" placeholder="Masukan nama lengkap pendonor" required>
                </div>
                <div class="form-group">
                    <label for="nomorHP">Nomor HP Pendonor</label>
                    <input type="text" class="form-control" id="nomorHP" name="nomorHP" placeholder="Masukan nomor HP pendonor" required>
                </div>
                <br>
                <button id="submitNamaNomorPendonor" onclick="openSpinner()" type="submit" class="btn btn-danger">INPUT</button>
            </form>
        <?php
        }
        ?>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="js/<?php echo $nomorHPPendonor ? "tambah_Pendonor_Second.js" : "tambah_Pendonor_First.js" ?>"></script>
</body>

</html>