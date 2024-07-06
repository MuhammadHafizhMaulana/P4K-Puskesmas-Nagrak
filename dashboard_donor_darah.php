<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: index.php');
  exit();
}

include './proses/koneksi.php';
include 'proses/hitung_usia_kandungan.php';

$id = $_SESSION['id'];
$status = [];

// Cek apakah nomor HP sudah ada di database
$queryCheck = "SELECT goldar FROM kesehatan_user WHERE id_user = $id";
$stmtCheck = mysqli_prepare($connect, $queryCheck);
mysqli_stmt_execute($stmtCheck);
mysqli_stmt_bind_result($stmtCheck, $goldar);
mysqli_stmt_fetch($stmtCheck);
mysqli_stmt_close($stmtCheck);

// Function untuk merubah format tanggal
function formatTanggal($tanggal_input)
{
  $timestamp = strtotime($tanggal_input);
  $tanggal_format = date("d M Y", $timestamp);

  return $tanggal_format;
}

if (!isset($goldar) || $goldar == null){
  $status = "tidak diketahui";
} else {
  $queryStatus = "SELECT `status`, `goldar`, `tanggal_input`, `taksiran_persalinan` FROM `kesehatan_user` WHERE id_user = ?";
  $stmtStatus = mysqli_prepare($connect, $queryStatus);
  mysqli_stmt_bind_param($stmtStatus, "i", $id);
  mysqli_stmt_execute($stmtStatus);
  mysqli_stmt_bind_result($stmtStatus, $status, $goldar, $tanggal_input, $taksiran_persalinan);
  mysqli_stmt_fetch($stmtStatus);
  mysqli_stmt_close($stmtStatus);

  $tanggal_input = formatTanggal($tanggal_input);
}

if (isset($_GET['success'])) {
  $proccessIsSuccess = true;
  if ($_GET['success'] == "input") {
    $message = "Anda berhasil menambahkan data golongan darah";
  } else if ($_GET['success'] == "edit") {
    $message = "Anda berhasil mengubah data golongan darah";
  } else if ($_GET['success'] == "addPendonor") {
    $message = "Anda berhasil menambahkan data pendonor darah";
  }
} else if (isset($_GET['gagal'])) {
  $proccessIsSuccess = false;
  if ($_GET['gagal'] == "1") {
    $message = "Proses input atau edit data golongan darah gagal dilakukan!!";
  }
}


