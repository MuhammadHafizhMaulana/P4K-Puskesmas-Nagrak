<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: index.php');
    exit();
}
include './proses/koneksi.php';
$id = $_SESSION['id'];

$pembiayaanData = "SELECT * FROM `pembiayaan` WHERE `id_user` = ?";
$stmt = mysqli_prepare($connect, $pembiayaanData);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch the data as an associative array
$pembiayaanData = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_GET['success'])) {
  $proccessIsSuccess = true;
  if ($_GET['success'] == "input") {
    $message = "Anda berhasil menambahkan data pembiayaan";
  } else if ($_GET['success'] == "edit") {
    $message = "Anda berhasil mengubah data pembiayaan";
  }
} else if (isset($_GET['gagal'])) {
  $proccessIsSuccess = false;
  if ($_GET['gagal'] == "1") {
    $message = "Proses input atau edit data pembiayaan gagal dilakukan!!";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembiayaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/dashboard_UserGeneral.css">
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
              d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
          </svg></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="">Pricing</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="banner">
    <div>
      <h1>Selamat Datang</h1>
      <br>
      <h5>Website Program Perencanaan Persalinan dan Pencegahan Komplikasi (P4K) Puskesmas Nagrak!</h5>
    </div>
  </div>
  <div class="content" id="dashboardPembiayaan">
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-4 d-none d-lg-flex justify-content-center align-items-center">
          <img src="./assets/logo-donor-darah.png" alt="Logo Donor Darah">
        </div>
        <div class="children-content col-12 col-lg-8">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
              Data Pembiayaan
            </h1>
            <button type="button" onclick="window.location.href='form_pembiayaan.php'" class="mainButton btn btn-primary"><?php echo $pembiayaanData ? "Edit"  : "Tambah"; ?>
              <p class="m-0">Data Pembiayaan</p>
            </button>
          </div>
          <?php if ($pembiayaanData) { ?>
            <div class="alert alert-primary w-100" role="alert">
              <div id="carouselExampleCaptions" class="carousel slide m-0 m-md-5">
                <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                  <?php if ($pembiayaanData['rujukan'] != "-" && $pembiayaanData['rekomendasi'] != '-') {?>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
                  <?php } else if ($pembiayaanData['rujukan'] != "-" || $pembiayaanData['rekomendasi'] != '-') { ?> 
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                  <?php } ?>
                </div>
                <div class="carousel-inner">
                  <div class="rounded-1 carousel-item active" style='background-image: url("./proses/check_ktp.php");'>
                    <div class="carousel-caption d-block">
                      <button  data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('ktp')" type="button" class="btn btn-primary">
                        <h6 class="m-0">Lihat detail foto KTP</h6>
                      </button>
                    </div>
                  </div>
                  <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_kk.php");'>
                    <div class="carousel-caption d-block">
                      <button  data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('kk')" type="button" class="btn btn-primary">
                        <h6 class="m-0">Lihat detail foto KK</h6>
                      </button>
                    </div>
                  </div>
                  <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_pas_foto.php");'>
                    <div class="carousel-caption d-block">
                      <button  data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('pas_foto')" type="button" class="btn btn-primary">
                        <h6 class="m-0">Lihat detail Pas Foto</h6>
                      </button>
                    </div>
                  </div>
                  <?php if ($pembiayaanData['rujukan'] != "-") { ?>
                    <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_rujukan.php");'>
                      <div class="carousel-caption d-block">
                        <button  data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('rujukan')" type="button" class="btn btn-primary">
                          <h6 class="m-0">Lihat detail foto Rujukan</h6>
                        </button>
                      </div>
                    </div>  
                  <?php } ?>
                  <?php if ($pembiayaanData['rekomendasi'] != "-") { ?>
                    <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_rekomendasi.php");'>
                      <div class="carousel-caption d-block">
                        <button  data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('rekomendasi')" type="button" class="btn btn-primary">
                          <h6 class="m-0">Lihat detail foto Rekomendasi</h6>
                        </button>
                      </div>
                    </div>  
                  <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                  <div class="bg-primary w">
                    <span class="m-0 carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </div>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                  <div class="bg-primary">
                    <span class="m-0 carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </div>
                </button>
              </div>
              <div class="m-0 alert alert-success" role="alert">
                <h4 class="alert-heading">Detail Data Pembiayaan Anda!</h4>
                <hr>
                <p class="mb-0">Jenis pembayaran yang anda pilih adalah <?php echo $pembiayaanData['jenis_pembayaran'] == 'jkn' ? 'Jaminan Kesehatan Nasional' : 'Tabungan' ?><?php
                if ($pembiayaanData['jenis_tabungan'] != '-') {
                  echo " ({$pembiayaanData['jenis_tabungan']})";
                }
                ?> dengan status <?php echo $pembiayaanData['status'] ?>
                </p>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row" id="footer">
    <div class="col"></div>
  </div>
  <button style="display: none;" id="buttonAlert" type="button" class="btn btn-primary" data-bs-toggle="modal"
    data-bs-target="#exampleModal"></button>

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
  <?php if ($pembiayaanData) { ?>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="titlePhotoDialog"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="contentPhotoDialog" alt="" srcset="">
            </div>
            </div>
        </div>
    </div>
  <?php } ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="js/dashboard_Pembiayaan.js"></script>
</body>
</html>