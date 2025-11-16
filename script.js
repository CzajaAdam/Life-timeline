function regLogSwitch() {
    const regForm = document.getElementById('registration-form');
    const logForm = document.getElementById('login-form');
    const switchLink = document.getElementById('switch-link');
    if (regForm.classList.contains('hidden')) {
        switchLink.textContent = 'Switch to Login';
        regForm.classList.remove('hidden');
        logForm.classList.add('hidden');
    }else{
        switchLink.textContent = "Create an Account";
        regForm.classList.add('hidden');
        logForm.classList.remove('hidden');
    }
}