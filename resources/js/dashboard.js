const droneId = document.body.dataset.droneId;
const lastEventId = document.body.dataset.lastEventId || 0;

const events = new EventSource(`/drones/${droneId}/drone-events?last_event_id=${lastEventId}`);

events.addEventListener('person-detected', (event) => {
    const data = JSON.parse(event.data);

    if (data.severity === 'info') {
        setTimeout(5);
        addNotification(data);
    }

    if (['critical', 'error'].includes(data.severity)) {
        showAlertIndicator();
    }
});

function addNotification(event) {
    const pane = document.querySelector('.notification-pane');

    const item = document.createElement('div');
    item.className = 'notification-container';

    item.innerHTML = `
        <div class="notification-container">
            <div class="alert-svg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <path fill="currentColor"
                    d="M11.53 2.3A1.85 1.85 0 0 0 10 1.21A1.85 1.85 0 0 0
                    8.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67
                    0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z" />
                </svg>
            </div>
            <div class="notification-message">
                <strong style= " color:#f0eded">{{$drone->name}}</strong>
                <p class="notification-type">{{ $event->event_type }}</p>
                <strong class="notification-date">{{ date("Y-m-d H:i", strtotime($event->started_at)) }}</strong>
            </div>
        </div>
    `;

    pane.appendChild(item);
}

function showAlertIndicator() {
    const alertIndicator = document.querySelector('.num-alerts');

    if (alertIndicator) {
        alertIndicator.style.backgroundColor = 'red';
        alertIndicator.style.opacity = '1';
    }
}

pollEvents();