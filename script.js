// Menu controls

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

// Password visibility toggles

function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye', !isPassword);
    icon.classList.toggle('fa-eye-slash', isPassword);
}

function toggleConfirmPassword() {
    const input = document.getElementById('confirm-password');
    const icon = document.getElementById('toggleConfirmIcon');
    
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye', !isPassword);
    icon.classList.toggle('fa-eye-slash', isPassword);
}

// Form toggles

function toggleNotesForm() {
    document.getElementById('notes-form')?.classList.toggle('hidden');
}

function togglePeopleForm() {
    document.getElementById('people-form')?.classList.toggle('hidden');
}

function toggleLocationForm() {
    document.getElementById('location-form')?.classList.toggle('hidden');
}

function togglePhotosForm() {
    document.getElementById('photos-form')?.classList.toggle('hidden');
}

function toggleCreateEventForm() {
    document.getElementById('create-event-form')?.classList.toggle('hidden');
}

// Icon dropdown

function initIconDropdown() {
    const dropdownBtn = document.getElementById('icon-dropdown-btn');
    if (!dropdownBtn) return;
    
    const grid = document.getElementById('icon-grid');
    const arrow = document.getElementById('dropdown-arrow');
    const iconInput = document.getElementById('event-icon');
    const display = document.getElementById('selected-icon-name');
    
    // Toggle dropdown
    dropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        grid.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    });
    
    // Close dropdown on outside click
    document.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !grid.contains(e.target)) {
            grid.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    });
    
    // Icon selection
    document.querySelectorAll('.icon-option').forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove selection from all
            document.querySelectorAll('.icon-option').forEach(b => {
                b.classList.remove('selected', 'ring-2', 'ring-caleadon-500');
            });
            
            // Select clicked icon
            btn.classList.add('selected', 'ring-2', 'ring-caleadon-500');
            
            // Update form
            const iconValue = btn.dataset.value;
            iconInput.value = iconValue;
            display.innerHTML = `<i class="${iconValue} text-2xl"></i>`;
            display.classList.remove('text-charcoal-500');
            display.classList.add('text-charcoal-200');
            
            // Close dropdown
            grid.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            
            // Revalidate if validator exists
            if (window.eventFormValidator) {
                window.eventFormValidator.revalidateField('#event-icon');
            }
        });
    });
}

// Datepicker

function initDatepicker() {
    const dateInput = document.getElementById('dateInput');
    if (!dateInput) return;
    
    new FlexiDatepicker('#dateInput', {
        mode: 'single',
        minDate: new Date(),
        dateFormat: 'yyyy-MM-dd',
        onSelect: (date) => {
            document.getElementById('dateValue').value = date;
        }
    });
}

// Color picker

function initColorPicker() {
    const preview = document.getElementById('event-color');
    const popover = document.getElementById('color-picker-popover');
    const hiddenInput = document.getElementById('event-color-value');
    
    if (!preview || !popover || !hiddenInput) return;
    
    // Create color picker
    const colorPicker = new iro.ColorPicker(popover, {
        width: 220,
        color: '#4AAF75',
        layout: [
            { component: iro.ui.Box },
            { component: iro.ui.Slider, options: { sliderType: 'hue' } }
        ]
    });
    
    // Update preview and hidden input on color change
    colorPicker.on('color:change', (color) => {
        preview.style.backgroundColor = color.hexString;
        hiddenInput.value = color.hexString;
    });
    
    // Toggle popover on preview click
    preview.addEventListener('click', () => {
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
}

// Form validation

function initFormValidation() {
    const form = document.getElementById('create-event-form');
    if (!form) return;
    
    const validator = new JustValidate('#create-event-form', {
        errorFieldCssClass: 'border-red-500',
        errorLabelCssClass: 'text-red-500 text-sm mt-1',
        focusInvalidField: true,
        lockForm: true,
    });
    
    // Add validation rules
    validator
        .addField('#event-type', [
            { rule: 'required', errorMessage: 'Event title is required' },
            { rule: 'minLength', value: 2, errorMessage: 'Event title must be at least 2 characters' },
            { rule: 'maxLength', value: 64, errorMessage: 'Event title is too long' },
        ])
        .addField('#event-description', [
            { rule: 'maxLength', value: 2048, errorMessage: 'Event description is too long' },
        ])
        .addField('#dateInput', [
            { rule: 'required', errorMessage: 'Please select an event date' },
        ])
        .addField('#event-icon', [
            { rule: 'required', errorMessage: 'Please choose an event icon' },
        ])
        .onSuccess((e) => e.target.submit());
    
    // Store validator globally for icon dropdown revalidation
    window.eventFormValidator = validator;
}

// Delete modal management

let deleteData = {
    type: null,
    id: null,
    url: null,
    name: null
};

function openDeleteModal(type, id, url, name = null) {
    deleteData = { type, id, url, name };
    
    // Update modal text based on type
    const typeLabels = {
        'note': 'note',
        'person': 'person',
        'location': 'location',
        'photo': 'photo',
        'event': 'event'
    };
    
    // Use name if provided (for events), otherwise use type label
    const displayText = name ? `"${name}"` : typeLabels[type] || 'item';
    
    // Update the appropriate element based on what's available
    const itemTypeElement = document.getElementById('deleteItemType');
    const itemNameElement = document.getElementById('deleteItemName');
    
    if (itemNameElement) {
        itemNameElement.textContent = displayText;
    } else if (itemTypeElement) {
        itemTypeElement.textContent = displayText;
    }
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteData = { type: null, id: null, url: null, name: null };
}

function confirmDelete() {
    if (deleteData.url) {
        window.location.href = deleteData.url;
    }
}

function initDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (!modal) return;
    
    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    
    // Close modal on outside click
    modal.addEventListener('click', (e) => {
        if (e.target.id === 'deleteModal') {
            closeDeleteModal();
        }
    });
}

// Initialization

document.addEventListener('DOMContentLoaded', () => {
    initIconDropdown();
    initDatepicker();
    initColorPicker();
    initFormValidation();
    initDeleteModal();
});