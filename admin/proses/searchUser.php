<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

include '../../proses/koneksi.php';

$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : '';
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : '';

$query = "SELECT * FROM user";
if ($searchType && $searchValue) {
    if ($searchType == 'nama') {
        $query .= " WHERE nama LIKE '%$searchValue%'";
    } elseif ($searchType == 'nomorHP') {
        $query .= " WHERE nomorHP LIKE '%$searchValue%'";
    }
}

$result = mysqli_query($connect, $query);
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
