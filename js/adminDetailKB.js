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

function cekFieldHasilKonsultasi() {
    const fieldDeskripsi = document.getElementById('deskripsi');
    const buttonUpdateKonsultasi = document.getElementById('buttonUpdateKonsultasi');

    buttonUpdateKonsultasi.disabled = true;
    if (fieldDeskripsi.value) {
        buttonUpdateKonsultasi.disabled = false;
    }
}

function editHasilKonsultasi() {
    const fieldDeskripsi = document.getElementById('deskripsi');
    const buttonUpdateKonsultasi = document.getElementById('buttonUpdateKonsultasi');
    const buttonHasilKonsulName = document.getElementById('buttonHasilKonsulName');

    fieldDeskripsi.disabled = false
    buttonUpdateKonsultasi.setAttribute('data-bs-toggle', 'modal');
    buttonUpdateKonsultasi.setAttribute('data-bs-target', '#confirmUpdateModal');
    buttonUpdateKonsultasi.removeAttribute('onclick');

    buttonHasilKonsulName.innerHTML = "Input"

    cekFieldHasilKonsultasi();
}

