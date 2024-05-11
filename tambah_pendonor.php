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
    <title>Tambah Pendonor</title>
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
            Tambah Pendonor
        </h1>
        <br>
        <p>
            Untuk menambah pendonor, isi form berikut:
        </p>
        <form action="proses/tambah_pendonor_proses.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control registrasi-form" id="nama" name="nama"
                    placeholder="Masukkan nama lengkap pendonor" required>
            </div>

            <div class="form-group">
                <input type="text" class="form-control registrasi-form" id="nomer" name="nomorHP"
                    placeholder="Masukkan nomor HP pendonor" required>
            </div>

            <div class="form-group">
                <div class="mb-3">
                    <label for="nama" class="form-label">Masukkan Golongan Darah pendonor</label><br>
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
                    <label for="golongan_o">Golongan O-</label><br>

                    <button type="submit" class="btn btn-primary">INPUT</button>


                    <div class="form-group">
                    </div>

                    <br>
        </form>
        <?php 
        include 'proses/koneksi.php';
        $id = $_SESSION['id'];
        $query = "SELECT * FROM `pendonor` WHERE `id_user` = '$id'";
        $sql = mysqli_query($connect, $query);

        // Periksa apakah ada baris data yang ditemukan
        if(mysqli_num_rows($sql) > 0) {
            // Tampilkan judul
            echo "<h1>Data Pendonor</h1>";
            
            // Mulai iterasi melalui setiap baris data
            while($data = mysqli_fetch_assoc($sql)) {
        ?>
                <tbody>
                    <tr>
                        <th>Nama</th>
                        <td>:</td>
                        <td><?=$data['nama']?></td><br>
                    </tr>
                    <tr>
                        <th>Nomor HP</th>
                        <td>:</td>
                        <td><?=$data['nomorHP']?></td><br>
                    </tr>
                    <tr>
                        <th>Golongan Darah</th>
                        <td>:</td>
                        <td><?=$data['goldar']?></td><br>
                    </tr>
                    <tr>
                        <th> <a href="edit_profile.php" class="button button-dark me-2">Edit</a></th><br><br>
                    </tr> 
                </tbody>
        <?php 
    } // Tutup while loop
} else {
    // Tampilkan pesan jika data tidak ditemukan
    echo "<div class='alert alert-danger'> Data belum diinputkan.</div>";
}  
?>

        <h1><a href="home.php">Kembali ke Home</a></h1>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="./js/loginDaftar.js"></script>
</body>

</html>