<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}
include './proses/koneksi.php';
$id = $_SESSION['id'];

$sarprasData = "SELECT * FROM `sarpras` WHERE `id_user` = ?";
$stmt = mysqli_prepare($connect, $sarprasData);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch the data as an associative array
$sarprasData = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_GET['success'])) {
  $proccessIsSuccess = true;
  if ($_GET['success'] == "input") {
    $message = "Anda berhasil menambahkan data sarpras";
  } else if ($_GET['success'] == "edit") {
    $message = "Anda berhasil mengubah data sarpras";
  }
} else if (isset($_GET['gagal'])) {
  $proccessIsSuccess = false;
  if ($_GET['gagal'] == "1") {
    $message = "Proses input atau edit data sarpras gagal dilakukan!!";
  }
}

// Memisahkan jenis penolong dan nama penolong
$penolong_data = isset($sarprasData['penolong']) ? explode(' + ', $sarprasData['penolong']) : ['', ''];
$jenis_penolong = trim($penolong_data[0]);
$nama_penolong = trim($penolong_data[1]);
mysqli_close($connect);

$usg_data = isset($sarprasData['usg']) ? explode(' + ', $sarprasData['usg']) : ['', '', '', ''];
$status_usg = isset($usg_data[0]) ? $usg_data[0] : '';
$tanggal_usg = isset($usg_data[1]) ? $usg_data[1] : '';
$umur_usg = isset($usg_data[2]) ? $usg_data[2] : '';
$hasil_usg = isset($usg_data[3]) ? $usg_data[3] : '';

if ($status_usg === 'belum') {
    // Lakukan sesuatu jika status USG adalah "belum"
    // Misalnya:
    $tanggal_usg = ''; // Atur tanggal menjadi kosong
    $hasil_usg = ''; // Atur hasil USG menjadi kosong
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sarpras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="css/dashboardCustomer.css">
</head>

<body>
  <nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="assets/logo-kemenkes.png" alt="Logo Kemenkes">
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
          <a class="nav-link" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link active" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
      </div>
  </nav>

  <div class="banner">
    <div>
      <h1>ADIPURA</h1>
      <br>
      <h5> ATASI, DAMPINGI IBU PUNAHKAN PENDARAHAN</h5>
    </div>
  </div>
  <div class="content">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-12 col-lg-6 d-flex justify-content-center">
          <img src="./assets/logo2-kemenkes.png" alt="Logo Kemenkes">
        </div>
        <div class="col-12 col-lg-6">
          <h1>Sarana Prasarana</h1>
          <p>Perdarahan pasca salin merupakan penyebab kematian nomor satu pada angka kematian ibu. Kematian akibat perdarahan pasca salin selain disebabkan oleh faktor medis juga dapat disebabkan oleh multifaktorial salah satunya terlambat mendapatkan penanganan. Hal ini dapat dicegah dengan terdatanya transportasi dengan lengkap sehingga pada saat persalinan semua telah disiapkan dengan baik.</p>

          <button type="button" class="btn btn-primary" onclick="window.location.href='form_sarpras.php'">
            <p class="m-0">
              <?php if ($sarprasData) { ?>
              Edit Data Anda
              <?php } else {?>
              Tambah Data Anda
            </p>
            <?php } ?>
          </button>
          <?php if ($sarprasData) { ?>
          <br><br>
          <div class="m-0 alert alert-primary" role="alert">
            <h4 class="alert-heading">Detail Data Sarpras Anda!</h4>
            <p class="mb-0">
              Transportasi : <?php echo $sarprasData['transportasi'] ? $sarprasData['transportasi'] : '-' ?><br>
              Nama Supir : <?php echo $sarprasData['nama_supir'] ? $sarprasData['nama_supir'] : '-' ?><br>
              No Supir : <?php echo $sarprasData['no_supir'] ? $sarprasData['no_supir'] : '-' ?><br>
              Nama Pendamping :
              <?php echo $sarprasData['nama_pendamping'] ? $sarprasData['nama_pendamping'] : '-' ?><br>
              No Pendamping : <?php echo $sarprasData['no_pendamping'] ? $sarprasData['no_pendamping'] : '-' ?><br>
              Tujuan : <?php echo $sarprasData['tujuan'] ? $sarprasData['tujuan'] : '-' ?><br>
              Penolong :
              <?php echo $sarprasData['penolong'] ? "Penolong anda adalah " . $jenis_penolong . " dengan nama " . $nama_penolong : '-'; ?><br>
              <?php if($status_usg === 'pernah') { ?>
              USG :
              <?php echo $sarprasData['usg'] ? "Anda pernah melakukan USG pada ". $tanggal_usg. " dengan umur USG yaitu ". $umur_usg . " minggu, dengan kondisi USG ". $hasil_usg : '-' ?><br>
              <?php } else if ($status_usg === 'belum') {?>
              USG : <?php echo $sarprasData['usg'] ? $sarprasData['usg'] : '-' ?><br>
              <?php } ?>
            </p>
          </div>
        </div>
        <?php } else {?>
        <div class="alert alert-primary text-center" role="alert">
          <h6>Anda belum menginputkan data pembiayaan!!</h6>
        </div>
        <?php } ?>
      </div>
    </div>

  </div>
  </div>
  <div class="row" id="footer">
    <div class="col"></div>
  </div>
  <!-- Modal -->
  <?php
  if (isset($_GET['success']) || isset($_GET['gagal'])) { ?>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-primary" id="exampleModalLabel">
            <?php echo $proccessIsSuccess ? "BERHASIL" : "GAGAL" ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-primary" role="alert">
            <?php echo $message ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php if ($sarprasData) { ?>
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-photo-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="titlePhotoDialog"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div style=" overflow-x: scroll; width: 100%; max-width: min-content;">
            <img style="height: 70vh;" id="contentPhotoDialog" alt="" srcset="">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>