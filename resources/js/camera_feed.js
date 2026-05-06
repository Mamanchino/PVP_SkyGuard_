const feed = document.getElementById('airsim-camera-feed');

if (feed && feed.dataset.baseSrc) {
    feed.src = feed.dataset.baseSrc;
}

if (feed){
    const refreshFeed = () => {
        const baseSrc = feed.dataset.baseSrc;
        feed.src = `${baseSrc}&t=${Date.now()}`;
    };

    refreshFeed();
    //setInterval(refreshFeed, 1000);
}

