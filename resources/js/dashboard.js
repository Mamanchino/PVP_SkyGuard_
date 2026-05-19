const events = new EventSource('/drone-events');

events.addEventListener('person-detected', (event) => {
    const data = JSON.parse(event.data);
    console.log('Person was detected by a drone', data);
});