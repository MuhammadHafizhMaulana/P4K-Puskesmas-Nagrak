<?php 
session_start();
if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
}

include 'proses/koneksi.php';

$id = $_SESSION['id'];

// Menyiapkan query dengan prepared statement
$query = "SELECT * FROM `kesehatan_user` WHERE `id_user` = ?";
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
            <form class="form-login poppins mt-5" action="proses/edit_kesehatan_proses.php" method="post">
            <tbody>
                <tr>
                    <th><h1>Edit Kesehatan</h1></th>
                </tr>
                <tr>
                    <th>Golongan Darah</th>
                    <td>:</td>
                    <td><label for="nama" class="form-label">Masukkan Golongan Darah Anda</label><br>
                                <input type="radio" id="golongan_a" name="goldar" value="a"<?php echo ($data['goldar'] == 'a') ? 'checked' : ''; ?>>
                                <label for="golongan_a">Golongan A</label><br>

                                <input type="radio" id="golongan_b" name="goldar" value="b" <?php echo ($data['goldar'] == 'b') ? 'checked' : ''; ?> >
                                <label for="golongan_b">Golongan B</label><br>

                                <input type="radio" id="golongan_ab" name="goldar" value="ab" <?php echo ($data['goldar'] == 'ab') ? 'checked' : ''; ?> >
                                <label for="golongan_ab">Golongan AB</label><br>

                                <input type="radio" id="golongan_o" name="goldar" value="o" <?php echo ($data['goldar'] == 'o') ? 'checked' : ''; ?> >
                                <label for="golongan_o">Golongan O</label></td>
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