<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: index.php');
}

include './proses/koneksi.php';

$id = $_SESSION['id'];
$status = [];

// Cek apakah nomor HP sudah ada di database
$queryCheck = "SELECT COUNT(*) AS total FROM kesehatan_user WHERE id_user = $id";
$stmtCheck = mysqli_prepare($connect, $queryCheck);
mysqli_stmt_execute($stmtCheck);
mysqli_stmt_bind_result($stmtCheck, $total);
mysqli_stmt_fetch($stmtCheck);
mysqli_stmt_close($stmtCheck);

if ($total == 0) {
    $status = "tidak diketahui";
} else {
    $queryStatus = "SELECT status, goldar FROM kesehatan_user WHERE id_user = ?";
    $stmtStatus = mysqli_prepare($connect, $queryStatus);
    mysqli_stmt_bind_param($stmtStatus, "i", $id);
    mysqli_stmt_execute($stmtStatus);
    mysqli_stmt_bind_result($stmtStatus, $status, $goldar);
    mysqli_stmt_fetch($stmtStatus);
    mysqli_stmt_close($stmtStatus);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="css/dashboard_donor_darah.css">
</head>

<body>
  <nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
          </svg></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
          <a class="nav-link" href="donor_darah.php">DonorDarahTambah</a>
          <a class="nav-link" href="profile.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="banner">
    <div>
      <h1>Donor Darah</h1>
      <br>
      <h5>Website Program Perencanaan Persalinan dan Pencegahan Komplikasi (P4K) Puskesmas Nagrak!</h5>
    </div>
  </div>
  <div class="content">
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-4 d-flex justify-content-center align-items-center">
            <img src="./assets/logo-hatii.png" alt="Logo Hati">
        </div>
        <div class="children-content col-8">
            <h1>
                Golongan Darah Anda!
            </h1>
            <?php 
                if($status == "tidak diketahui")
                {
            ?>
            <p>
                Anda belum mendaftarkan (periksa) golongan darah anda, daftarkan di bawah
            </p>
            <button onclick="window.location.href='donor_darah.php'" type="button" class="btn btn-danger">
                Daftarkan Golongan Darah
            </button>
            <?php
            } 
            else if($status == "diketahui")
            {
            ?>
            <p>
                Golongan Darah Anda adalah <?php echo $goldar ?>
            </p>
            <button onclick="window.location.href='donor_darah.php'" type="button" class="btn btn-danger">
                Edit Golongan Darah
            </button>
            <?php
            } else if ($status == "menunggu")
            {
            ?>
            <p>
                Anda sedang menunggu proses pemeriksaan golongan darah.
            </p>
            <?php }?>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row d-flex align-items-center mt-5">
        <div class="col-4 d-flex justify-content-center align-items-center">
            <img src="./assets/logo-donor-darah.png" alt="Logo Donor Darah">
        </div>
        <div class="children-content col-8">
            <h1>
                Pendonor Darah Anda!
            </h1>
            <p>
                Pendonor darah anda belum didaftarkan
            </p>
            <button type="button" class="btn btn-danger">
                Daftarkan Pendonor Darah Anda
            </button>
        </div>
      </div>
    </div>
  </div>
  <div class="row" id="footer">
    <div class="col"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>