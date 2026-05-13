async function pollBattery(){
    try{
        const battery_level_poll = await fetch("http://localhost:5000/battery");

        if (!battery_level_poll.ok){
            throw new Error(`HTTP error: status ${battery_level_poll.status}`);
        }

        const data = await battery_level_poll.json();
        console.log("Polled data", data);

        const battery = data.battery

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
        setTimeout(pollBattery, 3000)
    }
}
pollBattery();