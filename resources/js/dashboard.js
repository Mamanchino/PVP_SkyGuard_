
const droneId = document.body.dataset.droneId;
const lastEventId = document.body.dataset.lastEventId || 0;
const droneName = document.body.dataset.droneName;

const pane = document.querySelector('.notification-pane');
const listContaienr = document.querySelector('.notification-list');

const errorPane = document.getElementById('error-pane');
const errorConatiner = document.getElementById('alert-menu');
const currentErrors = [];

const events = new EventSource(`/drones/${droneId}/drone-events?last_event_id=${lastEventId}`);
console.log("dashboard.js loaded");
console.log("droneId:", droneId);

events.onopen = () => {
    console.log("SSE connected");
};
events.onmessage = (event) => {
    console.log("SSE message received");
    const notifications = JSON.parse(event.data);

    if (Array.isArray(notifications)){
        notifications.forEach(item => {
            proccesNotifications(item);
        })
    } else {
        proccesNotifications(notifications)
    }
    
};
events.onerror = (error) => {
    console.error("Event source failed or disconnected", error);
}
function proccesNotifications(data){
    if (data.severity === 'info'){
        addNotification(data);
    }
    
    if (data.severity === 'critical' || data.severity === 'error'){
        if(!currentErrors.includes(data.id)){
            console.log("SSE ERROR received", data.event_type);
            document.querySelector(".empty-alert-item")?.remove();
            addAlertNotification(data);
            currentErrors.push(data.id);
            showalertindicator();
        }
    }

}

function addNotification(event) {
    if(!pane) return;

    const item = document.createElement('div');
    item.className = 'notification-container';

    item.innerHTML = `
        <div class="alert-svg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                <path fill="currentColor"
                d="M11.53 2.3A1.85 1.85 0 0 0 10 1.21A1.85 1.85 0 0 0
                8.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67
                0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z" />
            </svg>
        </div>
        <div class="notification-message">
            <strong style= " color:#f0eded">${droneName}</strong>
            <p class="notification-type">${event.event_type}</p>
            <strong class="notification-date">${event.started_at}</strong>
        </div>
    `;

    listContaienr.insertBefore(item, listContaienr.firstChild);
}

function addAlertNotification(data){
    const alertButton = document.querySelector(".alert-notif");

    const ids = alertButton.dataset.alertIds
        ? alertButton.dataset.alertIds.split(",").filter(Boolean)
        : [];

    if (!ids.includes(String(data.id))) {
        ids.push(String(data.id));
        alertButton.dataset.alertIds = ids.join(",");
    }
    if(!errorPane) return;
    
    const errorItem = document.createElement('div');
    errorItem.className = 'dropdown-item alert-item';
    errorItem.innerHTML = `
        <strong class="drone-name-error">${droneName}</strong>
        <p class="error-message">${data.event_type} </p>
        <hr class="divider">
    `;

    errorConatiner.insertBefore(errorItem, errorConatiner.firstChild);

}

function showalertindicator() {
    const alertindicator = document.getElementById('alert-indicator');

    if (alertindicator) {
        alertindicator.style.backgroundColor = 'red';
        alertindicator.style.opacity = '1';
    }
}

