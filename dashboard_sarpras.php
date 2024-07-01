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

// Function untuk merubah format tanggal
function formatTanggal($tanggal_input)
{
    $timestamp = strtotime($tanggal_input);
    $tanggal_format = date("d M Y", $timestamp);

    return $tanggal_format;
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

$tanggal_usg = formatTanggal($tanggal_usg);

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
          <a class="nav-link active" href="dashboard_sarpras.php">Sarpras</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="dashboard_kb.php">Konsul KB</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
      </div>
  </nav>

  <div class="banner banner2" style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('./assets/banner-sarpras.jpg');">
    <div>
      <h1>ADIPURA</h1>
      <br>
      <h3>"Atasi, Dampingi Ibu Punahkan Pendarahan"</h3>
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
              <h4>Apa itu "ADIPURA"?</h4>
            </div>
            <p>
              ADIPURA yang memiliki kepanjangan Atasi Dampingi Ibu Punahkan Pendarahan merupakan program dari Puskesmas Nagrak untuk memastikan kesiapan sarana prasana persalinan. Data yang perlu diinputkan antara lain : 
            </p>
            <ul>
              <li>
                <p>Transportasi yang akan digunakan</p>
              </li>
              <li>
                <p>Nama dari supir transportasi</p>
              </li>
              <li>
                <p>Nomor handphone dari supir transportasi</p>
              </li>
              <li>
                <p>Nama pendamping saat persalinan</p>
              </li>
              <li>
                <p>Penolong persalinan</p>
              </li>
              <li>
                <p>Data USG jika ada</p>
              </li>
            </ul>
            <p>
              Perdarahan pasca salin merupakan penyebab kematian nomor satu pada angka kematian ibu. Kematian akibat perdarahan pasca salin selain disebabkan oleh faktor medis juga dapat disebabkan oleh multifaktorial salah satunya terlambat mendapatkan penanganan. Hal ini dapat dicegah dengan terdatanya transportasi dengan lengkap sehingga pada saat persalinan semua telah disiapkan dengan baik.
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-12 col-lg-4 d-flex justify-content-center align-items-center">
          <img src="./assets/logo-sarpras.png" alt="Logo Hati">
        </div>
        <div class="children-content col-lg-8 col-12">
          <div class="d-flex align-items-end justify-content-between mb-2">
            <h1 class="m-0 p-0">
            Data Sarpras Anda
            </h1>
            <button type="button" onclick="window.location.href='form_sarpras.php'" class="mainButton btn btn-danger">
              <?php echo $sarprasData ? 'Edit' : 'Daftarkan' ?><p class="m-0">Data Sarpras</p>
            </button>
          </div>
          <div class="alert alert-primary <?= $sarprasData ? '' : 'text-center'?>" role="alert">
            <?php if ($sarprasData) { ?>
              <ul>
                <li>
                  <h6>Transportasi</h6>
                </li>
                <p>Transportasi yang anda pilih adalah <?php echo $sarprasData['transportasi'] ? $sarprasData['transportasi'] : '-' ?></p>
                <li>
                  <h6>Supir Transportasi</h6>
                </li>
                <p>
                  Supir transportasi yang anda gunakan yaitu <?php echo $sarprasData['nama_supir'] ? ucwords($sarprasData['nama_supir']) : '-' ?> dengan nomor handphone <?php echo $sarprasData['no_supir'] ? $sarprasData['no_supir'] : '-' ?>.
                </p>
                <li>
                  <h6>Pendamping Persalinan</h6>
                </li>
                <p>
                  Pendamping persalinan anda yaitu <?php echo $sarprasData['nama_pendamping'] ? ucwords($sarprasData['nama_pendamping']) : '-' ?> dengan nomor handphone <?php echo $sarprasData['no_pendamping'] ? $sarprasData['no_pendamping'] : '-' ?>.
                </p>
                <li>
                  <h6>Tempat Persalinan</h6>
                </li>
                <p>
                  Tempat persalinan yang adna pilih adalah <?php echo $sarprasData['tujuan'] ? $sarprasData['tujuan'] : '-' ?>.
                </p>
                <li>
                  <h6>Penolong Persalinan</h6>
                </li>
                <p>
                  <?php echo $sarprasData['penolong'] ? "Penolong persalinan anda adalah " . $jenis_penolong . " dengan nama " . ucwords($nama_penolong) : '-'; ?><br>
                </p>
                <li>
                  <h6>Data USG</h6>
                </li>
                <p>
                  <?php if($status_usg === 'pernah') { ?>
                  <?php echo $sarprasData['usg'] ? "Anda pernah melakukan USG pada ". $tanggal_usg. " dengan umur USG yaitu ". $umur_usg . " minggu, dengan kondisi USG ". $hasil_usg : '-' ?><br>
                  <?php } else if ($status_usg === 'belum') {?>
                  Tidak ada data USG.
                  <?php } ?>
                </p>
              </ul>
            <?php } else { ?>
              Anda belum mendaftarkan data sarpras anda, silahkan daftarkan data sarpras anda.
            <?php } ?>
            <!-- <?php if ($status == "tidak diketahui") { ?>
              <h6>Anda belum mendaftarkan (periksa) golongan darah anda, silahkan daftarkan golongan darah anda.</h6>
            <?php } else if ($status == "diketahui"){ ?>
              <h6>Golongan Darah Anda adalah <?php echo strtoupper($goldar); ?></h6>
              <?php if($usia_kandungan == null): ?>
              <h6>Anda belum menginputkan HPHT</h6>
              <?php elseif ($usia_kandungan == "LAHIR"): ?>
              <h6>Anda sedang tidak mengandung</h6>
              <?php else: ?>
              <h6>Usia kandungan anda <?= $usia_kandungan ?> pada <?= date('d-m-Y') ?></h6>
              <?php endif; ?>
              <div class="alert alert-warning m-0" role="alert" style="border-radius: 20px;">
                <h6 class="m-0" style="font-weight: bold;" id="countdown"></h6>
              </div>
              <?php if (isset($taksiran_persalinan)) { ?>
                <script>
                  var taksiranPersalinan = "<?php echo $taksiran_persalinan; ?>";
                </script>
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
            <?php } ?> -->
          </div>
        </div>
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