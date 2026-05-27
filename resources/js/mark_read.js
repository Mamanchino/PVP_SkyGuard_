const alertButton = document.querySelector('.alert-notif');
const alertIndicator = document.querySelector('.num-alerts');

if (alertButton) {
    alertButton.addEventListener('click', () => {
        const ids = alertButton.dataset.alertIds;

        if (!ids) return;

        ids.split(',').forEach(id => {
            fetch(`/alerts/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
        });

        alertButton.dataset.alertIds = '';
        if (alertIndicator) {
            alertIndicator.style.opacity = '0';
        }
    });
}
if(document.querySelectorAll('.alert-item').length > 0) {
    alertIndicator.style.backgroundColor = 'red';
    alertIndicator.style.opacity = '1';
    
}