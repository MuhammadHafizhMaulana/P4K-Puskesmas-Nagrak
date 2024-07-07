<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login_admin') {
    header('Location: login_admin.php');
    exit();
}

// Sambungan ke database
include '../proses/koneksi.php';

// Periksa apakah parameter id ada di URL dan apakah valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = intval($_GET['id']);

    // Function untuk merubah format tanggal
    function formatTanggal($tanggal_input) {
        $timestamp = strtotime($tanggal_input);
        return date("d M Y", $timestamp);
    }

    // Query untuk mengambil data pengguna berdasarkan id yang sudah didekripsi
    $query = "SELECT * FROM kesehatan_user WHERE id_user = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $kesehatan_userresult = mysqli_stmt_get_result($stmt);

    if (!$kesehatan_userresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM pembiayaan WHERE id_user = ?";
    $stmt_pembiayaan = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_pembiayaan, "i", $id_user);
    mysqli_stmt_execute($stmt_pembiayaan);
    $pembiayaanresult = mysqli_stmt_get_result($stmt_pembiayaan);

    if (!$pembiayaanresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM sarpras WHERE id_user = ?";
    $stmt_sarpras = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_sarpras, "i", $id_user);
    mysqli_stmt_execute($stmt_sarpras);
    $sarprasresult = mysqli_stmt_get_result($stmt_sarpras);

    if (!$sarprasresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM kb WHERE id_user = ? ORDER BY tanggal_input DESC";
    $stmt_kb = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_kb, "i", $id_user);
    mysqli_stmt_execute($stmt_kb);
    $kbresult = mysqli_stmt_get_result($stmt_kb);

    if (!$kbresult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT `nama` FROM user WHERE id = ?";
    $stmt_user = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_user, "i", $id_user);
    mysqli_stmt_execute($stmt_user);
    $nameResult = mysqli_stmt_get_result($stmt_user);

    if (!$nameResult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $query = "SELECT * FROM pendonor WHERE id_user = ?";
    $stmt_user = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt_user, "i", $id_user);
    mysqli_stmt_execute($stmt_user);
    $pendonorResult = mysqli_stmt_get_result($stmt_user);

    if (!$pendonorResult) {
        die('Query gagal: ' . mysqli_error($connect));
    }

    $kesehatan = mysqli_fetch_assoc($kesehatan_userresult);
    $pembiayaan = mysqli_fetch_assoc($pembiayaanresult);
    $sarpras = mysqli_fetch_assoc($sarprasresult);
    $kb = mysqli_fetch_assoc($kbresult);
    $ambil_nama = mysqli_fetch_assoc($nameResult);
    

    // Memisahkan jenis penolong dan nama penolong
    $penolong_data = isset($sarpras['penolong']) ? explode(' + ', $sarpras['penolong']) : ['-', '-'];
    $jenis_penolong = trim($penolong_data[0]);
    $nama_penolong = trim($penolong_data[1]);

    $usg_data = isset($sarpras['usg']) ? explode(' + ', $sarpras['usg']) : ['-', '-', '-', '-'];
    $status_usg = isset($usg_data[0]) ? $usg_data[0] : '-';
    $tanggal_usg = isset($usg_data[1]) ? $usg_data[1] : '-';
    $umur_usg = isset($usg_data[2]) ? $usg_data[2] : '-';
    $hasil_usg = isset($usg_data[3]) ? $usg_data[3] : '-';

    if ($sarpras) {
        if (!empty($ksarpras['tanggal_usg'])) {
            $kesehatan['tanggal_usg'] = formatTanggal($kesehatan['tanggal_usg']);
        } else {
            $kesehatan['tanggal_usg'] = '-';
        }
    }
    
    if ($kesehatan) {
        if (!empty($kesehatan['tanggal_input'])) {
            $kesehatan['tanggal_input'] = formatTanggal($kesehatan['tanggal_input']);
        } else {
            $kesehatan['tanggal_input'] = '-';
        }
    }

    if ($kesehatan) {
        if (!empty($kesehatan['taksiran_persalinan'])) {
            $kesehatan['taksiran_persalinan'] = formatTanggal($kesehatan['taksiran_persalinan']);
        } else {
            $kesehatan['taksiran_persalinan'] = '-';
        }
    }

    if ($kesehatan['status'] != 'diketahui') {
        $kesehatan['status'] = '-';
    }

    if (is_null($kesehatan['goldar']) || $kesehatan['goldar'] === '') {
        $kesehatan['goldar'] = '-';
    }

    // Menghitung usia kandungan
    if (isset($kesehatan["hpht"])) {
        $hpht = $kesehatan['hpht'];
        $taksiran = $kesehatan['taksiran_persalinan'];
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
            $usia_kandungan = "Telah Lahir";
        } else {
            $usia_kandungan = "$usia_bulan Bulan $usia_minggu Minggu $usia_hari Hari";
        }
    }

    // Jika data pengguna ditemukan, tampilkan halaman
    if ($kesehatan || $pembiayaan || $sarpras || $kb) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/adminKesehatanUser&DetailPendonor.css">
    <style>
        h3 {
            text-align: left;
            /* Atur teks menjadi rata kiri */
        }

        .show-print {
            display: none;
        }

        .br-print {
            display: none;
        }

        .show-on-print-nama-bawah {
            display: none;
        }


        @media print {
            .hide-on-print {
                display: none !important;
            }

            .show-print {
                display: block;
            }

            .hide-page-title {
                display: none;
            }

            .pasFoto {
                display: flex;
                justify-content: center;
                /* Untuk menempatkan elemen di tengah secara horizontal */
                align-items: center;
                /* Untuk menempatkan elemen di tengah secara vertikal */
            }

            .show-on-print-foto {
                width: 250px;
                /* Sesuaikan lebar foto sesuai kebutuhan */
                height: auto;
                /* Biarkan tinggi mengikuti proporsi aslinya */
                margin-left: 0;
                margin-right: 0;
            }

            .show-on-print-nama-atas {
                display: none;
            }

            .show-on-print-nama-bawah {
                display: flex;
            }

            .br-print {
                display: block;
            }

            @page {
                margin: 0;
                /* Mengatur margin halaman cetak menjadi 0 */
            }

            body {
                margin: 0;
                /* Juga mengatur margin body menjadi 0 untuk menghindari margin tambahan */
            }

        }
    </style>
