<?php 

    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
}

    include 'proses/koneksi.php';
    $id = $_SESSION['id'];
    $query = "SELECT * FROM `user` WHERE `id` = '$id' ";
    $sql = mysqli_query($connect, $query);
    $data = mysqli_fetch_assoc($sql);
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
    <div>
    <h1>Halaman Profile</h1>
    <table class="table">
            <tbody>
                <tr>
                    <th><h1>Data Diri</h1></th>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>:</td>
                    <td><?=$data['nama']?></td>
                </tr>
                <tr>
                    <th>Usia</th>
                    <td>:</td>
                    <td><?=$data['usia']?></td>
                </tr>
                <tr>
                    <th>Nomor HP</th>
                    <td>:</td>
                    <td><?=$data['nomorHP']?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>:</td>
                    <td>
                      <p><?=$data['alamat']?></p>
                    </td>
                </tr>
                <tr>
                    <th> <a href="edit_profile.php" class="button button-dark me-2">Edit Profile</a></th>
                    <th> <a href="home.php" class="button button-dark me-2">Home</a></th>
                </tr> 
            </tbody>
    </div>
    <div>
    </table class="table" >
            <?php
              include 'proses/koneksi.php';
              $id = $_SESSION['id'];
              $query = "SELECT * FROM `kesehatan_user` WHERE `id_user` = '$id' ";
              $sql = mysqli_query($connect, $query);
              $data = mysqli_fetch_assoc($sql);
          ?>
            <table class="table">
            <tbody>
                <tr>
                    <th><h1>Data Kesehatan</h1></th>
                </tr>
                <tr>
                    <th>Golongan Darah</th>
                    <td>:</td>
                    <td><?= isset($data['goldar']) ? $data['goldar'] : "Data belum diinput" ?></td>
                </tr>
                <tr>
                    <th>Usia Kandungan (Minggu)</th>
                    <td>:</td>
                    <td><?= isset($data['usia_kandungan']) ? $data['usia_kandungan'] : "Data belum diinput" ?></td>
                </tr>
                <tr>
                    <th> <a href="donor_darah.php" class="button button-dark me-2">Edit</a></th>
                    <th> <a href="home.php" class="button button-dark me-2">Home</a></th>
                </tr>
            </tbody>
            </table>
    </div>
</body>
</html>