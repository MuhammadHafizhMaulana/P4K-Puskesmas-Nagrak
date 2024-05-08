var prevNomorHP = "";
var passwordField = document.getElementById('password');
var nomorHpField = document.getElementById('nomer');
var passwordAlert = document.getElementById('passwordAlert');
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/;
const nomorHpPattern = /^[0-9]+$/;
var passwordValid = false;

        passwordField.addEventListener('input', function() {
            var passwordValue = this.value;

            if (passwordPattern.test(passwordValue)) {
                passwordValid = true;
                passwordAlert.innerText = ""
            } else {
                passwordValid = false;
                passwordAlert.innerText = "Password harus berisi minimal 8 karakter dan mengandung huruf kapital, huruf kecil, dan angka"
            }
        });

        nomorHpField.addEventListener('input', function() {
            var nomorHpValue = this.value;

            if (this.value === "") {
                prevNomorHP = "";
            } else if (nomorHpPattern.test(nomorHpValue)) {
                prevNomorHP = nomorHpValue;
            } else {
                this.value = prevNomorHP;
            }
        })

        

        function submitButtonValid() {
            var fieldStatus = false;
            const value = document.getElementsByClassName("registrasi-form")
            for (let index = 0; index < value.length; index++) {
                if (value[index].value != "") {
                    fieldStatus = true;
                } else {
                    fieldStatus = false;
                    break;
                }
            }

            if (fieldStatus && passwordValid) {
                document.getElementById('submitButton').disabled = false;
            } else {
                document.getElementById('submitButton').disabled = true;
            }
        }