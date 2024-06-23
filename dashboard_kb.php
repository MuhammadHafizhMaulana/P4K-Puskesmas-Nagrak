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
          <img src="./assets/logo2-kemenkes.png" alt="Logo Kemenkes">
        </div>
        <div class="col-12 col-lg-6">
          <h1>Apa KB Pasca Salin</h1>
          <p>KB Pascasalin adalah upaya pencegahan kehamilan setelah persalinan menggunakan alat kontrasepsi selama masa
            nifas (sampai 42 hari pasca persalinan).</p>
          <h1>Apa saja alat kontrasepsi yang dapat digunakan?</h1><br>
          <p>

            Alat kontrasepsi yang dapat digunakan yaitu: <br><br>

            1. Alat kontrasepsi dalam rahim (AKD) atau IUD <br><br>

            AKDR adalah alat kontrasepsi berbentuk kecil, elastis dan berlengan, yang dipasang di dalam rahim. AKDR
            adalah alat kontrasepsi paling efektif dan paling direkomendasikan oleh tenaga kesehatan. Keunggulan AKDR di
            antaranya: memberikan perlindungan jangka panjang terhadap kehamilan, tidak mengganggu produksi ASI, tidak
            menimbulkan efek samping hormonal, dan dapat segera dipasang setelah melahirkan. Kekurangan AKDR yaitu Tidak
            mencegah IMS (infeksi menular seksual), dapat menimbulkan nyeri haid, haid lebih lama dan banyak, serta
            perbercakan (spotting) di luar siklus haid.<br><br>

            2. Implan hormonal<br><br>

            Alat kontrasepsi ini memiliki bentuk seperti batang korek api dan akan dimasukkan ke bagian bawah kulit,
            blasanya pada lengan bagian atas. KB implan akan mengeluarkan hormon secara perlahan, dan bisa mencegah
            terjadinya kehamilan hingga tiga tahun. Kekurangan KB implan di antaranya: harga cenderung mahal, memiliki
            efek samping hormonal seperti menstruasi tidak teratur, berat badan naik, tekanan darah naik, pembengkakan
            dan memar pada area kulit yang terpasang, serta tidak mencegah penularan IMS.<br><br>

            3. Suntik hormonal<br><br>

            Metode suntik terbagi menjadi dua jenis, yaitu suntik pertiga bulan dan suntik persatu bulan, sehingga
            pasien perlu kontrol setiap satu atau tiga bulan untuk menerima KB suntik. Sama halnya dengan metode implan,
            metode ini memiliki kekurangan berikut: biaya yang agak mahal karena perlu kontrol, memiliki efek samping
            hormonal seperti menstruasi tidak teratur, berat badan naik, tekanan darah naik, serta tidak mencegah
            penularan IMS.<br><br>

            4. Pil hormonal<br><br>

            Pil adalah alat kontrasepsi yang efektivitasnya paling rendah. Hal ini karena jangka perlindungan terhadap
            kehamilan yang singkat (hitungan hari), sehingga pil perlu dikonsumsi setiap hari. Selain itu terdapat
            peluang pasien lupa mengonsumsi pil setiap harinya yang semakin menurunkan efektivitas pil. Pil memiliki
            kekurangan seperti alat kontrasepsi hormonal lain (implan dan suntik).</p><br><br>
            <a href="pilihKB.php">
            <button type="button" class="btn btn-primary">Pilih Kb anda</button>
          </a>

        </div>
      </div>
    </div>
  </div>
  <div class="row" id="footer">
    <div class="col"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>