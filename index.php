<?php
    session_start();
    if(isset($_SESSION['status']) && $_SESSION['status'] == 'login'){ // Periksa apakah 'status' telah di-set dan bernilai 'login'
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
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto mt-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Login Pasien</h5>
                    </div>
                    <div class="card-body">
                        <form action="login_proses.php" method="post">
                            <div class="mb-3">
                                <label for="nomer" class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" id="nomer" name="nomorHP" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">LOGIN</button><br><br><br>
                            <div class="mb-3">
                                <input type="checkbox" class="" id="remember" name="remember" >
                                <label for="remember" class="form-label">Remeber me</label>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p>Belum punya akun? <a href="daftar.php">Daftar Sekarang</a></p>
                    </div>
                    <?php
                    //pesan jika terjadi kesalahan
                        if(isset($_GET['pesan'])) {
                        if ($_GET['pesan'] == "gagal")
                        {
                            echo "<div class='alert alert-danger'> Login gagal. Username atau password salah.</div>";
                        } elseif ($_GET['pesan'] == "logout"){
                            echo "<div class='alert alert-danger'> Anda telah berhasil logout.</div>";
                        } elseif ($_GET['pesan'] == "belum_login"){
                            echo "<div class='alert alert-danger'> Anda harus login terlebih dahulu untuk akses halaman utama.</div>";
                        } 
                    }
                    // pesan jika sukses
                    if(isset($_GET['success']) && $_GET['success'] == 1){
                        echo "<div class='alert alert-success'>Data berhasil ditambahkan!</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
