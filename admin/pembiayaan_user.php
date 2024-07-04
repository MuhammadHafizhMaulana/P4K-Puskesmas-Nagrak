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

    // Path ke foto
    $kk = "../data_user/kk/kk_$id";
    $ktp = "../data_user/ktp/ktp_$id";
    $pas_foto = "../data_user/pas_foto/pas_foto_$id";
    $rekomendasi = "../data_user/rekomendasi/rekomendasi_$id";
    $rujukan = "../rujukan/rujukan/rujukan_$id";

    // Function to check and return the correct image path
    function getImagePath($basePath) {
        $extensions = ['jpg', 'jpeg', 'png'];
        foreach ($extensions as $ext) {
            $filePath = "$basePath.$ext";
            if (file_exists($filePath)) {
                return $filePath;
            }
        }
        return null;
    }

    $kk_path = getImagePath($kk);
    $ktp_path = getImagePath($ktp);
    $pas_foto_path = getImagePath($pas_foto);
    $rekomendasi_path = getImagePath($rekomendasi);
    $rujukan_path = getImagePath($rujukan);

    $proccessIsSuccess = null;
    if (isset($_GET['success'])) {
        $proccessIsSuccess = true;
        if ($_GET['success'] == "update_successful") {
            $message = "Anda berhasil mengubah deskripsi pembiayaan.";
        }
    } else if (isset($_GET['gagal'])) {
        $proccessIsSuccess = false;
        if ($_GET['gagal'] == "update_failed") {
            $message = "Edit gagal.";
        }
    }

    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembiayaan User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/generalForm.css">
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
                    <a class="nav-link" href="profile_admin.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        <div class="d-flex justify-content-between align-items-end">
            <h3 class="text-start m-0" style="font-weight: bold;">Data Pembiayaan User</h3>
        </div>
        <br>
        <?php if ($data) { ?>
        <div class="w-100">
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-"; ?>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Jenis Pembayaran</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['jenis_pembayaran'] ? $data['jenis_pembayaran'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Saldo Tabungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['saldo_tabungan'] ? $data['saldo_tabungan'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nomor BPJS</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['nomor_bpjs'] ? $data['nomor_bpjs'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Foto KTP</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if ($ktp_path) { ?>
                        <img src="<?php echo $ktp_path; ?>" alt="Foto KTP User" style="width: 150px; height: auto;">
                    <?php } else { ?>
                        Tidak ada foto
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Foto KK</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if ($kk_path) { ?>
                        <img src="<?php echo $kk_path; ?>" alt="Foto KK User" style="width: 150px; height: auto;">
                    <?php } else { ?>
                        Tidak ada foto
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Pas Foto</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if ($pas_foto_path) { ?>
                        <img src="<?php echo $pas_foto_path; ?>" alt="Pas Foto User" style="width: 150px; height: auto;">
                    <?php } else { ?>
                        Tidak ada foto
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Rekomendasi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if ($rekomendasi_path) { ?>
                        <img src="<?php echo $rekomendasi_path; ?>" alt="Rekomendasi User" style="width: 150px; height: auto;">
                    <?php } else { ?>
                        Tidak ada foto
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Rujukan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if ($rujukan_path) { ?>
                        <img src="<?php echo $rujukan_path; ?>" alt="Rujukan User" style="width: 150px; height: auto;">
                    <?php } else { ?>
                        Tidak ada foto
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-4 text-start fw-bolder fw-bolder">Deskripsi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-7 ms-2 mb-2 m-sm-0  text-start">
                    <form method="post" action="proses/editDeskripsi_pembiayaan.php">
                        <textarea name="deskripsi" rows="10"
                            cols="40"><?php echo $data['deskripsi'] ? ucwords($data['deskripsi']) : '-' ?></textarea>


                        <input type="hidden" name="id" value="<?php echo $data['id']?> ">
                        <input type="hidden" name="id_user" value="<?php echo $data['id_user']?> ">
                        <input type="submit">
                    </form>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div class="alert alert-primary text-center">
            <h2>User atas nama <?php echo $ambil_nama['nama'] ?> belum melakukan penginputan data</h2>
        </div>
        <?php } ?>
    </div>

    <?php if (isset($proccessIsSuccess)) { ?>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-primary" id="exampleModalLabel">
                        <?php echo $proccessIsSuccess ? "BERHASIL" : "GAGAL" ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary text-center" role="alert">
                        <?php echo $message ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (isset($_GET['success']) || isset($_GET['gagal'])) { ?>
  <script>
    window.onload = function () {
      var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        keyboard: false
      });
      myModal.show();
    };
  </script>
  <?php } ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>

<?php
    // Tutup statement dan koneksi di sini
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} 
?>
