<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
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
// ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembiayaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
                <div class="navbar-nav ms-auto">
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
                    <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
                    <a class="nav-link" href="donor_darah.php">DonorDarahTambah</a>
                    <a class="nav-link" href="pilihKB.php">Pilih KB</a>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        <h1 style="font-weight: bold; font-size: xxx-large">
            Pemilihan KB
        </h1>
        <br />
        <p>
            Lengkapi data berikut untuk melengkapi data anda
        </p>
        <form id="formKB" method="post" action="proses/kbproses.php" enctype="multipart/form-data">
            <div class="form-group text-start">
                <label for="tujuan" onload="updateForm()">Jenis Pembayaran</label>
                <select id="tujuan" name="tujuan" class="form-select" aria-label="Default select example" required
                    onchange="updateForm()">
                    <option value="">Pilih tujuan anda menggunakan KB</option>
                    <option value="menyudahi">Menyudahi Kehamilan </option>
                    <option value="menjarakan">Menjarakkan Kehamilan</option>
                </select>
            </div>
            <div id="additionalFields" class="text-start"></div>
            <button id="submitForm" type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-danger">INPUT</button>
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Form Pengisian Data KB</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert">
                                Setelah tombol "Input" ditekan, anda akan diarahkan ke whatsapp untuk mengirim pesan
                                permintaan konsul KB kepada dokter.
                            </div>
                            <input id="nama" value="<?php echo $nama; ?>" type="hidden" disabled>
                            <input id="nomorHP" value="<?php echo $nomorHP; ?>" type="hidden" disabled>
                            <input id="alamat" value="<?php echo $alamat; ?>" type="hidden" disabled>
                            <div style="width: 100%;" class="form-group">
                                <label for="waktu_konsul">Tentukan tanggal untuk konsul KB</label>
                                <input style="width: 100%;" type="date" class="form-control"
                                    id="waktu_konsul" name="waktu_konsul">
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
    <script src="js/form_KB.js"></script>
    <script src="js/konsulKB.js"></script>
</body>

</html>