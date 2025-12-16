// Menu toggle functions
function menuToggle() {
    const menu = document.getElementById('menu');
    const nav = document.getElementById('nav');
    const listButton = document.getElementById('list-button');
    
    nav.classList.toggle('border-r');
    listButton.classList.toggle('hidden');
    listButton.classList.toggle('inline-flex');
    menu.classList.toggle('hidden');
}

// Desktop menu toggle
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

// Icon dropdown functionality
if (document.getElementById('icon-dropdown-btn')) {
    // Toggle dropdown
    document.getElementById('icon-dropdown-btn').addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent this click from immediately closing the dropdown
        const grid = document.getElementById('icon-grid');
        const arrow = document.getElementById('dropdown-arrow');
        grid.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const iconGrid = document.getElementById('icon-grid');
        const dropdownBtn = document.getElementById('icon-dropdown-btn');
        const arrow = document.getElementById('dropdown-arrow');
        
        // Check if click is outside both the dropdown button and the grid
        if (!dropdownBtn.contains(event.target) && !iconGrid.contains(event.target)) {
            iconGrid.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    });
    
    // Prevent clicks inside the grid from closing it
    document.getElementById('icon-grid').addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Icon selection
    document.querySelectorAll('.icon-option').forEach(button => {
        button.addEventListener('click', function() {
            // Remove selected class from all buttons
            document.querySelectorAll('.icon-option').forEach(btn => {
                btn.classList.remove('selected', 'ring-2', 'ring-caleadon-500');
            });
            
            // Add selected class to clicked button
            this.classList.add('selected', 'ring-2', 'ring-caleadon-500');
            
            // Update hidden input and display
            const value = this.dataset.value;
            document.getElementById('event-icon').value = value;
            document.getElementById('selected-icon-name').innerHTML = '<i class="' + value + ' text-2xl"></i>';
            document.getElementById('selected-icon-name').classList.remove('text-charcoal-500');
            document.getElementById('selected-icon-name').classList.add('text-charcoal-200');
            
            // Close dropdown
            document.getElementById('icon-grid').classList.add('hidden');
            document.getElementById('dropdown-arrow').classList.remove('rotate-180');
        });
    });
    
    // Create Event Form Toggle
    function toggleCreateEventForm() {
        const form = document.getElementById('create-event-form');
        form.classList.toggle('hidden');
    }
}

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function toggleConfirmPassword() {
    const passwordInput = document.getElementById('confirm-password');
    const toggleIcon = document.getElementById('toggleConfirmIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Toggle functions for forms of event-page.php
function toggleNotesForm() {
    const form = document.getElementById('notes-form');
    form.classList.toggle('hidden');
}

function togglePeopleForm() {
    const form = document.getElementById('people-form');
    form.classList.toggle('hidden');
}

function toggleLocationForm() {
    const form = document.getElementById('location-form');
    form.classList.toggle('hidden');
}

function togglePhotosForm() {
    const form = document.getElementById('photos-form');
    form.classList.toggle('hidden');
}

// Datepicker
const picker = new FlexiDatepicker('#dateInput', {
    mode: 'single',
    minDate: new Date(),
    dateFormat: 'yyyy-MM-dd',
    onSelect: (date) => {
    document.getElementById('dateValue').value = date;
    }
});

const preview = document.getElementById('event-color');
const popover = document.getElementById('color-picker-popover');
const hiddenInput = document.getElementById('event-color-value');

// Create picker
const colorPicker = new iro.ColorPicker(popover, {
    width: 220,
    color: '#4AAF75',
    layout: [
        { component: iro.ui.Box },
        { component: iro.ui.Slider, options: { sliderType: 'hue' } }
    ]
});

// Update preview + form value
colorPicker.on('color:change', (color) => {
    preview.style.backgroundColor = color.hexString;
    hiddenInput.value = color.hexString;
});

// Toggle picker
preview.addEventListener('click', (e) => {
    const rect = preview.getBoundingClientRect();

    popover.style.top = `${rect.bottom + window.scrollY + 8}px`;
    popover.style.left = `${rect.left + window.scrollX}px`;

    popover.classList.toggle('hidden');
});

// Close on outside click
document.addEventListener('click', (e) => {
    if (!popover.contains(e.target) && !preview.contains(e.target)) {
        popover.classList.add('hidden');
    }
});
