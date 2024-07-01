<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: index.php');
}

include './proses/koneksi.php';
$id = $_SESSION['id'];

$dataKB = "SELECT * FROM `kb` WHERE `id_user` = ?";
$stmt = mysqli_prepare($connect, $dataKB);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch the data as an associative array
$dataKB = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_GET['success'])) {
  $proccessIsSuccess = true;
  if ($_GET['success'] == "input") {
    $message = "Anda berhasil menambahkan data konsul KB";
  }
} else if (isset($_GET['gagal'])) {
  $proccessIsSuccess = false;
  $message = "Proses input data konsul KB dilakukan!!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard KB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="css/dashboard_User_General.css">
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
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link active" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
    </div>
  </nav>

  <div class="banner banner2" style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('./assets/banner-hati.jpg');">
    <div>
      <h1>KELANA BAHTERA</h1>
      <br>
      <h3>"Keluarga Berencana, Awal Bahagia dan Sejahtera"</h3>
    </div>
  </div>
  <div class="content">
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-12 col-lg-4 d-flex justify-content-center align-items-center">
          <img src="./assets/logo-goldar.png" alt="Logo Hati">
        </div>
        <div class="children-content col-lg-8 col-12">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
            Deskripsi Program
            </h1>
              <button type="button" onclick="window.location.href='donor_darah.php'" class="mainButton btn btn-danger">
              Lihat<p class="m-0">Penjelasan Menu</p>
              </button>
          </div>
          <div id="boxDeskripsi" style="overflow-y: scroll; max-height: calc(100vh - 169px)">
            <div class="d-flex align-items-center">
              <h1 class="me-1 text-danger">#</h1>
              <h4>Apa itu "KELANA BAHTERA"?</h4>
            </div>
            <p>
              KELANA BAHTERA yang memiliki kepanjangan Keluarga Berencana Awal Bahagia dan Sejahtera merupakan program dari Puskesmas Nagrak untuk memberikan bantuan kepada masyarakat untuk memilih dan menentukan program KB yang sesuai.
            </p>
            <div class="d-flex align-items-center">
                <h1 class="me-1 text-danger">#</h1>
                <h4>Apa itu KB Pasca Salin?</h4>
            </div>
            <p>
              KB Pascasalin adalah upaya pencegahan kehamilan setelah persalinan menggunakan alat kontrasepsi selama masa nifas (sampai 42 hari pasca persalinan).
            </p>
            <div class="d-flex align-items-center">
                <h1 class="me-1 text-danger">#</h1>
                <h4> Apa saja alat kontrasepsi yang dapat digunakan?</h4>
            </div>
            <p>
              Alat kontrasepsi yang dapat digunakan yaitu:
            </p>
            <ul>
              <li><h6>Alat kontrasepsi dalam rahim (AKDR) atau IUD</h6></li>
              <p>
                AKDR adalah alat kontrasepsi berbentuk kecil, elastis dan berlengan, yang dipasang di dalam rahim. AKDR adalah alat kontrasepsi paling efektif dan paling direkomendasikan oleh tenaga kesehatan. Keunggulan AKDR di antaranya: memberikan perlindungan jangka panjang terhadap kehamilan, tidak mengganggu produksi ASI, tidak menimbulkan efek samping hormonal, dan dapat segera dipasang setelah melahirkan. Kekurangan AKDR yaitu Tidak mencegah IMS (infeksi menular seksual), dapat menimbulkan nyeri haid, haid lebih lama dan banyak, serta perbercakan (spotting) di luar siklus haid.
              </p>
              <li><h6>Implan hormonal</h6></li>
              <p>
                Alat kontrasepsi ini memiliki bentuk seperti batang korek api dan akan dimasukkan ke bagian bawah kulit, biasanya pada lengan bagian atas. KB implan akan mengeluarkan hormon secara perlahan, dan bisa mencegah terjadinya kehamilan hingga tiga tahun. Kekurangan KB implan di antaranya: harga cenderung mahal, memiliki efek samping hormonal seperti menstruasi tidak teratur, berat badan naik, tekanan darah naik, pembengkakan dan memar pada area kulit yang terpasang, serta tidak mencegah penularan IMS.
              </p>
              <li><h6>Suntik hormonal</h6></li>
              <p>
                Metode suntik terbagi menjadi dua jenis, yaitu suntik pertiga bulan dan suntik persatu bulan, sehingga pasien perlu kontrol setiap satu atau tiga bulan untuk menerima KB suntik. Sama halnya dengan metode implan, metode ini memiliki kekurangan berikut: biaya yang agak mahal karena perlu kontrol, memiliki efek samping hormonal seperti menstruasi tidak teratur, berat badan naik, tekanan darah naik, serta tidak mencegah penularan IMS.
              </p>
              <li><h6>Pil hormonal</h6></li>
              <p>
                Pil adalah alat kontrasepsi yang efektivitasnya paling rendah. Hal ini karena jangka perlindungan terhadap kehamilan yang singkat (hitungan hari), sehingga pil perlu dikonsumsi setiap hari. Selain itu terdapat peluang pasien lupa mengonsumsi pil setiap harinya yang semakin menurunkan efektivitas pil. Pil memiliki kekurangan seperti alat kontrasepsi hormonal lain (implan dan suntik).
              </p>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-12 col-lg-4 d-flex justify-content-center align-items-center">
          <img src="./assets/logo-donor-darah-2.png" alt="Logo Donor Darah">
        </div>
        <div class="children-content col-12 col-lg-8">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
              Pogram KB Anda
            </h1>
            <?php if (!$dataKB) { ?>
              <button type="button" onclick="window.location.href='pilihKB.php'" class="mainButton btn btn-danger">
                Masukan
                <p class="m-0">Program KB</p>
              </button>
            <?php } ?>
          </div>

          <div class="alert alert-primary text-center" role="alert">
            <?php if ($dataKB) { ?>
              <h6>
                Anda memilih Program KB untuk <?= $dataKB['tujuan'] ?> dengan mettode (<?= $dataKB['jenis'] ?>)
              </h6>
              <div class="alert alert-warning m-0" role="alert" style="border-radius: 20px;">
                <h6 class="m-0 text-start" style="font-weight: bold;">Hasil Konsultasi :</h6>
                <p class="m-0 text-center">
                    <?= $dataKB['deskripsi'] ? $dataKB['deskripsi'] : 'Menunggu hasil konsultasi dengan dokter.' ?>
                </p>
              </div>     
            <?php } else { ?>
              <h6>Anda belum mendaftarkan program KB anda, silahkan daftarkan program KB anda.</h6>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row" id="footer">
    <div class="col"></div>
  </div>
  <button style="display: none;" id="buttonAlert" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"></button>
  <!-- Modal -->
  <?php if (isset($_GET['success']) || isset($_GET['gagal'])) { ?>
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
  <?php
  }
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
  <script src="./js/dashboardKonsulKB.js"></script>
</body>

</html>