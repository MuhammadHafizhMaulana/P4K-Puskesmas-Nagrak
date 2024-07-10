<?php 

    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
}

    include 'proses/koneksi.php';
    include 'proses/hitung_usia_kandungan.php';
    $id = $_SESSION['id'];
    $query = "SELECT * FROM `user` WHERE `id` = '$id' ";
    $sql = mysqli_query($connect, $query);
    $data = mysqli_fetch_assoc($sql);

    $query = "SELECT * FROM `pembiayaan` WHERE `id_user` = '$id' ";
    $sql = mysqli_query($connect, $query);
    $pembiayaan = mysqli_fetch_assoc($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/generalForm.css">
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
                <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
                <a class="nav-link" href="dashboard_kb.php">Konsultasi KB</a>
                <a class="nav-link active" href="profile.php">Profile</a>
                <a class="nav-link" href="proses/logout.php">
                    <button type="button" class="btn btn-outline-danger">Logout</button>
                </a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        <?php if ($pembiayaan) { ?>
            <div  id="profilePhoto" style="width: 70%; max-width: 250px; background-image: url('./proses/check_pas_foto.php'); background-size: cover; background-position: center;" class="border border-2 border-primary rounded-circle"></div>
        <?php } ?>
        <br>
        <div class="w-100">
            <h3 style="font-weight: bold;">Detail Data Diri</h3>
            <br>
            <div class="row">
            <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start"><?=$data['nama']?></div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Usia</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start"><?=$data['usia']?> tahun</div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nomor HP</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start"><?=$data['nomorHP']?></div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-5 text-start fw-bolder">Alamat</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start"><?=$data['alamat']?></div>
            </div>
        </div>
    </div>
    
  <script>
    function updateProfilePhotoHeight() {
        var items = document.getElementById('profilePhoto');
        items.style.height = items.offsetWidth * 1 + 'px';
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateProfilePhotoHeight()
    })

    window.addEventListener('resize', () => {
        updateProfilePhotoHeight();
    })
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>
</html>