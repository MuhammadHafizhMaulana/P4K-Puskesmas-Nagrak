<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

include '../../proses/koneksi.php';

$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : '';
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : '';

$query = "SELECT kesehatan_user.goldar, kesehatan_user.id, kesehatan_user.id_user, user.nama, kesehatan_user.status, kesehatan_user.hpht, kesehatan_user.taksiran_persalinan FROM kesehatan_user JOIN user ON kesehatan_user.id_user = user.id";
if ($searchType && $searchValue) {
    if ($searchType == 'nama') {
        $query .= " WHERE user.nama LIKE '%$searchValue%'";
    } else if ($searchType == 'goldar' && $searchValue != null) {
        $query .= " WHERE kesehatan_user.goldar LIKE '$searchValue'";
    }
}

$query .= " ORDER BY user.nama ASC";

$result = mysqli_query($connect, $query);
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
