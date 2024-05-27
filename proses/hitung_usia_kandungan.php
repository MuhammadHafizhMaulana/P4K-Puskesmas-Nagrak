<?php
$id = $_SESSION['id'];
$query = "SELECT * FROM `kesehatan_user` WHERE `id_user` = '$id'";
$sql = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($sql);
$goldar = $data['goldar'];

if ($data["hpht"] == null) {
    $usia_kandungan = "Data belum diinputkan";
} else {
    // HPHT & Taksiran Persalinan
    $hpht = $data['hpht'];
    $taksiran = $data['taksiran_persalinan'];
    $taksiran_date = new DateTime($taksiran);
    $now = new DateTime();
    $hpht_date = new DateTime($hpht);

   // Hitung selisih hari antara HPHT dan sekarang
   $interval = $now->diff($hpht_date);
   $selisih_hari = $interval->days;

   // Hitung usia kandungan dalam bulan, minggu, dan hari
   $usia_bulan = $interval->m;
   $usia_tahun = $interval->y;
   $usia_bulan += $usia_tahun * 12; // Tambahkan bulan dari tahun

   $sisa_hari = $interval->d;
   $usia_minggu = floor($sisa_hari / 7);
   $usia_hari = $sisa_hari % 7;

    if ($now >= $taksiran_date) {
        $usia_kandungan = "LAHIR";
    } else {
        $usia_kandungan = "$usia_bulan bulan $usia_minggu minggu $usia_hari hari";
    }
}

?>