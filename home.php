<?php
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
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
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
<nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">
            <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
                <a class="nav-link" href="donor_darah.php">Donor Darah</a>
                <a class="nav-link" href="#">Pricing</a>
                <a class="nav-link" href="profil.php">Profil</a>
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
  <div class="content">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-12 col-lg-6 d-flex justify-content-center">
          <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
        </div>
        <div class="col-12 col-lg-6">
          <h1>Apa itu P4K?</h1>
          <p>Program Perencanaan Persalinan dan Pencegahan Komplikasi (P4K) merupakan salah satu upaya percepatan penurunan Angka Kematian Ibu dan Bayi Baru Lahir  melalui peningkatan akses dan mutu pelayanan antenatal, pertolongan persalinan, pencegahan komplikasi dan keluarga berencana oleh Tenaga Kesehatan.</p>
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
<!-- <img src="https://img.freepik.com/free-photo/full-shot-mother-girl-nature_23-2150120942.jpg?t=st=1715326139~exp=1715329739~hmac=0add892ff675b4d7c3ec763aeaed3bbd51db7d5df0d31cb7fa7eb161c2d4b357&w=1060" alt=""> -->
    <!-- <div class="con d-flex justify-content-center ">
        <div class="box ">
            <h1>Home</h1>
            <a href="home2.php" class="btn btn-primary" >lanjut</a><br><br><br>
            <a href="proses/logout.php" class="btn btn-primary">Logout</a>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>