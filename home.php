<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="css/dashboardUserGeneral.css">
</head>

<body>
  <nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="assets/logo-kemenkes.png" alt="Logo Kemenkes">
        <img src="./assets/logo-puskesmas-nagrak.png" alt="Logo Puskesmas Nagrak">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
          </svg></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto align-items-center">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsultasi KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">
            <button type="button" class="btn btn-outline-danger">Logout</button>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="banner banner2"  style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('./assets/banner-family.jpg');">
    <div>
      <h1>BIJI SELASIH</h1>
      <br>
      <h3>"Bersama Ngahiji Menuju Persalinan Sehat dan Lancar Tanpa Komplikasi"</h3>
    </div>
  </div>
  <div class="content">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-12 col-lg-6 d-flex justify-content-center">
          <img src="./assets/logo2-kemenkes.png" alt="Logo Kemenkes">
        </div>
        <div class="col-12 col-lg-6">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
              Apa itu P4K?
            </h1>
            <button onclick="triggerUpdateRasioVideo()" data-bs-toggle="modal" data-bs-target="#bukaVideoPenjelasan" type="button" class="mainButton btn btn-danger">
              Lihat<p class="m-0">Penjelasan Menu</p>
            </button>
          </div>
          <p>
            Program Perencanaan Persalinan dan Pencegahan Komplikasi (P4K) merupakan salah satu upaya percepatan penurunan Angka Kematian Ibu dan Bayi Baru Lahir melalui peningkatan akses dan mutu pelayanan antenatal, pertolongan persalinan, pencegahan komplikasi dan keluarga berencana oleh Tenaga Kesehatan.
          </p>
          <h1>Apa tujuan P4K?</h1>
          <p>P4K memiliki beberapa tujuan antara lain:</p>
          <ul>
            <li>Suami, keluarga, dan masyarakat paham tentang bahaya persalinan;</li>
            <li>Adanya rencana persalinan yang aman;</li>
            <li>Adanya rencana kontrasepsi yang akan digunakan;</li>
            <li>Adanya dukungan masyarakat, Toma, kader, dukung untuk ikut KB pasca persalinan;</li>
            <li>Adanya dukungan sukarela dalam persiapan biaya, transportasi, donor darah;</li>
            <li>Memantapkan kerjasama antara bidan, dukun bayi, dan kader.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="row" id="footer">
    <div class="col"></div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="bukaVideoPenjelasan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-photo-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Video Penjelasan P4K</h1>
          <button onclick="closeDialogVideoPenjelasan()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe id="videoPenjelasan" style="width : 80vw;" src="https://www.youtube.com/embed/Of-mrv90OLw?si=oTOcD2NfBBvWgC5j" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (isset($_GET['login'])) {
    if ($_GET['login'] == "success") { 
  ?>
    <button id="buttonPanduanWebsite" style="display: none;" data-bs-toggle="modal" data-bs-target="#panduanWebsite" type="button" class="mainButton btn btn-danger">
      <p class="m-0">Panduan Website</p>
    </button>
    <div class="modal fade" id="panduanWebsite" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h4 class="text-center">Panduan Dalam Mengakses Website "BIJI SELASIH"</h4>
            <br>
            <ul>
              <li><h5>Disarankan mengisi semua menu secara berurutan</h5></li>
              <img class="border border-1 border-primary rounded-2" src="./assets/navbar.png" alt="navbar" style="width: 100%;">
              <p>
                Website ini memiliki 4 menu utama yaitu : Pembiayaan, Sarpras, Donor Darah dan Konsultasi KB. Di dalam menu - menu tersebut, terdapat formulir yang harus diisi. Dalam mengakses menu tersebut, disarankan untuk mengakses secara berutan dimulai dari menu Pembiayaan hingga Konsultasi KB agar tidak ada formulir yang terlewati. 
              </p>
              <li><h5>Melihat video pejelasan menu</h5></li>
              <img class="border border-1 border-primary rounded-2" src="./assets/tombolLihatPenjelasanMenu.png" alt="navbar" style="width: 100%;">
              <p>
                Di menu Home, Pembiayaan, Sarpras, Donor Darah, dan Konsultasi KB terdapat video penjelasan menu yang dapat diakses dengan menekan tombol "Lihat Penjelasan Menu" di tiap menu. Di dalam menu Home, anda dapat melihat video penjelasan tentang P4K. Sementara itu, di menu Pembiayaan, Sarpras, Donor Darah dan Konsultasi KB anda dapat melihat video tutorial dalam mengisi formulir di menu - menu tersebut.
              </p>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const buttonPanduan = document.getElementById('buttonPanduanWebsite');

        buttonPanduan.click();
      })
    </script>
  <?php  } } ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="./js/userHome.js"></script>
</body>

</html>