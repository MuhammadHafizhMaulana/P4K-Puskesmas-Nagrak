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
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center">
        <main class="row mt-5 justify-content-center align-items-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Donor Darah</h5>
                    </div>
                    <div class="card-body">
                        <form action="proses/input_gol_darah_proses.php" method="post" >
                            <div class="mb-3">
                                <label for="nama" class="form-label">Masukkan Golongan Darah Anda</label><br>
                                <input type="radio" id="golongan_a" name="goldar" value="a">
                                <label for="golongan_a">Golongan A</label><br>

                                <input type="radio" id="golongan_b" name="goldar" value="b">
                                <label for="golongan_b">Golongan B</label><br>

                                <input type="radio" id="golongan_ab" name="goldar" value="ab">
                                <label for="golongan_ab">Golongan AB</label><br>

                                <input type="radio" id="golongan_o" name="goldar" value="o">
                                <label for="golongan_o">Golongan O</label><br>
                                

                                <div class="form-group">
                                <label for="usiakandungan"> Berapa Usia Kandungan Anda (Dalam Minggu)</label>
                                <input type="number" class="form-control" id="usiakandungan" name="usia_kandungan" placeholder="Masukan usia usia_kandungan anda" required>

                                <button type="submit" class="btn btn-primary">INPUT</button>
                                </div>

                                
                        </form>
                        <br>
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
                        <br>
                        <?php
                            //pesan jika terjadi kesalahan
                            if (isset($_GET['pesan'])) {
                                if ($_GET['pesan'] == "gagal") {
                                    echo "<div class='alert alert-danger'> Login gagal. Username atau password salah.</div>";
                            }
                            }
                            // pesan jika sukses
                            if (isset($_GET['success']) && $_GET['success'] == 1) {
                                echo "<div class='alert alert-success'>Data berhasil ditambahkan!</div>";
                            }
                        ?>
                        <h2><a href="home.php">Kembali ke Home</a></h2>

                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>