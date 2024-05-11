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
    <title>Login Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/loginDaftar.css">
</head>

<body>
    <div id="formRegistrasi">
        <h1 style="
            font-weight: bold;
            font-size: xxx-large;
        ">
           Tambah Pendonor
        </h1>
        <br>
        <?php
        if (isset($_GET['gagal'])) {
            if ($_GET['gagal'] == "nomorHP") {
                echo "<div class='alert alert-danger'>Nomor HP telah terdaftar. Silahkan daftar menggunakan nomor HP yang lain.</div>";
            }
        }
        ?>
        <p>
            Untuk menambah pendonor, isi form berikut:
        </p>
        <form action="proses/tambah_donor_proses.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control registrasi-form" id="nama" name="nama" placeholder="Masukkan nama lengkap pendonor" required>
            </div>

            <div class="form-group">
                <input type="text" class="form-control registrasi-form" id="nomer" name="nomorHP" placeholder="Masukkan nomor HP pendonor" required>
            </div>

            <div class="form-group">
                <textarea class="form-control registrasi-form" name="alamat" id="alamat" placeholder="Masukkan alamat ibu" required></textarea>
            </div>

            <br>

            <button onclick="openSpinner()" id="submitButton" type="submit" disabled type="submit" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                </svg>
            </button>
        </form>

        <div style="
            display: flex;
        ">
            <p>Sudah punya akun?&nbsp;</p>
            <a style="
                cursor: pointer;
            " href="index.php">Login</a>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="./js/loginDaftar.js"></script>
</body>

</html>