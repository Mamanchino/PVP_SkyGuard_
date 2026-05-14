async function pollBattery(){
    try{
        const vehicleName = document.body.dataset.vehicleName;

        if (!vehicleName) {
            console.error("Missing sim vehicle name");
            return;
        }

        const battery_level_poll = await fetch(
            `http://localhost:5000/battery?vehicle_name=${encodeURIComponent(vehicleName)}`
        );

        if (!battery_level_poll.ok){
            throw new Error(`HTTP error: status ${battery_level_poll.status}`);
        }

        const data = await battery_level_poll.json();
        const battery = data.battery;

        if (battery === null || battery === undefined){
            return;
        }

        const fill = document.querySelector(".battery-fill");
        const text = document.querySelector(".battery-percentage");

        fill.style.width = `${battery}%`;
        fill.style.backgroundColor = battery < 30 ? "#f44336" :
            battery < 60 ? "#ff9800" : "#4caf50";
        text.textContent = `${battery}%`;

    } catch(error){
        console.error("Polling failed", error);
    } finally {
        setTimeout(pollBattery, 3000);
    }
}
async function pollConnectionStatus(){
    try{
        const vehicleName = document.body.dataset.vehicleName;
        if (!vehicleName) {
            console.error("Missing sim vehicle name");
            return;
        }
        const connection_status_poll = await fetch(
            `http://localhost:5000/connection_status?vehicle_name=${encodeURIComponent(vehicleName)}`
        );
        if (!connection_status_poll.ok){
            throw new Error(`HTTP error: status ${connection_status_poll.status}`);
        }
        const data = await connection_status_poll.json();
        const connection_status = data.connection_status;
        if (connection_status === null || connection_status === undefined){
            return;
        }
        const text = document.querySelector(".drone-connection-status");
        text.textContent = connection_status ? "online" : "offline";
        text.style.color = connection_status ? "#4caf50" : "#f44336";
    } catch(error){
        console.error("Polling failed", error);
    } finally {
        setTimeout(pollConnectionStatus, 3000);
    }
}
pollBattery();
pollConnectionStatus();
