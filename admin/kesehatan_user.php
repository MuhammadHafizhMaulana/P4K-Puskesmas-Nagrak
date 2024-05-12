<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header('Location: login_admin.php');
    exit(); // tambahkan exit setelah redirect
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
    $query = "SELECT * FROM kesehatan_user WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $decrypted_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        $data = mysqli_fetch_assoc($result);

        // Tampilkan data pengguna dalam formulir untuk diedit
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
                        <th> <a href="edit_user.php?id=<?= $encrypted_id ?>" class="button button-dark me-2">Edit</a></th>
                        <th> <a href="home.php" class="button button-dark me-2">Home</a></th>
                    </tr>
                </tbody>
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
