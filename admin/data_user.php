<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
  header('Location: login_admin.php');
}

include '../proses/koneksi.php';

// Query untuk mengambil semua data dari tabel user
$query = "SELECT * FROM user";
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/dashboard_Admin.css">
</head>

<body>
  <nav class="my-navbar navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">
        <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
          </svg></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
          <a class="nav-link" href="data_user.php">User</a>
          <a class="nav-link" href="kesehatan_user.php">Kesehatan User</a>
          <a class="nav-link" href="donor_darah.php">Tambah Pendonor</a>
          <a class="nav-link" href="profile.php">Profile</a>
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
      <div style="overflow-x:scroll">
        <table style="min-width: 950px; " class="table m-0">
          <thead>
            <tr style="background: #FDFFA0;" div>
              <th class="col-1 text-center" scope="col"></th>
              <th class="col-2 text-center" scope="col">Nama</th>
              <th class="col-1 text-center" scope="col">Usia</th>
              <th class="col-2 text-center" scope="col">Nomor HP</th>
              <th class="col-2 text-center" scope="col">Alamat</th>
              <th class="col-2 text-center" scope="col">Kesehatan</th>
              <th class="col-2 text-center" scope="col">Aksi</th>
            </tr>
          </thead>
        </table>
        <div style="max-height: 325px; min-width: 950px; overflow-y: scroll">
          <table class="table">
            <tbody style="background: #FEFFD9">
              <?php
              $i = 1;
              while ($data = mysqli_fetch_assoc($result)) {
              ?>
                <tr>
                  <th class="col-1 text-center" scope="row"><?php echo $i; ?></th>
                  <td class="col-2"><?php echo strtoupper($data['nama']); ?></td>
                  <td class="col-1"><?php echo $data['usia']; ?> TAHUN</td>
                  <td class="col-2 text-center"><?php echo $data['nomorHP']; ?></td>
                  <td class="col-2"><?php echo strtoupper($data['alamat']); ?></td>
                  <td class="col-2 justify-content-center">
                    <div class="mx-auto" style="width: min-content;">
                      <a href="kesehatan_user.php?id=<?= $data['id'] ?>" style="width: 27px">
                        <button type="button" class="p-0 btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-lg" viewBox="0 0 16 16">
                            <path d="m9.708 6.075-3.024.379-.108.502.595.108c.387.093.464.232.38.619l-.975 4.577c-.255 1.183.14 1.74 1.067 1.74.72 0 1.554-.332 1.933-.789l.116-.549c-.263.232-.65.325-.905.325-.363 0-.494-.255-.402-.704zm.091-2.755a1.32 1.32 0 1 1-2.64 0 1.32 1.32 0 0 1 2.64 0" />
                          </svg></button>
                      </a>
                    </div>
                  </td>
                  <td class="col-2">
                    <div class="d-flex justify-content-evenly">
                      <a href="kesehatan_user.php?id=<?= $data['id'] ?>">
                        <button type="button" class="btn btn-outline-primary">
                          <div style="width: 27px; height: 27px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                              <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                            </svg>
                          </div>
                        </button>
                      </a>
                      <!-- <a href="proses/hapus_user.php?id=<?= $data['id'] ?>"> -->
                        <button onclick="showModal(<?= $data['id'] ?>)" type="button" class="btn btn-outline-primary">
                          <div style="height: 27px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                              <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                            </svg>
                          </div>
                        </button>
                      <!-- </a> -->
                      </div>
                  </td>
                </tr>
              <?php
                $i++;
              } ?>
      </tbody>
      </table>
      </div>
      </div>
  </div>
  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
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
          <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
        </div>
      </div>
    </div>
  </div>
  <tr>
    <h4><a href="landing.php">kembali</a></h4>
  </tr>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
  <script>
    let userIdToDelete = null;

    function showModal(userId) {
      userIdToDelete = userId;
      var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'), {
        keyboard: false
      });
      myModal.show();
    }

    function confirmDelete() {
      if (userIdToDelete !== null) {
        window.location.href = 'proses/hapus_user.php?id=' + userIdToDelete;
      }
    }
  </script>
</body>

</html>