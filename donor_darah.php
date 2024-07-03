<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}

include './proses/koneksi.php';

$id = $_SESSION['id'];

$query = "SELECT `nama`, `nomorHP`, `alamat` FROM `user` WHERE `id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nama, $nomorHP, $alamat);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$query = "SELECT `goldar`, `usia_kandungan` FROM `kesehatan_user` WHERE `id_user` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $goldar, $usia_kandungan);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($goldar == "-") {
    header('Location: ./dashboard_donor_darah.php');
    exit();
}

if ($usia_kandungan) {
    $value_usia_kandungan = $usia_kandungan;
} else {
    $value_usia_kandungan = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Donor Darah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/generalForm.css">
</head>

<body>
<nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
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
        <div class="navbar-nav ms-auto align-items-center">
          <a class="nav-link" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link active" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">
            <button type="button" class="btn btn-outline-danger">Logout</button>
          </a>
        </div>
      </div>
    </div>
  </nav>
    <div id="formDonorDarah">
        <h1 style="font-weight: bold; font-size: xxx-large">
            <?php echo $goldar ? "Edit" : "Daftar"; ?> Golongan Darah
        </h1>
        <br />
        <?php
        if (isset($_GET['success'])) {
            if ($_GET['success'] == "input") {
                echo "<div class='alert alert-success'>Anda berhasil menambahkan data golongan darah dan usia kehamilan</div>";
            } else if ($_GET['success'] == "edit") {
                echo "<div class='alert alert-success'>Anda berhasil mengubah data golongan darah dan usia kehamilan</div>";
            }
        } else if (isset($_GET['gagal'])) {
            if ($_GET['gagal'] == "1") {
                echo "<div class='alert alert-danger'>Proses input atau edit data golongan darah dan usia kehamilan gagal dilakukan!!</div>";
            }
        }
        ?>
        <p>
            <?php echo $goldar ? "Edit" : "Daftarkan"; ?> golongan darah anda
        </p>
        <form action="proses/input_gol_darah_proses.php" method="post">
            <div class="form-group">
                <label for="goldar">Golongan Darah</label>
                <select id="goldar" name="goldar" class="form-select" aria-label="Default select example" required>
                    <option value="-">Belum Mengetahui</option>
                    <option value="a+" <?php if ($goldar === 'a+') echo 'selected'; ?>>A+</option>
                    <option value="o+" <?php if ($goldar === 'o+') echo 'selected'; ?>>O+</option>
                    <option value="b+" <?php if ($goldar === 'b+') echo 'selected'; ?>>B+</option>
                    <option value="ab+" <?php if ($goldar === 'ab+') echo 'selected'; ?>>AB+</option>
                    <option value="a-" <?php if ($goldar === 'a-') echo 'selected'; ?>>A-</option>
                    <option value="o-" <?php if ($goldar === 'o-') echo 'selected'; ?>>O-</option>
                    <option value="b-" <?php if ($goldar === 'b-') echo 'selected'; ?>>B-</option>
                    <option value="ab-" <?php if ($goldar === 'ab-') echo 'selected'; ?>>AB-</option>
                </select>
            </div>
            <br />
            <button id="submitGoldar" onclick="openSpinner()" type="submit" class="btn btn-danger">Input</button>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Form Pengajuan Pengecekan Golongan
                                Darah</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert">
                                Setelah tombol "Input" ditekan, anda akan diarahkan ke whatsapp untuk mengirim pesan
                                permintaan pengecekan golongan darah ini kepada dokter.
                            </div>
                            <input id="nama" value="<?php echo $nama; ?>" type="hidden" disabled>
                            <input id="nomorHP" value="<?php echo $nomorHP; ?>" type="hidden" disabled>
                            <input id="alamat" value="<?php echo $alamat; ?>" type="hidden" disabled>
                            <div style="width: 100%;">
                                <label for="waktu_pengecekan_goldar">Tentukan tanggal pengecekan golongan darah</label>
                                <input style="width: 100%;" type="date" class="form-control"
                                    id="waktu_pengecekan_goldar" name="waktu_pengecekan_goldar">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button id="submitJadwal" disabled onclick="openSpinner()" type="submit"
                                data-bs-dismiss="modal" class="btn btn-primary">Input</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="./js/donor_Darah.js"></script>
</body>

</html>