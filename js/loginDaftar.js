var prevNomorHP = "";
var passwordField = document.getElementById('password');
var nomorHpField = document.getElementById('nomer');
var passwordAlert = document.getElementById('passwordAlert');

const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/;
const nomorHpPattern = /^[0-9]+$/;
var fieldStatus = false;
var passwordStatus = false

passwordField.addEventListener('input', function () {
    if (passwordPattern.test(passwordField.value)) {
        passwordStatus = true
        passwordAlert.innerText = ""
    } else {
        passwordStatus = false
        passwordAlert.innerText = "Password harus berisi minimal 8 karakter dan mengandung huruf kapital, huruf kecil, dan angka"
    }

    checkSubmitValid();
});

nomorHpField.addEventListener('input', function () {
    if (this.value === "") {
        prevNomorHP = "";
    } else if (nomorHpPattern.test(this.value)) {
        prevNomorHP = this.value;
    } else {
        this.value = prevNomorHP;
    }
})

function checkSubmitValid() {
    if (fieldStatus && passwordStatus) {
        document.getElementById('submitButton').disabled = false;
    } else {
        document.getElementById('submitButton').disabled = true;
    }
}

function formValid() {
    fieldStatus = true;
    const value = document.getElementsByClassName("registrasi-form");
    for (let index = 0; index < value.length; index++) {
        if (value[index].value === "") { // Jika ada yang kosong
            fieldStatus = false; // Mengubah nilai menjadi false
            break; // Hentikan iterasi
        }
    }

    checkSubmitValid();
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