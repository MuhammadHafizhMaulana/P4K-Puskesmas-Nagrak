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

function openPhotoDialog(photoName, id) {
    const titleDialog = document.getElementById('titlePhotoDialog');
    const contentDialog = document.getElementById('contentPhotoDialog');
    if (photoName == "ktp") {
        titleDialog.innerText = "Detail Foto KTP"
        contentDialog.setAttribute('src', `./proses/getUserKTP.php?id=${id}`)
    } else if (photoName == "kk") {
        titleDialog.innerText = "Detail Foto KK"
        contentDialog.setAttribute('src', `./proses/getUserKK.php?id=${id}`)
    } else if (photoName == "pas_foto") {
        titleDialog.innerText = "Detail Pas Foto"
        contentDialog.setAttribute('src', `./proses/getUserPasFoto.php?id=${id}`)
    } else if (photoName == "rujukan") {
        titleDialog.innerText = "Detail Foto Rujukan"
        contentDialog.setAttribute('src', `./proses/getUserRujukan.php?id=${id}`)
    } else if (photoName == "rekomendasi") {
        titleDialog.innerText = "Detail Foto Rekomendasi"
        contentDialog.setAttribute('src', `./proses/getUserRekomendasi.php?id=${id}`)
    }
}


function updateBoxPhotoHeight() {
    if (document.querySelectorAll('.boxPhoto')) {
        var items = document.querySelectorAll('.boxPhoto');
        items.forEach(function(item) {
            item.style.height = item.offsetWidth * 0.75 + 'px';
        });
    }
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

document.addEventListener('DOMContentLoaded', () => {
    updateBoxPhotoHeight();
} );

// Update height on window resize
window.addEventListener('resize', function() {
    updateBoxPhotoHeight();
});