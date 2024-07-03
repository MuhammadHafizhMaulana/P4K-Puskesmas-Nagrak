<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
  header('Location: login_admin.php');
}

if (isset($_GET['status'])) {
  $proccessIsSuccess = true;
  if ($_GET['status'] == "deleted") {
      $message = "Anda berhasil menghapus user.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/admin_User.css">
</head>

<body>
  <nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
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
          <a class="nav-link" href="landing.php">Dashboard</a>
          <a class="nav-link" href="data_user.php">User</a>
          <a class="nav-link" href="listKesehatanUser.php">Kesehatan User</a>
          <a class="nav-link" href="pendonor.php">Pendonor</a>
          <a class="nav-link" href="profile_admin.php">Profile</a>
          <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="container">
      <h1 class="text-center">
        Daftar User
      </h1>
      <br>
      <form id="searchForm" class="container d-flex w-full">
        <div class="input-group mb-3">
          <select id="searchType" name="searchType" class="form-select bg-primary text-white icon-white"
            style="max-width: 125px;" aria-label="Default select example">
            <option value="nama">Nama</option>
            <option value="nomorHP">Nomor HP</option>
          </select>
          <input id="searchValue" name="searchValue" placeholder="Masukan nama user" type="text" class="form-control"
            aria-label="Text input with dropdown button">
        </div>
      </form>
      <div id="spinner" class="container d-flex justify-content-center align-items-center">
        <div class="spinner-border text-white" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div class="boxTable">
        <table style="min-width: 950px; " class="table m-0">
          <thead>
            <tr style="background: #FDFFA0;" div>
              <th class="col-1 text-center" scope="col">No</th>
              <th class="col-2 text-center" scope="col">Nama</th>
              <th class="col-2 text-center" scope="col">Nomor HP</th>
              <th class="col-1 text-center" scope="col">Kesehatan</th>
              <th class="col-1 text-center" scope="col">Pembiayaan</th>
              <th class="col-1 text-center" scope="col">Sarpras</th>
              <th class="col-1 text-center" scope="col">Konsul KB</th>
              <th class="col-2 text-center" scope="col">Aksi</th>
            </tr>
          </thead>
        </table>
        <div class="tableBody" style="height: 325px; min-width: 950px; overflow-y: scroll; background: #FEFFD9">
          <table class="table mb-0">
            <tbody id="userTable" style="background: #FEFFD9">
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin menghapus pengguna ini?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
              onclick="confirmDelete()">Hapus</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <button style="display: none;" id="buttonAlert" type="button" class="btn btn-primary" data-bs-toggle="modal"
    data-bs-target="#exampleModal"></button>

  <?php
  if (isset($_GET['status'])) { ?>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-primary" id="exampleModalLabel">
            <?php echo $proccessIsSuccess ? "BERHASIL" : "GAGAL" ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-primary text-center" role="alert">
            <?php echo $message ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  }
  ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/0KvBfySwF7mio5Y5eUll8Eka1pF7V1F5TkPHTb" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-Tny1Hvt0VS4eN0C4zPWFlpJqsmwy28HGw2DqynzwiG2R8WpNSpNq5D7r1wFfKxQ1" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>
<script src="../js/admin_Data_User.js"></script>
</body>

</html>