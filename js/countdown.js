// Convert date string to timestamp
function getTimestamp(dateString) {
    return new Date(dateString).getTime();
}

// Countdown function
function startCountdown(endTime, display) {
    var endTime = getTimestamp(endTime);
    var interval = setInterval(function () {
        var now = new Date().getTime();
        var distance = endTime - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        display.textContent = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        if (distance < 0) {
            clearInterval(interval);
            display.textContent = "EXPIRED";
        }
    }, 1000);
}

// Start countdown when modal is shown
document.addEventListener('DOMContentLoaded', function() {
    var buttonAlert = document.getElementById('buttonAlert');
    if (buttonAlert) {
        buttonAlert.click();
    }

    var modal = document.getElementById('exampleModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            var countdownDisplay = document.getElementById('countdown');
            if (countdownDisplay && typeof taksiranPersalinan !== 'undefined') {
                startCountdown(taksiranPersalinan, countdownDisplay);
            }
        });
    }
});