var namaField = document.getElementById('nama');
var buttonSubmitJadwal = document.getElementById('submitJadwal');
var nomorHpField = document.getElementById('nomorHP');
var submitNamaNomorPendonor = document.getElementById('submitNamaNomorPendonor');
var firstSubmitPendonor = document.getElementsByClassName("first-submit-pendonor")[0];
var prevNomorHP = "";
const nomorHpPattern = /^[0-9]+$/;


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

function checkButtonSubmitNamaNomorForm() {
    if (nomorHpField.value == "" || namaField.value == "") {
        submitNamaNomorPendonor.disabled = true;
    } else {
        submitNamaNomorPendonor.disabled = false;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    checkButtonSubmitNamaNomorForm();
});

nomorHpField.addEventListener('input', function () {
    if (this.value === "") {
        prevNomorHP = "";
    } else if (nomorHpPattern.test(this.value)) {
        prevNomorHP = this.value;
    } else {
        this.value = prevNomorHP;
    }
    checkButtonSubmitNamaNomorForm();
})