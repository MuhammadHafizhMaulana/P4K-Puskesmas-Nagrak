<?php
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') { // Periksa apakah 'status' telah di-set dan bernilai 'login'
    header('Location: home.php');
    exit(); // Penting untuk diikuti dengan exit() setelah header redirect
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
            Selamat Datang
        </h1>
        <h5 style="
        font-weight: bold;
        ">
            Website Program Perencanaan Persalinan dan Pencegahan Komplikasi (P4K) Puskesmas Nagrak!
        </h5>
        <br>
        <?php
        if (isset($_GET['gagal'])) {
            if ($_GET['gagal'] == "nomorHP") {
                echo "<div class='alert alert-danger'>Nomor HP telah terdaftar. Silahkan daftar menggunakan nomor HP yang lain.</div>";
            }
        }
        ?>
        <p>
            Untuk membuat akun, isi form berikut:
        </p>
        <form action="proses/daftar_proses.php" method="post">
            <div class="form-group">
                <input oninput="formValid()" type="text" class="form-control registrasi-form" id="nama" name="nama" placeholder="Masukkan nama lengkap ibu" required>
            </div>

            <div class="form-group">
                <input oninput="formValid()" type="number" min="0" class="form-control registrasi-form" id="umur" name="usia" placeholder="Masukkan usia ibu" min="0" required>
            </div>

            <div class="form-group">
                <input oninput="formValid()" type="text" class="form-control registrasi-form" id="nomer" name="nomorHP" placeholder="Masukkan nomor HP ibu" required>
            </div>

            <div class="form-group">
                <textarea oninput="formValid()" class="form-control registrasi-form" name="alamat" id="alamat" placeholder="Masukkan alamat ibu" required></textarea>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password" required>
                <div id="passwordAlert" class="form-text text-danger"></div>
            </div>

            <div class="form-group">
                <label for=""> Masukkan HPHT</label>
                <input type="date" oninput="formValid()" class="form-control registrasi-form" name="hpht" id="hpht" placeholder="HPHT" required>
            </div>

            <div class="form-group">
                <input type="number" class="form-control" id="number" name="minggu" placeholder="Masukan Usia Kandungan (Minggu)" required>
                <div id="passwordAlert" class="form-text text-danger"></div>
            </div>

            <div class="form-group">
                <input type="number" class="form-control" id="number" name="hari" placeholder="Brapa hari (Hari)" required>
                <div id="passwordAlert" class="form-text text-danger"></div>
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