$query = "SELECT * FROM `pendonor` ORDER BY `nama` ASC";
$sql = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donor Darah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
          <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
          <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link active" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">
            <button type="button" class="btn btn-outline-danger">Logout</button>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="banner banner1" style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('./assets/banner-goldar.jpg');">
    <div>
      <h1>BASKOM SI DORA</h1>
      <br>
      <h3>"Bebaskan Komplikasi Persalinan dengan Siaga Donor Darah"</h3>
    </div>
  </div>
  <div class="content">
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
              <h4>Apa itu "BASKOM SI DORA"?</h4>
            </div>
            <p>
              BASKOM SI DORA yang memiliki kepanjangan Bebaskan Komplikasi Persalinan dengan Siaga Donor Darah merupakan program dari Puskesmas Nagrak untuk memberikan solusi serta monitoring terkait kebutuhan darah ibu menuju persalinan. Dalam mengakses program ini, anda perlu menginputkan golongan darah anda. Formulir input golongan darah dapat anda akses dengan mengklik tombol "Daftarkan Golongan Darah" yang berada di bawah ini.
            </p>
            <p>
              Apabila anda belum mengetahui golongan darah yang anda miliki, anda dapat memilih opsi "Belum Mengetahui" di dalam formulir input golongan darah. Sehingga, setelah ketika anda menekan tombol input, anda akan diarahkan ke whatsapp untuk melakukan perjanjikan pengecekan golongan darah dengan nakes. Setelah pengecekan golongan darah dilakukan, data golongan darah anda akan otomatis diisikan oleh tenaga kesehatan.
            </p>
            <p>
              Di dalam program "BASKOM SI DORA", dibutuhkan juga data pendonor darah dari setiap ibu hamil. Setiap ibu hamil dapat menginputkan pendonor dari orang - orang terdekat mereka seperti suami ataupun anggota keluarga lainnya. Setiap ibu hamil dapat menginpukan lebih dari 1 pendonor darah. Formulir pendonor darah dapat diakses dengan cara mengklik tombol "Daftarkan Pendonor Darah" yang berada di bawah ini.
            </p>
            <p>
              Apabila pendonor darah belum mengetahui golongan darah yang dia miliki, anda dapat memilih opsi "Belum Mengetahui" di dalam formulir input golongan darah pendonor. Sehingga, setelah ketika anda menekan tombol input, anda akan diarahkan ke whatsapp untuk melakukan perjanjikan pengecekan golongan darah pendonor dengan nakes. Setelah pengecekan golongan darah dilakukan, data golongan darah pendonor akan otomatis diisikan oleh tenaga kesehatan.
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-12 col-lg-4 d-flex justify-content-center align-items-center">
          <img src="./assets/logo-goldar-2.png" alt="Logo Hati">
        </div>
        <div class="children-content col-lg-8 col-12">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
            Golongan Darah Anda
            </h1>
            <?php if ($status != 'menunggu') { ?>
              <button type="button" onclick="window.location.href='donor_darah.php'" class="mainButton btn btn-danger">
                <?php echo $status == 'tidak diketahui' ? 'Daftarkan' : 'Edit' ?><p class="m-0">Golongan Darah</p>
              </button>
            <?php } ?>
          </div>
          <div class="alert alert-primary text-center" role="alert">
            <?php if ($status == "tidak diketahui") { ?>
              <h6>Anda belum mendaftarkan (periksa) golongan darah anda, silahkan daftarkan golongan darah anda.</h6>
            <?php } else if ($status == "diketahui"){ ?>
              <h6>Golongan Darah Anda adalah <?php echo strtoupper($goldar); ?></h6>
              <?php if($usia_kandungan == null): ?>
              <h6>Anda belum menginputkan HPHT</h6>
              <?php elseif ($usia_kandungan == "Telah Lahir"): ?>
              <h6>Anda sedang tidak mengandung</h6>
              <?php else: ?>
              <h6>Usia kandungan anda <?= $usia_kandungan ?> pada <?= date('d-m-Y') ?></h6>
              <?php endif; ?>
              <?php if ($usia_kandungan != "Telah Lahir") { ?>
                <div class="alert alert-warning m-0" role="alert" style="border-radius: 20px;">
                  <h6 class="m-0" style="font-weight: bold;" id="countdown"></h6>
                </div>
                <?php if (isset($taksiran_persalinan)) { ?>
                  <script>
                    var taksiranPersalinan = "<?php echo $taksiran_persalinan; ?>";
                  </script>
                <?php } ?>
              <?php } ?>
            <?php } else if ($status == "menunggu") { ?>
              <h6>Anda sedang dalam proses menunggu pemeriksaan golongan darah.</h6>
              <div class="alert alert-warning m-0" role="alert" style="border-radius: 20px;">
                <h6 class="m-0" style="font-weight: bold;" id="countdown"></h6>
              </div>
              <?php if (isset($taksiran_persalinan)) { ?>
                <script>
                  var taksiranPersalinan = "<?php echo $taksiran_persalinan; ?>";
                </script>
              <?php } ?>
            <?php } ?>
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
              Pendonor Darah
            </h1>
            <button type="button" onclick="window.location.href='tambah_pendonor.php'" class="mainButton btn btn-danger">
              Daftarkan Pendonor Darah
            </button>
          </div>


          <?php
          if (mysqli_num_rows($sql) > 0) {
          ?>
          <div class="row mb-2">
            <div class="col-6 d-flex align-items-center">
              <div
                style="width : 15px; min-width: 15px; height: 15px; border-radius: 100%; border: solid 1px; background: #CFE2FF"
                class="border-danger code">
              </div>
              <h6 class="ms-2 m-0 p-0">Pendonor yang anda inputkan.</h6>
            </div>
            <div class="col-6 d-flex align-items-center">
              <div
                style="width : 15px; min-width: 15px; height: 15px; border-radius: 100%; border: solid 1px; background: #FEFFD9"
                class="border-danger code">
              </div>
              <h6 class="ms-2 m-0 p-0">Pendonor yang orang lain inputkan.</h6>
            </div>
          </div>
          <table class="table m-0">
            <thead>
              <tr style="background: #FDFFA0;" div>
                <th class="col-1" scope="col"></th>
                <th class="col-6 text-center" scope="col">Nama</th>
                <th class="col-5 text-center" scope="col">Goldar</th>
              </tr>
            </thead>
          </table>
          <div style="max-height: 325px; width: 100%; overflow-y: scroll">
            <table class="table">
              <tbody style="background: #FEFFD9">
                <?php
                  $data_array = [];
                  while ($data = mysqli_fetch_assoc($sql)) {
                    $data_array[] = $data;
                  }
                  foreach ($data_array as $i => $data) {
                  ?>
                <tr
                  style="background: <?php echo $data['id_user'] == $id ? '#CFE2FF' : '#FEFFD9';  ?>">
                  <th class="col-1" scope="row"><?php echo $i + 1; ?></th>
                  <td class="col-6"><?php echo ucwords($data['nama']); ?></td>
                  <td class="col-5 text-center">
                    <?php echo $data['goldar'] == '-' ? "proses pengecekan" : strtoupper($data['goldar']); ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <?php
          } else {
          ?>
          <div class='alert alert-primary text-center'>
            <h6>TIDAK ADA DATA PENDONOR</h6>
          </div>
          <?php
          }
          ?>


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
  <div class="modal fade" id="bukaVideoPenjelasan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-photo-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Video Penjelasan Menu</h1>
          <button onclick="closeDialogVideoPenjelasan()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe id="videoPenjelasan" style="width : 80vw;" src="https://www.youtube.com/embed/DI1-sAZglqM?si=oUOT6k_5c2PEBWds" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
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
  <?php
  }
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
  <script src="./js/dashboardDonorDarah.js"></script>
  <script src="js/countDown.js"></script>
  <script src="./js/userHome.js"></script>
</body>

</html>