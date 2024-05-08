var prevNomorHP = "";
var passwordField = document.getElementById('password');
var nomorHpField = document.getElementById('nomer');
var passwordAlert = document.getElementById('passwordAlert');
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/;
const nomorHpPattern = /^[0-9]+$/;
var fieldStatus = false;
var passwordStatus = false

passwordField.addEventListener('input', function() {
    if (passwordPattern.test(passwordField.value)) {
        passwordStatus = true
        passwordAlert.innerText = ""
    } else {
        passwordStatus = false
        passwordAlert.innerText = "Password harus berisi minimal 8 karakter dan mengandung huruf kapital, huruf kecil, dan angka"
    }

    checkSubmitValid();
});

nomorHpField.addEventListener('input', function() {
    console.log('halo')
    var nomorHpValue = this.value;

    if (this.value === "") {
        prevNomorHP = "";
    } else if (nomorHpPattern.test(nomorHpValue)) {
        prevNomorHP = nomorHpValue;
    } else {
        this.value = prevNomorHP;
    }
})

function checkSubmitValid() {
    
    console.log('test')
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
        console.log(value[index].value)
        if (value[index].value === "") { // Jika ada yang kosong
            fieldStatus = false; // Mengubah nilai menjadi false
            break; // Hentikan iterasi
        }
    }

   checkSubmitValid();
}
