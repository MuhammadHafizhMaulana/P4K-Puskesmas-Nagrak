<?php
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login_admin') { // Periksa apakah 'status' telah di-set dan bernilai 'login'
    header('Location: landing.php');
    exit(); // Penting untuk diikuti dengan exit() setelah header redirect
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/loginDaftar.css">
</head>

<body>
    <div id="formLogin">
        <h1 style="font-weight: bold; font-size: xxx-large">Selamat Datang Admin</h1>
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
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username anda" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <br />

            <button onclick="openSpinner()" type="submit" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                </svg>
            </button>
        </form>
    </div>




    <script src="./js/loginDaftar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>