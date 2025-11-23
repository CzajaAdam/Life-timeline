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

// Datepicker
const dateInput = document.getElementById('dateInput');
const dateValue = document.getElementById('dateValue');
const datepicker = document.getElementById('datepicker');
const monthYear = document.getElementById('monthYear');
const prevMonth = document.getElementById('prevMonth');
const nextMonth = document.getElementById('nextMonth');
const daysContainer = document.getElementById('daysContainer');

let currentDate = new Date();
let selectedDate = null;

const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

// Toggle and close datepicker
dateInput.addEventListener('click', () => {
    datepicker.classList.toggle('active');
});

document.addEventListener('click', (e) => {
    if (!dateInput.contains(e.target) && !datepicker.contains(e.target)) {
        datepicker.classList.remove('active');
    }
});

// Month navigation
prevMonth.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextMonth.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Create day element helper
function createDayElement(day, date, isCurrentMonth) {
    const dayElement = document.createElement('div');
    const today = new Date();
    
    dayElement.textContent = day;
    dayElement.className = `aspect-square flex items-center justify-center rounded-lg cursor-pointer text-sm transition ${
        isCurrentMonth 
            ? 'text-charcoal-50 hover:bg-charcoal-700' 
            : 'text-charcoal-500 hover:bg-charcoal-700/50'
    }`;

    // Highlight today
    if (isCurrentMonth && date.toDateString() === today.toDateString()) {
        dayElement.classList.add('bg-charcoal-700');
    }

    // Highlight selected date
    if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
        dayElement.classList.remove('bg-charcoal-700', 'hover:bg-charcoal-700');
        dayElement.classList.add('bg-caleadon-600', 'hover:bg-caleadon-700');
    }

    dayElement.addEventListener('click', () => selectDate(date));
    
    return dayElement;
}

// Select date helper
function selectDate(date) {
    selectedDate = date;
    dateInput.value = formatDate(date);
    dateValue.value = date.toISOString().split('T')[0];
    datepicker.classList.remove('active');
    renderCalendar();
}

// Render calendar
function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    monthYear.textContent = `${months[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const prevMonthDays = new Date(year, month, 0).getDate();

    daysContainer.innerHTML = '';

    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const prevDay = prevMonthDays - i;
        const date = new Date(year, month - 1, prevDay);
        daysContainer.appendChild(createDayElement(prevDay, date, false));
    }

    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        daysContainer.appendChild(createDayElement(day, date, true));
    }

    // Next month days to fill grid
    const totalCells = daysContainer.children.length;
    const remainingCells = 42 - totalCells;
    
    for (let day = 1; day <= remainingCells; day++) {
        const date = new Date(year, month + 1, day);
        daysContainer.appendChild(createDayElement(day, date, false));
    }
}

function formatDate(date) {
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

// Initialize
renderCalendar();

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