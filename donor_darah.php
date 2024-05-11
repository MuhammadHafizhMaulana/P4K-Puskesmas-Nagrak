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
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/donorDarah.css">
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
  <div id="formDonorDarah">
        <h1 style="font-weight: bold; font-size: xxx-large">Daftarkan Golongan Darah</h1>
        <br />
        <?php 
                            include 'proses/koneksi.php';
                            $id = $_SESSION['id'];
                            $query = "SELECT `goldar`, `usia_kandungan` FROM `kesehatan_user` WHERE `id_user` = '$id'";


                            $sql = mysqli_query($connect, $query);

                            // Periksa apakah ada baris data yang ditemukan
                            if(mysqli_num_rows($sql) > 0) {
                                // Data ditemukan, ambil data dari hasil query
                                $data = mysqli_fetch_assoc($sql);
                                // Lakukan sesuatu dengan data yang ditemukan
                                echo "<div class='alert alert-success'> Golongan darah anda " . $data['goldar'] . "</div>";
                                echo "<div class='alert alert-success'> Golongan darah anda " . $data['usia_kandungan'] . " Minggu</div>";
                            } else {
                                // Data tidak ditemukan, lakukan sesuatu (misalnya, tampilkan pesan)
                                echo "<div class='alert alert-danger'> Data belum diinputkan.</div>";
                            }  
                        ?>
        <p>
            Masuk informasi golongan darah anda:
        </p>
        <form action="proses/input_gol_darah_proses.php" method="post" >

        <div class="form-group">
        <label >Pilih golongan darah anda</label>
        <select class="form-select" aria-label="Default select example">
            <option value="-" selected>Belum Mengetahui</option>
            <option value="a">A</option>
            <option value="o">O</option>
            <option value="b">B</option>
        </select>
        </div>

            <div class="form-group">
                <label for="usiakandungan"> Berapa Usia Kandungan Anda (Dalam Minggu)</label>
                <input type="number" class="form-control" id="usiakandungan" name="usia_kandungan" placeholder="Masukan usia usia_kandungan anda" required>
            </div>

            <br />

            <button type="submit" class="btn btn-danger">INPUT</button>
        </form>
    </div>

</body>

</html>