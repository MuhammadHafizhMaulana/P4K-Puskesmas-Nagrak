// Ambil elemen-elemen berdasarkan ID mereka
var tujuanField = document.getElementById('tujuan');
var waktuPengecekanField = document.getElementById('waktu_konsultasi');
var buttonSubmitForm = document.getElementById('submitForm');
var buttonSubmitDialog = document.getElementById('submitJadwal');

function openSpinner() {
    var body = document.getElementsByTagName("body")[0];
    var spinnerDiv = document.createElement("div");
    spinnerDiv.className = "spinner-opened";

    var spinnerBorderDiv = document.createElement("div");
    spinnerBorderDiv.className = "spinner-border text-light";
    spinnerBorderDiv.setAttribute("role", "status");

    var spinnerText = document.createElement("span");
    spinnerText.className = "visually-hidden";
    spinnerText.innerText = "Loading...";

    spinnerBorderDiv.appendChild(spinnerText);
    spinnerDiv.appendChild(spinnerBorderDiv);
    body.appendChild(spinnerDiv);
}

// Aktifkan atau nonaktifkan tombol submit berdasarkan input di field waktu
waktuPengecekanField.addEventListener('input', function () {
    buttonSubmitDialog.disabled = !waktuPengecekanField.value;
});

// Event listener untuk dialog submit
buttonSubmitDialog.addEventListener("click", function (event) {
    // Ambil nilai-nilai dari field form
    var nama = document.getElementById('nama').value;
    var nomorHP = document.getElementById('nomorHP').value;
    var alamat = document.getElementById('alamat').value;
    var tujuan = tujuanField.value;
    var metodeKonsul;

    // Ambil field opsional jika tujuan sesuai
    var mowField = document.getElementById('mow');
    var penyakit1Field = document.getElementById('penyakit1');
    var iudField = document.getElementById('iud');
    var penyakit2Field = document.getElementById('penyakit2');
    var mow = mowField ? mowField.value : '';
    var penyakit1 = penyakit1Field ? penyakit1Field.value : '';
    var iud = iudField ? iudField.value : '';
    var penyakit2 = penyakit2Field ? penyakit2Field.value : '';

    // Tentukan metode konsultasi berdasarkan nilai-nilai input
    if (tujuan === 'menyudahi') {
        if (mow === 'iya') {
            metodeKonsul = 'mow';
        } else {
            metodeKonsul = 'selain mow';
        }
    } else if (tujuan === 'menjarakan') {
        if (penyakit1 === 'tidak') {
            if ( iud === 'iya'){
                metodeKonsul = "iud"
            } else {
                metodeKonsul = 'selain iud';
            }
        } else if (penyakit1 === 'iya') {
            if (penyakit2 === 'iya') {
                metodeKonsul = 'konsultasi nakes';
            } else {
                metodeKonsul = 'kondom + kb hormonal';
            }
        }
    }

    // Buat URL WhatsApp
    var urlToWhatsapp = `https://wa.me/6285720848420?text=Halo, perkenalkan saya *${nama}* dengan nomor handphone *${nomorHP}* yang beralamatkan di *${alamat}* ingin melakukan konsultasi KB pada *${waktuPengecekanField.value}*. Tujuan saya menggunakan KB adalah untuk ${tujuan} keturunan, dan saya ingin menggunakan metode ${metodeKonsul}`;

    // Buka URL di tab baru
    window.open(urlToWhatsapp, "_blank");
});
