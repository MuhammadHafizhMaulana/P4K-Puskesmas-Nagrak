<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

include '../proses/koneksi.php';

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    $query = "SELECT * FROM kb WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $query = "SELECT `nama` FROM user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $nameResult = mysqli_stmt_get_result($stmt);

    $data = mysqli_fetch_assoc($result);
    $ambil_nama = mysqli_fetch_assoc($nameResult);

    function formatTanggal($tanggal_input)
    {
        $timestamp = strtotime($tanggal_input);
        $tanggal_format = date("d M Y", $timestamp);
        return $tanggal_format;
    }

    if ($data && !empty($data['tanggal_input'])) {
        $data['tanggal_input'] = formatTanggal($data['tanggal_input']);
    }

    $proccessIsSuccess = null;
    if (isset($_GET['success'])) {
        $proccessIsSuccess = true;
        if ($_GET['success'] == "update_successful") {
            $message = "Anda berhasil mengubah deskripsi KB.";
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
    <title>Detail KB User</title>
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
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
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
        <div class="d-flex justify-content-between align-items-end">
            <h1 class="text-start m-0" style="font-weight: bold;">Data KB User</h1>
        </div>
        <?php if ($data) { ?>
            <br>
        <div class="w-100">
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : "-"; ?>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-sm-5 text-start fw-bolder">Tujuan KB</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['tujuan'] ? ucwords($data['tujuan']) : '-' ?> Kehamilan
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Jenis KB</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['jenis'] ? ucwords($data['jenis']) : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Tanggal Input</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo $data['tanggal_input'] ? $data['tanggal_input'] : '-' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Hasil Konsultasi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 ms-sm-0  text-start">
                    <form method="post" action="proses/editDeskripsi_kb.php" style="max-width: -webkit-fill-available;">
                        <textarea <?= $data['deskripsi'] ? 'disabled' : '' ?> id="deskripsi"
                            oninput="cekFieldHasilKonsultasi()" name="deskripsi" class="form-control"
                            style="width: 100%; height: 150px" style="width: -webkit-fill-available;"
                            placeholder="masukan hasil konsultasi disini"><?php echo $data['deskripsi'] ? $data['deskripsi'] : '' ?></textarea>
                        <input type="hidden" name="id" value="<?php echo $data['id']?> ">
                        <input type="hidden" name="id_user" value="<?php echo $data['id_user']?> ">
                        <div class="w-100 d-flex justify-content-center mt-1">
                            <button id="buttonUpdateKonsultasi" type="button"
                                <?= $data['deskripsi'] ? 'onclick="editHasilKonsultasi()"' : 'disabled data-bs-toggle="modal" data-bs-target="#confirmUpdateModal"' ?>
                                class="btn btn-primary">
                                <p class="m-0" id="buttonHasilKonsulName"><?= $data['deskripsi'] ? 'Edit' : 'Input'  ?>
                                </p>
                                <p class="m-0">Hasil Konsultasi</p>
                            </button>
                        </div>
                        <div class="modal fade" id="confirmUpdateModal" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah anda yakin ingin menyimpan hasil konsultasi?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button onclick="openSpinner()" type="submit" class="btn btn-warning"
                                            data-bs-dismiss="modal">Iya</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br><br>
        </div>
    </div>
    <?php } else { ?>
    <br>
    <div class="alert alert-primary text-center">
        <h6>User atas nama <?php echo $ambil_nama['nama'] ?> belum melakukan penginputan data</h6>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="../js/adminDetailKB.js"></script>

</body>

</html>

<?php
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
?>