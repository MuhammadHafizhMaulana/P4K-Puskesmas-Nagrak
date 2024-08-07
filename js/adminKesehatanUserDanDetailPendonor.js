var btnEditGoldar = document.getElementById('btn-edit-goldar');
var selectGoldar = document.getElementById('goldarGet');
var goldarSend = document.getElementById('goldar');

function editGoldar() {
    selectGoldar.disabled = false;

    btnEditGoldar.innerText = "Simpan";
    btnEditGoldar.setAttribute('onclick', 'showModal()')
}

function showModal() {
    goldarSend.value = selectGoldar.value;

    var myModal = new bootstrap.Modal(document.getElementById('confirmUpdateModal'), {
      keyboard: false
    });
    myModal.show();
}

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

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('buttonAlert').click();
 });