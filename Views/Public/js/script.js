const hexContainer = document.getElementById('hex-background');

function getRandomPosition() {
    const x = Math.random() * window.innerWidth;
    const y = Math.random() * window.innerHeight;
    return { x, y };
}

function getRandomHexClass() {
    return Math.random() > 0.25 ? 'bi-hexagon-fill' : 'bi-hexagon';
}

function createHexagon() {
    const hexagon = document.createElement('i');
    hexagon.classList.add('bi', 'hexagon-icon', getRandomHexClass());

    const { x, y } = getRandomPosition();
    hexagon.style.left = `${x}px`;
    hexagon.style.top = `${y}px`;

    hexContainer.appendChild(hexagon);

    setTimeout(() => {
        hexagon.style.opacity = 1;
    }, 100);

    setTimeout(() => {
        const newPosition = getRandomPosition();
        hexagon.style.left = `${newPosition.x}px`;
        hexagon.style.top = `${newPosition.y}px`;
    }, 500);

    const timeOnScreen = 2000 + Math.random() * 3000;
    setTimeout(() => {
        hexagon.style.opacity = 1;
        setTimeout(() => {
            hexContainer.removeChild(hexagon);
        }, 1000);
    }, timeOnScreen);
}

setInterval(createHexagon, 500);