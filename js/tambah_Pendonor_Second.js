var goldarField = document.getElementById('goldar');
var namaField = document.getElementById('nama');
var waktuPengecekanField = document.getElementById('waktu_pengecekan_goldar');
var buttonSubmitJadwal = document.getElementById('submitJadwal');
var nomorHpField = document.getElementById('nomorHP');
var submitPendonor = document.getElementById('submitPendonor');


function openSpinner() {
    // Ambil elemen body dari dokumen
    var body = document.getElementsByTagName("body")[0];

    // Buat elemen div untuk spinner
    var spinnerDiv = document.createElement("div");
    spinnerDiv.className = "spinner-opened";

    // Buat elemen div untuk spinner-border
    var spinnerBorderDiv = document.createElement("div");
    spinnerBorderDiv.className = "spinner-border text-light";
    spinnerBorderDiv.setAttribute("role", "status");

    // Buat elemen span untuk teks "Loading..."
    var spinnerText = document.createElement("span");
    spinnerText.className = "visually-hidden";
    spinnerText.innerText = "Loading...";

    // Masukkan elemen span ke dalam elemen spinner-border
    spinnerBorderDiv.appendChild(spinnerText);

    // Masukkan elemen spinner-border ke dalam elemen spinner
    spinnerDiv.appendChild(spinnerBorderDiv);

    // Masukkan elemen spinner ke dalam elemen body
    body.appendChild(spinnerDiv);
}

function checkGoldar() {
    var goldarField = document.getElementById('goldar');

    if (goldarField.value == "-") {
        submitPendonor.removeAttribute("onclick");
        //submitPendonor.setAttribute("class", "first-submit-pendonor btn btn-danger")
        submitPendonor.setAttribute("type", "button");
        submitPendonor.setAttribute("data-bs-toggle", "modal");
        submitPendonor.setAttribute("data-bs-target", "#staticBackdrop");
    } else {
        submitPendonor.setAttribute("onclick", "openSpinner()");
        //submitPendonor.setAttribute("class", "btn btn-danger")
        submitPendonor.setAttribute("type", "submit");
        submitPendonor.removeAttribute("data-bs-toggle");
        submitPendonor.removeAttribute("data-bs-target");
    }
}


document.addEventListener("DOMContentLoaded", function () {
    checkGoldar();
});

goldarField.addEventListener('input', function () {
    checkGoldar();
})


waktuPengecekanField.addEventListener('input', function () {
    if (waktuPengecekanField.value) {
        buttonSubmitJadwal.disabled = false;
    } else {
        buttonSubmitJadwal.disabled = true;
    }
})


buttonSubmitJadwal.addEventListener('click', function () {
    namaUser = document.getElementById("namaUser");
    nomorUser = document.getElementById('nomorHPUser');
    namaPendonor = namaField.value.toUpperCase();

    urlToWhatsapp = `https://wa.me/6285540570790?text=Halo, perkenalkan saya *${namaUser.value}* (noHP : *${nomorUser.value}*) ingin mendaftarkan saudara/i *${namaPendonor}* (noHP : *${nomorHpField.value}*) untuk melakukan pengecekan golongan darah di Puskesmas Nagrak. Berkaitan dengan hal tersebut, apakah saudara/i *${namaPendonor}* dapat melakukan pengecekan pada *${waktuPengecekanField.value}*?`

    window.open(urlToWhatsapp, "_blank");
})