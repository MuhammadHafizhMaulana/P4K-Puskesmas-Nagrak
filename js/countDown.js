// Convert date string to timestamp
function getTimestamp(dateString) {
    return new Date(dateString).getTime();
}

function convertDateToArray(dateString) {
    var dateParts = dateString.split('-'); // Split the string into an array
    return [
        parseInt(dateParts[2], 10), // Day
        parseInt(dateParts[1], 10), // Month
        parseInt(dateParts[0], 10) // Year
    ];
}

function getTodayAsArray() {
    var today = new Date();
    return [
        today.getDate(), // Day
        today.getMonth() + 1, // Month (getMonth() returns month index starting from 0)
        today.getFullYear() // Year
    ];
}

// Countdown function
function startCountdown(endTime, display) {
    var tanggalHariIni = getTodayAsArray();

    var dueDateArray = convertDateToArray(endTime);
    var dayDue = dueDateArray[0];
    var monthDue = dueDateArray[1];
    var yearDue = dueDateArray[2];

    var dayCurrent = tanggalHariIni[0];
    var monthCurrent = tanggalHariIni[1];
    var yearCurrent = tanggalHariIni[2];

    var moreDaysCurrent = 0;

    if (dayCurrent > dayDue) {
        moreDaysCurrent = 1;
    }

    // Calculate months
    var totalMonths;
    totalMonths = monthDue + 12 - monthCurrent - moreDaysCurrent;
    if (totalMonths >= 12) {
        totalMonths = totalMonths - 12;
    }

    // Calculate remaining days
    var lastMonth = monthCurrent + totalMonths;
    if (lastMonth > 12) {
        lastMonth = lastMonth - 12;
    }

    var weeks;
    var days
    if (dayDue == dayCurrent) {
        weeks = 0;
        days = 0;
    } else {
        var remainingDays;
        if (moreDaysCurrent == 1) {
            if (lastMonth == 2) {
                var daysInFeb = 28;
                if (yearDue % 4 == 0) {
                    daysInFeb = 29;
                }
                remainingDays = daysInFeb + dayDue - dayCurrent;
            } else if (lastMonth == 4 || lastMonth == 6 || lastMonth == 9 || lastMonth == 11) {
                remainingDays = 30 + dayDue - dayCurrent;
            } else {
                remainingDays = 31 + dayDue - dayCurrent;
            }
        } else {
            remainingDays = dayDue - dayCurrent;
        }

        weeks = Math.floor(remainingDays / 7);
        days = remainingDays % 7;

    }

    // cek apakah taksiran telah terlewati
    let persalinanTerlewati = false;
    if (yearCurrent >= yearDue) {
        if (monthCurrent >= monthDue) {
            if (dayCurrent >= dayDue) {
                persalinanTerlewati = true
            }
        }
    }


    display.textContent = persalinanTerlewati ? 'Taksiran persalinan terlah terlewati' : totalMonths + " Bulan " + weeks + " Minggu " + days + " Hari menuju persalinan";

}

// Start countdown when modal is shown
document.addEventListener('DOMContentLoaded', function () {

    var countdownDisplay = document.getElementById('countdown');
    if (countdownDisplay && typeof taksiranPersalinan !== 'undefined') {
        startCountdown(taksiranPersalinan, countdownDisplay);
    }
});