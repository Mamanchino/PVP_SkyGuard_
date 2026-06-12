const navbar = document.querySelector('.navbar');
function getRealBackgroundColor(element) {
    while(element && element !== document.documentElement) {
        const color = window.getComputedStyle(element).backgroundColor;

        if (color && color !== 'transparent' && color !== 'rgba(0, 0, 0, 0)') {
            return color;
        }
        element = element.parentElement;
    }
    return getComputedStyle(document.documentElement).backgroundColor;
}
function isLightColor(color) {
    const match = color.match(/\d+/g);
    if (!match)
        return false;
    const [r, g, b] = match.map(Number);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
    return brightness > 155;
}
function updateNavbarTheme() {
    const navRect = navbar.getBoundingClientRect();

    const x = window.innerWidth / 2;
    const y = navRect.bottom + 1;

    const element = document.elementFromPoint(x, y);

    if (!element) {
        return;
    }
    const backgroundColor = getRealBackgroundColor(element);
    navbar.classList.toggle('navbar-light', isLightColor(backgroundColor));
}

window.addEventListener('scroll', updateNavbarTheme);
window.addEventListener('resize', updateNavbarTheme);
updateNavbarTheme();