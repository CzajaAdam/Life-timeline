function menuToggle() {
    const menu = document.getElementById('menu');
    const listButton = document.getElementById('list-button');
    const cancelButton = document.getElementById('cancel-button');

    menu.classList.toggle('translate-x-0');
    menu.classList.toggle('-translate-x-full');
    listButton.classList.toggle('hidden');
}