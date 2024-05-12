var goldarField = document.getElementById('goldar');
var waktuPengecekanField = document.getElementById('waktu_pengecekan_goldar');
var buttonSubmitJadwal = document.getElementById('submitJadwal');


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
    var submitGoldar = document.getElementById('submitGoldar');

    if (goldarField.value == "-") {
        submitGoldar.removeAttribute("onclick");
        submitGoldar.setAttribute("type", "button");
        submitGoldar.setAttribute("data-bs-toggle", "modal");
        submitGoldar.setAttribute("data-bs-target", "#staticBackdrop");
    } else {
        submitGoldar.setAttribute("onclick", "openSpinner()");
        submitGoldar.setAttribute("type", "submit");
        submitGoldar.removeAttribute("data-bs-toggle");
        submitGoldar.removeAttribute("data-bs-target");
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
    var nama = document.getElementById('nama');
    var nomorHP = document.getElementById('nomorHP');
    var alamat = document.getElementById('alamat');
    urlToWhatsapp = `https://wa.me/6285540570790?text=Halo, perkenalkan saya *${nama.value}* dengan nomor handphone *${nomorHP.value}* yang beralamatkan di *${alamat.value}* ingin melakukan pengecekan golongan darah di Puskesmas Nagrak. Berkaitan dengan hal tersebut, apakah saya dapat melakukan pemeriksaan pada *${waktuPengecekanField.value}*?`;

    window.open(urlToWhatsapp, "_blank")
})