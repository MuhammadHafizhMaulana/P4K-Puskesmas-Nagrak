<?php

session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header('Location: login_admin.php');
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL
if(isset($_GET['id'])) {
    // Tangkap id terenkripsi dari URL
    $encrypted_id = $_GET['id'];

    // Dekripsikan id
    $decrypted_id = base64_decode($encrypted_id);

    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = "SELECT * FROM user WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $decrypted_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        $data = mysqli_fetch_assoc($result);

        // Tampilkan data pengguna dalam formulir untuk diedit
        // Misalnya:
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
<table class="table">
    <form class="form-login poppins mt-5" action="proses/edit_user_proses.php" method="post">
        <tbody>
            <tr>
                <th>
                    <h1>Halaman Profil</h1>
                </th>
            </tr>
            <tr>
                <th>Nama</th>
                <td>:</td>
                <td> <input type="text" name="nama" class="input-login" placeholder="Nama Lengkap"
                        value="<?=$data['nama']?>"></td>
            </tr>
            <tr>
                <th>Usia</th>
                <td>:</td>
                <td><input type="number" min="0" name="usia" class="input-login" placeholder="usia"
                        value="<?=$data['usia']?>"></td>
            </tr>
            <tr>
                <th>Nomor HP</th>
                <td>:</td>
                <td><input type="text" name="nomorHP" class="input-login" placeholder="nomorHP"
                        value="<?=$data['nomorHP']?>"></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>:</td>
                <td>
                    <p><textarea name="alamat" cols="30" rows="10" class="input-login"
                            placeholder="Alamat"><?=$data['alamat']?></textarea></p>
                </td>
            </tr>
            <tr>
                <th>
                    <div class="button-input">
                        <button type="submit" class="button button-dark w-100">EDIT</button>
                    </div>
                </th>
            </tr>

        </tbody>
    </form>
</table>
</body>

</html>

<?php
    } else {
        echo "Data pengguna tidak ditemukan.";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    echo "ID tidak ditemukan dalam URL.";
}

// Tutup koneksi
mysqli_close($connect);
?>