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
    <table class="table">
            <form class="form-login poppins mt-5" action="proses/edit_profile_proses.php" method="post">
            <tbody>
                <tr>
                    <th><h1>Halaman Profil</h1></th>
                </tr>
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
                    <th>Nomor HP</th>
                    <td>:</td>
                    <td><input type="text" name="nomorHP" class="input-login" placeholder="nomorHP" value="<?=$data['nomorHP']?>"></td>
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
                    <button type="submit" class="button button-dark w-100">EDIT</button>
                </div></th>
                </tr>
               
            </tbody>
            </form>
    </table>
</body>
</html>