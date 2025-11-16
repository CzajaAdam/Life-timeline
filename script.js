function regLogSwitch() {
    const regForm = document.getElementById('registration-form');
    const logForm = document.getElementById('login-form');
    const switchLink = document.getElementById('switch-link');
    const logRegDesc = document.getElementById('log-reg-desc');

    // Switch to Regsitration
    if (regForm.classList.contains('hidden')) {
        switchLink.textContent = 'Switch to Login';
        logRegDesc.innerHTML = 'Join us today! Create your free account to start building your <span class="text-cambridge-500 font-bold">Life Timeline</span>.';
        regForm.classList.remove('hidden');
        switchLink.classList.replace('text-caleadon-600', 'text-cambridge-500');
        switchLink.classList.replace('hover:text-caleadon-700', 'hover:text-cambridge-600');
        logForm.classList.add('hidden');

    // Switch to Login
    }else{
        switchLink.textContent = "Create an Account";
        logRegDesc.innerHTML = 'Welcome back! Please enter your credentials to access your <span class="text-caleadon-600 font-bold">Life Timeline</span>.';
        switchLink.classList.replace('text-cambridge-500', 'text-caleadon-600');
        switchLink.classList.replace('hover:text-cambridge-600', 'hover:text-caleadon-700');
        regForm.classList.add('hidden');
        logForm.classList.remove('hidden');
    }
}