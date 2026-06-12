const video = document.getElementById('product-video');

const options = {
    root: null,
    threshold: 0.5
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            video.play().catch(error => {
                console.error('Error playing video:', error);
            });
        } else {
            video.pause();
        }
    });
}, options);

observer.observe(video);