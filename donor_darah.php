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
    <title>Donor Darah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/loginDaftar.css">
</head>

<body>
    <div id="formRegistrasi">
        <h1 style="
            font-weight: bold;
            font-size: xxx-large;
        ">
            Input Golongan Darah
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
            Untuk menambah golongan darah, isi form berikut:
        </p>
        <form action="proses/input_gol_darah_proses.php" method="post">
            <div class="form-group">
                <div class="mb-1">
                    <label for="nama" class="form-label">Masukkan Golongan Darah Anda</label>
                    
                    <input type="radio" id="golongan_a+" name="goldar" value="a+">
                    <label for="golongan_a">Golongan A+</label><br>

                    <input type="radio" id="golongan_b+" name="goldar" value="b+">
                    <label for="golongan_b">Golongan B+</label><br>

                    <input type="radio" id="golongan_ab+" name="goldar" value="ab+">
                    <label for="golongan_ab">Golongan AB+</label><br>

                    <input type="radio" id="golongan_o+" name="goldar" value="o+">
                    <label for="golongan_o">Golongan O+</label><br>

                    <input type="radio" id="golongan_a-" name="goldar" value="a-">
                    <label for="golongan_a">Golongan A-</label><br>

                    <input type="radio" id="golongan_b-" name="goldar" value="b-">
                    <label for="golongan_b">Golongan B-</label><br>

                    <input type="radio" id="golongan_ab-" name="goldar" value="ab-">
                    <label for="golongan_ab">Golongan AB-</label><br>

                    <input type="radio" id="golongan_o-" name="goldar" value="o-">
                    <label for="golongan_o">Golongan O-</label><br><br>

                    <label for="usia_kandungan">Usia Kandungan Anda</label><br>
                    <input type="number" id="usia_kandungan" name="usia_kandungan"><br><br>

                    <button type="submit" class="btn btn-primary">INPUT</button>


                    <div class="form-group">
                    </div>

                    <br>
        </form>
        <?php 
            include 'proses/koneksi.php';
            $id = $_SESSION['id'];
            $query = "SELECT `goldar`, `usia_kandungan`, `tanggal_input` FROM `kesehatan_user` WHERE `id_user` = '$id'";


            $sql = mysqli_query($connect, $query);

            // Periksa apakah ada baris data yang ditemukan
            if(mysqli_num_rows($sql) > 0) {
            // Data ditemukan, ambil data dari hasil query
            $data = mysqli_fetch_assoc($sql);
            // Lakukan sesuatu dengan data yang ditemukan
            echo "<div class='alert alert-success'> Golongan darah anda " . $data['goldar'] . "</div>";
            echo "<div class='alert alert-success'> Usia Kandungan anda " . $data['usia_kandungan'] . " Minggu pada " . $data['tanggal_input'] . "</div>";

            } else {
            // Data tidak ditemukan, lakukan sesuatu (misalnya, tampilkan pesan)
             echo "<div class='alert alert-danger'> Data belum diinputkan.</div>";
            }  
            ?>

            <h2><a href="home.php">Home</a></h2>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="./js/loginDaftar.js"></script>
</body>

</html>