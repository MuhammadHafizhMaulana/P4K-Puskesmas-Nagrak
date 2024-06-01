<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sarpras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="css/dashboardCustomer.css">
</head>

<body>
  <nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="assets/logo-kemenkes.png" alt="Logo Kemenkes">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
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
  <div class="content">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-12 col-lg-6 d-flex justify-content-center">
          <img src="./assets/logo2-kemenkes.png" alt="Logo Kemenkes">
        </div>
        <div class="col-12 col-lg-6">
          <h1>Sarana Prasarana</h1>
          <p>Lengkapi data berikut untuk melengkapi data anda</p>
          <h2>Persiapan Rujukan pada HPL</h2><br>

          <form method="post" action="proses/sarpras_proses.php">
            <label for="transportasi">Pilih Trnasportasi anda</label>
            <select id="transportasi" name="transportasi" class="form-select" aria-label="Default select example" required onchange="updateForm()">
              <option value="">Pilih Jenis Transportasi Anda</option>
              <option value="ambulance_desa">Ambulance Desa</option>
              <option value="ambulance_pkm">Ambulance PKM</option>
              <option value="kendaraan_pribadi">Kendaraan Pribadi</option>
            </select>
            
            <label for="nama_supir">Nama Supir</label>
            <input type="text" name="nama_supir" class="form-control" placeholder="Nama Supir" required >
            <label for="no_supir">Nomer Supir</label>
            <input type="text" name="no_supir" class="form-control" placeholder="Nomer Supir" required ><br>
            
            <label for="nama_pendamping">Nama Pendamping</label>
            <input type="text" name="nama_pendamping" class="form-control" placeholder="Nama Pendamping" required >
            <label for="no_pendamping">Nomer Pendamping</label>
            <input type="text" name="no_pendamping" class="form-control" placeholder="Nomer Pendamping" required ><br>
            
            <label for="tujuan">Pilih Tujuan anda</label>
            <select id="tujuan" name="tujuan" class="form-select" aria-label="Default select example" required onchange="updateForm()">
              <option value="">Pilih Tujuan Anda</option>
              <option value="sekarwangi">RS Sekar Wangi</option>
              <option value="dkh">RS DKH</option>
              <option value="cibadak">RS Cibadak</option>
            </select>
            <input type="submit" value="Submit">
          </form>
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