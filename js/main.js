/**
 * Luxe Cake - Client-side interactions
 */

document.addEventListener('DOMContentLoaded', function () {
    // Weight selector dynamic price updater
    const weightSelect = document.getElementById('cakeWeight');
    const displayPrice = document.getElementById('displayPrice');
    const basePriceInput = document.getElementById('basePrice');

    if (weightSelect && displayPrice && basePriceInput) {
        const basePrice = parseFloat(basePriceInput.value);
        weightSelect.addEventListener('change', function () {
            let multiplier = 1.0;
            const val = this.value;
            if (val === '0.5kg') multiplier = 0.6;
            else if (val === '1kg') multiplier = 1.0;
            else if (val === '2kg') multiplier = 1.9;
            else if (val === '3kg') multiplier = 2.8;
            else if (val === '5kg') multiplier = 4.5;

            const updated = (basePrice * multiplier).toFixed(2);
            displayPrice.textContent = '$' + updated;
        });
    }

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
});
