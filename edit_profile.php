<?php 
session_start();
if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
}

include 'proses/koneksi.php';

$id = $_SESSION['id'];

// Menyiapkan query dengan prepared statement
$query = "SELECT * FROM `user` WHERE `id` = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Mengambil data dari hasil query
$data = mysqli_fetch_assoc($result);
?>
<head>
    <meta charset="UTF-8">
    <meta  name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/general_Form.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="./assets/logo-kemenkes.png" alt="Logo Kemenkes">
                <img src="./assets/logo-puskesmas-nagrak.png" alt="Logo Puskesmas Nagrak">
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
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link" href="dashboard_pembiayaan.php">Pembiayaan</a>
                    <a class="nav-link" href="dashboard_sarpras.php">Sarpras</a>
                    <a class="nav-link" href="dashboard_donor_darah.php">Donor Darah</a>
                    <a class="nav-link" href="dashboard_kb.php">Konsultasi KB</a>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="formDonorDarah">
        
        <div>
            <h1>Halaman Profile</h1>
            <br>
            <table class="table-primary">
            <form class="form-login poppins mt-5" action="proses/edit_profile_proses.php" method="post">
            <tbody>
                <tr>
                    <th>Nama</th>
                    <td>:</td>
                    <td> <input type="text" name="nama" class="input-login" placeholder="Nama Lengkap" value="<?=$data['nama']?>"></td>
                </tr>
                <tr>
                    <th>Usia</th>
                    <td>:</td>
                    <td><input type="number" min="0" name="usia" class="input-login" placeholder="usia" value="<?=$data['usia']?>"></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>:</td>
                    <td>
                      <p><textarea name="alamat" cols="30" rows="10" class="input-login" placeholder="Alamat" ><?=$data['alamat']?></textarea></p>
                    </td>
                </tr>
                <tr>
                    <th> <div class="button-input">
                    <button type="submit" class="btn btn-primary">EDIT</button>
                </div></th>
                </tr>
               
            </tbody>
            </form>
    </table>
                
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>