function menuToggle() {
    const menu = document.getElementById('menu');
    const listButton = document.getElementById('list-button');
    
    listButton.classList.toggle('hidden');
    menu.classList.toggle('hidden');
}

function desktopMenuToggle() {
    const menu = document.getElementById('menu');
    const menuButton = document.getElementById('menu-button');
    const closeButton = document.getElementById('desktop-close-button');
    const menuTexts = document.querySelectorAll('.menu-text');
    const menuAs = menu.querySelectorAll('a');
    
    menu.classList.toggle('md:w-64');
    menuAs.forEach(a => a.classList.toggle('md:space-x-2'));
    menuTexts.forEach(text => text.classList.toggle('hidden'));
    menuButton.classList.toggle('md:flex');
    closeButton.classList.toggle('hidden');
}