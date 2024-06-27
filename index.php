<?php
session_start();
include 'proses/koneksi.php';

// Set Cookie
if(isset($_COOKIE["yudi"]) && isset($_COOKIE["key"])){
    $yudi = $_COOKIE['yudi'];
    $key = $_COOKIE['key'];

    // Ambil nomor hp berdasarkan id
    $query = "SELECT `nomorHP` FROM user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $yudi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        $row = mysqli_fetch_assoc($result);

        // cek cookie dan nomor HP
        if($key === hash('sha256', $row['nomorHP'])) {
            $_SESSION['status'] = 'login';
            $_SESSION['id'] = $yudi;
            header('Location: home.php');
            exit();
        }
    }
}

// Pengecekan session
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header('Location: home.php');
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/login_Daftar.css">
</head>

<body>
    <div id="formLogin">
        <h1 style="font-weight: bold; font-size: xxx-large">Selamat Datang</h1>
        <h5 style="font-weight: bold">
            Website Program Perencanaan Persalinan dan Pencegahan Komplikasi (P4K)
            Puskesmas Nagrak!
        </h5>
        <br />
        <?php
        //pesan jika terjadi kesalahan
        if (isset($_GET['pesan'])) {
            if ($_GET['pesan'] == "gagal") {
                echo "<div class='alert alert-danger'> Login gagal. Username atau password salah.</div>";
            } elseif ($_GET['pesan'] == "logout") {
                echo "<div class='alert alert-danger'> Anda telah berhasil logout.</div>";
            } elseif ($_GET['pesan'] == "belum_login") {
                echo "<div class='alert alert-danger'> Anda harus login terlebih dahulu untuk akses halaman utama.</div>";
            }
        }
        // pesan jika sukses
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<div class='alert alert-success'>Data berhasil ditambahkan!</div>";
        }
        ?>
        <p>
            Masuk ke akun anda
        </p>
        <form action="proses/login_proses.php" method="post">

            <div class="form-group">
                <label for="nomorHP">Masukan nomor HP</label>
                <input oninput="formValid()" type="text" class="form-control registrasi-form" id="nomer" name="nomorHP" placeholder="nomor HP" required>
            </div>

            <div class="form-group">
                <label for="password">Masukan password</label>
                <input oninput="formValid()" type="password" class="form-control registrasi-form" id="password" name="password" placeholder="password" required>
            </div>
            <br>

                <input type="checkbox" id="rememberme" name="rememberme">
                <label for="rememberme">Ingat saya?</label>

            <br /><br>

            <button onclick="openSpinner()" id="submitButton" disabled type="submit" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                </svg>
            </button>
        </form>
        <div style="display: flex">
            <p>Belum punya akun?&nbsp;</p>
            <a href="daftar.php">Registrasi</a>
        </div>
    </div>




    <script src="./js/loginDanDaftar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>