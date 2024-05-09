var prevNomorHP = "";
var nomorHpField = document.getElementById('nomer');
const nomorHpPattern = /^[0-9]+$/;

nomorHpField.addEventListener('input', function () {
    var nomorHpValue = this.value;

    if (this.value === "") {
        prevNomorHP = "";
    } else if (nomorHpPattern.test(nomorHpValue)) {
        prevNomorHP = nomorHpValue;
    } else {
        this.value = prevNomorHP;
    }
})