</head>

<body>
    <nav class="my-navbar navbar navbar-expand-lg hide-on-print">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="../assets/logo-kemenkes.png" alt="Logo Kemenkes">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                        fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                 </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link" href="landing.php">Dashboard</a>
                    <a class="nav-link active" href="data_user.php">User</a>
                    <a class="nav-link" href="listKesehatanUser.php">Kesehatan User</a>
                    <a class="nav-link" href="pendonor.php">Pendonor</a>
                    <a class="nav-link" href="profile.php">Profile</a>
                    <a class="nav-link" href="proses/logout.php">
                        <button type="button" class="btn btn-outline-danger">Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div id="boxKesehatanUser">
        <h1 style="font-weight: bold;" class="hide-on-print">Detail Data User</h1>
        <div class="d-flex justify-content-center hide-on-print">
            <button type="button" onclick="window.print()" class="btn btn-secondary">Print Data User</button>
        </div>
        <br class="hide-on-print"><br class="hide-on-print">
        <br class="show-print"><br class="show-print">
        <div class="w-100">
            <div class="d-flex justify-content-between align-items-end hide-on-print">
                <h3 class=" text-start m-0 hide-on-print" style="font-weight: bold;">Detail Data Pembayaran</h3>
                <a class="hide-on-print" href="pembiayaan_user.php?id=<?php echo $id_user; ?>">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
            <br class="hide-on-print">
            <div class="row show-on-print-nama-atas">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($ambil_nama['nama']) ? ucwords($ambil_nama['nama']) : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Jenis Pembiayaan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['jenis_pembayaran']) ? ucwords($pembiayaan['jenis_pembayaran']) : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Saldo Tabungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['saldo_tabungan']) ? 'Rp. '. $pembiayaan['saldo_tabungan'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Foto KTP</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php if (isset($pembiayaan['ktp'])) { 
                    if ($pembiayaan['ktp'] === '-') {
                        echo '-';
                    } elseif ($pembiayaan['ktp']) { ?>
                    <img src="./proses/getUserKTP.php?id=<?= $pembiayaan["id_user"] ?>" alt="KTP User"
                        class="boxPhoto rounded-3 border border-2 border-primary w-100 mb-3 mt-2">
                    <?php } else { ?>
                    -
                    <?php } 
                } else { ?>
                    -
                    <?php } ?>
                </div>

            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Foto Kartu Keluarga</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if (isset($pembiayaan['kk'])) { 
                    if ($pembiayaan['kk'] === '-') {
                        echo '-';
                    } elseif ($pembiayaan['kk']) { ?>
                    <img src="./proses/getUserkk.php?id=<?= $pembiayaan["id_user"] ?>" alt="KK User"
                        class="boxPhoto rounded-3 border border-2 border-primary w-100 mb-3">
                    <?php } else { ?>
                    -
                    <?php } 
                } else { ?>
                    -
                    <?php } ?>
                </div>
            </div>
            <div class="row pasFoto">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder hide-on-print">Pas Foto</div>
                <div class="col-1 d-none d-sm-block hide-on-print">:</div>
                <h1 style="font-weight: bold;" class="show-print m-0">Print Data User</h1>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start show-on-print-foto">
                    <br class="br-print">
                    <?php if (isset($pembiayaan['pas_foto'])) { 
                    if ($pembiayaan['pas_foto'] === '-') {
                        echo '-';
                    } elseif ($pembiayaan['pas_foto']) { ?>
                    <img src="./proses/getUserPasFoto.php?id=<?= $pembiayaan["id_user"] ?>" alt="Pas Foto User"
                        class="boxPhoto rounded-3 border border-2 border-primary w-100 mb-3">
                    <?php } else { ?>
                    -
                    <?php } 
                } else { ?>
                    -
                    <?php } ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Rekomendasi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if (isset($pembiayaan['rekomendasi'])) { 
                        if ($pembiayaan['rekomendasi'] === '-') {
                            echo '-';
                        } elseif ($pembiayaan['rekomendasi']) { ?>
                    <img src="./proses/getUserRekomendasi.php?id=<?= $pembiayaan["id_user"] ?>" alt="Rekomendasi User"
                        class="boxPhoto rounded-3 border border-2 border-primary w-100 mb-3">
                    <?php } else { ?>
                    -
                    <?php } 
                    } else { ?>
                    -
                    <?php } ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Rujukan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php if (isset($pembiayaan['rujukan'])) { 
                    if ($pembiayaan['rujukan'] === '-') {
                        echo '-';
                    } elseif ($pembiayaan['rujukan']) { ?>
                    <img src="./proses/getUserRujukan.php?id=<?= $pembiayaan["id_user"] ?>" alt="Rujukan User"
                        class="boxPhoto rounded-3 border border-2 border-primary w-100 mb-2">
                    <?php } else { ?>
                    -
                    <?php } 
                } else { ?>
                    -
                    <?php } ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Deskripsi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($pembiayaan['deskripsi']) && $pembiayaan['deskripsi'] ? $pembiayaan['deskripsi'] : '-'; ?>

                </div>
            </div>
            <br class="hide-on-print"><br class="hide-on-print">
            <div class="d-flex justify-content-between align-items-end hide-on-print">
                <h3 class=" text-start m-0 hide-on-print" style="font-weight: bold;">Detail Data Goldar User</h3>
                <a class="hide-on-print" href="kesehatan_user.php?id=<?php echo $id_user; ?>">
                    <button type="button" class="btn btn-primary">Edit</button>
                </a>
            </div>
            <br class="hide-on-print">
            <div class="row show-on-print-nama-bawah">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($ambil_nama['nama']) ? $ambil_nama['nama'] : '-' ?>
                </div>
            </div>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['goldar']) ? strtoupper($kesehatan['goldar']) : '-' ?>
                </div>
            </div>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Taksiran Persalinan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['taksiran_persalinan']) ? $kesehatan['taksiran_persalinan'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Usia Kandungan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($usia_kandungan) ? ucwords($usia_kandungan .' pada saat ini ') : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Status Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['status']) ? ucwords($kesehatan['status']) : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Terakhir User Update</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kesehatan['tanggal_input']) ? ucwords($kesehatan['tanggal_input']) : '-' ?>
                </div>
            </div>
            <br class="hide-on-print"><br class="hide-on-print">
            <?php
            // Ambil semua data pendonor dari hasil query
            $data_pendonor = [];
            while ($row_pendonor = mysqli_fetch_assoc($pendonorResult)) {
                $data_pendonor[] = $row_pendonor;
            }

            // Jika terdapat data pendonr, lakukan pemformatan tanggal
            foreach ($data_pendonor as &$pendonor) {
                if (isset($pendonor['tanggal_input'])) {
                    $pendonor['tanggal_input'] = formatTanggal($pendonor['tanggal_input']);
                }
            }
            unset($pendonor);

            // Batasi data pendonor hanya 2
            $data_pendonor = array_slice($data_pendonor, 0, 2);

            ?>
            <div class="d-flex justify-content-between align-items-end hide-on-print">
                <h3 class="text-start m-0 hide-on-print" style="font-weight: bold;">Detail Data Pendonor</h3>
            </div>
            <br>
            <?php if (!empty($data_pendonor)) {
            $counter = 1; 
            foreach ($data_pendonor as $pendonor_item): ?>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nama Pendonor <?php echo $counter++; ?></div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo isset($pendonor_item['nama']) ? $pendonor_item['nama'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nomor HP</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo isset($pendonor_item['nomorHP']) ? $pendonor_item['nomorHP'] : '-' ?>
                </div>
            </div>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo isset($pendonor_item['goldar']) ? strtoupper($pendonor_item['goldar']) : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Tanggal Input</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo isset($pendonor_item['tanggal_input']) ? $pendonor_item['tanggal_input'] : '-' ?>
                </div>
            </div>
            <br>
            <?php endforeach; 
            } else { ?>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nama Pendonor</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Nomor HP</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Goldar</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder">Tanggal Input</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0 text-start">
                    <?php echo '-' ?>
                </div>
            </div>
            <br>
            <?php } ?>
            <br class="hide-on-print"><br class="hide-on-print">
            <div class="d-flex justify-content-between align-items-end hide-on-print">
                <h3 class=" text-start m-0 hide-on-print" style="font-weight: bold;">Detail Data Sarpras</h3>
            </div>
            <br class="hide-on-print">
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Transportasi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['transportasi']) ? ucwords($sarpras['transportasi']) : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama Supir</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['nama_supir']) ? $sarpras['nama_supir'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">No Supir</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['no_supir']) ? $sarpras['no_supir'] : '-' ?>
                </div>
            </div>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama Pendamping</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['nama_pendamping']) ? $sarpras['nama_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">No Pendamping</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['no_pendamping']) ? $sarpras['no_pendamping'] : '-' ?>
                </div>
            </div>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Rumah Sakit Tujuan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($sarpras['tujuan']) ? $sarpras['tujuan'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Jenis Penolong</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($jenis_penolong) ? strtoupper($jenis_penolong) : '-' ?>
                </div>
            </div>
            <div class="row show-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Nama Penolong</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($nama_penolong) ? $nama_penolong : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">USG</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($status_usg) ? ucwords($status_usg) : '-' ?>
                </div>
            </div>
            <?php if ($status_usg == 'pernah') { ?>
                <div class="row hide-on-print">
                    <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Tanggal USG</div>
                    <div class="col-1 d-none d-sm-block">:</div>
                    <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                        <?php echo isset($tanggal_usg) ? $tanggal_usg : '-' ?>
                    </div>
                </div>
                <div class="row hide-on-print">
                    <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Umur Kandungan USG</div>
                    <div class="col-1 d-none d-sm-block">:</div>
                    <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                        <?php echo isset($umur_usg) ? $umur_usg : '-' ?>
                    </div>
                </div>
                <div class="row hide-on-print">
                    <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Hasil USG</div>
                    <div class="col-1 d-none d-sm-block">:</div>
                    <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                        <?php echo isset($hasil_usg) ? ucwords($hasil_usg) : '-' ?>
                    </div>
                </div>
            <?php } ?>

            <br class="hide-on-print"><br>

            <div class="d-flex justify-content-between align-items-end hide-on-print">
                <h3 class=" text-start m-0 hide-on-print" style="font-weight: bold;">Detail Data KB User</h3>
                <a href="kb_user.php?id=<?php echo $id_user; ?>" class="btn btn-primary hide-on-print">Edit</a>
            </div>
            <br class="hide-on-print">

            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Tujuan</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['tujuan']) ? ucwords($kb['tujuan']) . ' Kehamilan' : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Metode</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['jenis']) ? ucwords($kb['jenis']) : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Tanggal Input</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['tanggal_input']) ? $kb['tanggal_input'] : '-' ?>
                </div>
            </div>
            <div class="row hide-on-print">
                <div class="col-12 col-sm-5 text-start fw-bolder fw-bolder">Deskripsi</div>
                <div class="col-1 d-none d-sm-block">:</div>
                <div class="col-12 col-sm-6 ms-2 mb-2 m-sm-0  text-start">
                    <?php echo isset($kb['deskripsi']) && $kb['deskripsi'] ? $kb['deskripsi'] : '-'; ?>
                </div>
            </div>
        </div>

        <script src="../js/adminKesehatanUserDanDetailPendonor.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</body>

</html>
<?php
    } else {
        echo "Data pengguna tidak ditemukan.";
    }
    // Tutup statement
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_pembiayaan);
    mysqli_stmt_close($stmt_sarpras);
    mysqli_stmt_close($stmt_kb);
    mysqli_stmt_close($stmt_user);

// Tutup koneksi
mysqli_close($connect);
} else {
    echo "ID tidak valid atau tidak disediakan.";
}
?>