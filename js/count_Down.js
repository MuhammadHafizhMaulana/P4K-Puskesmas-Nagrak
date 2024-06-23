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

        // Calculate time components
        var months = Math.floor(distance / (1000 * 60 * 60 * 24 * 30));
        var weeks = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24 * 7));
        var days = Math.floor((distance % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24));

        display.textContent = months + " Bulan " + weeks + " Minggu " + days + " Hari ";

        if (distance < 0) {
            clearInterval(interval);
            display.textContent = "EXPIRED";
        }
    }, 1000);
}

// Start countdown when modal is shown
document.addEventListener('DOMContentLoaded', function() {
    
    var countdownDisplay = document.getElementById('countdown');
    if (countdownDisplay && typeof taksiranPersalinan !== 'undefined') {
        startCountdown(taksiranPersalinan, countdownDisplay);
    }

    // var modal = document.getElementById('exampleModal');
    // if (modal) {
    //     modal.addEventListener('shown.bs.modal', function () {
    //     });
    // }
});
