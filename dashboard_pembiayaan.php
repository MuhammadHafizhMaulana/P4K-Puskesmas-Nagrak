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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/dashboardUserGeneral.css">
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
          <a class="nav-link active" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">
            <button type="button" class="btn btn-outline-danger">Logout</button>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="banner banner1" style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('./assets/banner-pembiayaan.jpg');">
    <div>
      <h1>SIAP GRAK</h1>
      <br>
      <h3>"Solusi Ibu Atasi Pembayaran"</h3>
    </div>
  </div>
  <div class="content" id="dashboardPembiayaan">
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-12 col-lg-4 d-flex justify-content-center align-items-center">
          <img src="./assets/logo-deskripsi.png" alt="Logo Deskripsi">
        </div>
        <div class="children-content col-lg-8 col-12">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
              Deskripsi Program
            </h1>
            <button onclick="triggerUpdateRasioVideo()" data-bs-toggle="modal" data-bs-target="#bukaVideoPenjelasan" type="button" class="mainButton btn btn-danger">
              Lihat<p class="m-0">Penjelasan Menu</p>
            </button>
          </div>
          <div id="boxDeskripsi" style="overflow-y: scroll; max-height: calc(100vh - 169px)">
            <div class="d-flex align-items-center">
              <h1 class="me-1 text-danger">#</h1>
              <h4>Apa itu "SIAP GRAK"?</h4>
            </div>
            <p>
              SIAP GRAK yang memiliki kepanjangan Solusi Ibu Atasi Pembayaran merupakan program dari Puskesmas Nagrak untuk memberikan solusi serta monitoring persiapan pembiayaan persalinan ibu hamil. Dalam mengakses program ini, anda perlu menginput foto data diri (dijelaskan di bawah), saldo tabungan persalinan saat ini, dan juga jenis pembiayaan yang dipilih. Selain itu, anda juga perlu menginputkan nomor BPJS apabila memilih jenis pembiayaan BPJS Aktif. 
            </p>
            <p>
              Anda tidak perlu khawatir dalam mengisi data data tersebut, karena diakhir mengisi formulir anda akan diarahkan ke whatsapp untuk dapat berkonsultasi lebih lanjut dengan tenaga kesehatan Puskesmas Nagrak terkait pembiayaan yang sesuai dengan anda.
            </p>
            <div class="d-flex align-items-center">
              <h1 class="me-1 text-danger">#</h1>
              <h4> Apa saja data yang diperlukan dalam program "SIAP GRAK"?</h4>
            </div>
            <p>
              Data yang perlu diinputkan di dalam program ini antara lain:
            </p>
            <ul>
              <li>
                <h6>Foto Kartu Keluarga</h6>
              </li>
              <p>
                Foto Kartu Keluarga yang diinputkan harus jelas (tidak buram) dan juga foto harus memiliki format jpg, jpeg atau png. Selain format tersebut, foto tidak dapat diinputkan.
              </p>
              <li>
                <h6>Foto Kartu Tanda Penduduk</h6>
              </li>
              <p>
                Foto KTP yang diinputkan harus jelas (tidak buram) dan juga foto harus memiliki format jpg, jpeg atau png. Selain format tersebut, foto tidak dapat diinputkan.
              </p>
              <li>
                <h6>Foto Rujukan (opsional)</h6>
              </li>
              <p>
                Foto Rujukan yang diinputkan harus jelas (tidak buram) dan juga foto harus memiliki format jpg, jpeg atau png. Selain format tersebut, foto tidak dapat diinputkan. Foto Rujukan bersifat opsional, artinya jika tidak ada rujukan, tidak perlu menginputkan foto rujukan
              </p>
              <li>
                <h6>Pas Foto</h6>
              </li>
              <p>
                Pas Foto yang diinputkan harus jelas (tidak buram) dan juga foto harus memiliki format jpg, jpeg atau png. Selain format tersebut, foto tidak dapat diinputkan. Pas Foto juga harus memiliki rasio 3 x 4.
              </p>
              <li>
                <h6>Foto Surat Rekomendasi dari Kelurahan (opsional)</h6>
              </li>
              <p>
                Foto Surat Rekomendasi yang diinputkan harus jelas (tidak buram) dan juga foto harus memiliki format jpg, jpeg atau png. Selain format tersebut, foto tidak dapat diinputkan. Foto Surat Rekomendasi bersifat opsional, artinya jika tidak ada rekomendasi dari kelurahan, tidak perlu menginpukan Foto Surat Rekomendasi.
              </p>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class=" col-12 col-lg-4 d-flex justify-content-center align-items-center">
          <img src="./assets/logo-pembiayaan-2.png" alt="Logo Donor Darah">
        </div>
        <div class="children-content col-12 col-lg-8">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
              Data Pembiayaan
            </h1>
            <button type="button" onclick="window.location.href='form_pembiayaan.php'" class="mainButton btn btn-danger"><?php echo $pembiayaanData ? "Edit"  : "Tambah"; ?>
              <p class="m-0">Data Pembiayaan</p>
            </button>
          </div>
          <?php if ($pembiayaanData) { ?>
            <div id="carouselExampleCaptions" class="carousel slide m-0 mx-md-5" data-bs-ride="carousel" data-bs-interval="3000">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <?php if ($pembiayaanData['rujukan'] != "-" && $pembiayaanData['rekomendasi'] != '-') { ?>
                  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
                <?php } else if ($pembiayaanData['rujukan'] != "-" || $pembiayaanData['rekomendasi'] != '-') { ?>
                  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <?php } ?>
              </div>
              <div class="border border-primary border-5 carousel-inner" style="border-radius: 20px;">
                <div class="rounded-1 carousel-item active" style='background-image: url("./proses/check_ktp.php");'>
                  <div class="carousel-caption d-block">
                    <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('ktp')" type="button" class="btn btn-primary">
                      <h6 class="m-0">Lihat detail foto KTP</h6>
                    </button>
                  </div>
                </div>
                <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_kk.php");'>
                  <div class="carousel-caption d-block">
                    <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('kk')" type="button" class="btn btn-primary">
                      <h6 class="m-0">Lihat detail foto KK</h6>
                    </button>
                  </div>
                </div>
                <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_pas_foto.php");'>
                  <div class="carousel-caption d-block">
                    <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('pas_foto')" type="button" class="btn btn-primary">
                      <h6 class="m-0">Lihat detail Pas Foto</h6>
                    </button>
                  </div>
                </div>
                <?php if ($pembiayaanData['rujukan'] != "-") { ?>
                  <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_rujukan.php");'>
                    <div class="carousel-caption d-block">
                      <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('rujukan')" type="button" class="btn btn-primary">
                        <h6 class="m-0">Lihat detail foto Rujukan</h6>
                      </button>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($pembiayaanData['rekomendasi'] != "-") { ?>
                  <div class="rounded-1 carousel-item" style='background-image: url("./proses/check_rekomendasi.php");'>
                    <div class="carousel-caption d-block">
                      <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="openPhotoDialog('rekomendasi')" type="button" class="btn btn-primary">
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
            <br>
            <div class="alert alert-primary text-center" role="alert">
              <?php if ($pembiayaanData) { ?>
                <h6>
                  Saldo tabungan persalinan yang sudah anda miliki sebanyak Rp. <?= $pembiayaanData['saldo_tabungan'] ?>. Jenis pembiayaan yang anda pilih yaitu <?= $pembiayaanData['jenis_pembayaran'] ?> <?= $pembiayaanData['jenis_pembayaran'] == 'BPJS Aktif' ? ' dengan nomor BPJS ' . $pembiayaanData['nomor_bpjs'] . '.' : '.' ?>
                </h6>
                <div class="alert alert-warning m-0" role="alert" style="border-radius: 20px;">
                  <h6 class="m-0 text-start" style="font-weight: bold;">Hasil Konsultasi :</h6>
                  <p class="m-0 text-center">
                    <?= $pembiayaanData['deskripsi'] ? $pembiayaanData['deskripsi'] : 'Menunggu hasil konsultasi dengan dokter.' ?>
                  </p>
                </div>
              <?php } else { ?>
                <h6>Anda belum mendaftarkan data pembiayaan anda, silahkan daftarkan data pembiayaan anda.</h6>
              <?php } ?>
            </div>
          <?php } else { ?>
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
  <button style="display: none;" id="buttonAlert" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"></button>

  <!-- Modal -->
  <div class="modal fade" id="bukaVideoPenjelasan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-photo-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Video Penjelasan Menu</h1>
          <button onclick="closeDialogVideoPenjelasan()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe id="videoPenjelasan" style="width : 80vw;" src="https://www.youtube.com/embed/_9yVJB5qEkQ?si=5w94bIKOq_On4dX3" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="js/dashboard_Pembiayaan.js"></script>
  <script src="./js/userHome.js"></script>
</body>

</html>