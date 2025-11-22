<!-- Navbar -->
<a id="list-button" class="md:hidden text-charcoal-200 hover:text-charcoal-400 mx-auto inline-flex items-center justify-center p-4" onclick="menuToggle()">
    <i class="fa-solid fa-list text-4xl"></i>
</a>
<!-- md:w-20 tu problem -->
<div id="menu" class="fixed top-0 left-0 h-full md:block bg-charcoal-800 shadow-lg p-4 md:w-20 w-64 md:hover:w-64 flex flex-col justify-start items-center -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out z-50 group">
    
    <div class="flex flex-col space-y-4 md:space-y-6 w-full">
        
        <!-- Buttons -->
        <a id="cancel-button" onclick="menuToggle()" class="flex items-center mb-0 space-x-2 text-charcoal-200 hover:text-charcoal-400 md:hidden">
            <i class="fa-solid fa-xmark text-4xl w-10 text-center"></i>
            <span>Close</span>
        </a>
        <hr class="w-full border-charcoal-700 mt-2 md:hidden">
        <a href="index.php" class="flex items-center space-x-2 text-charcoal-200 hover:text-charcoal-400">
            <i class="fa-solid fa-house-chimney-user text-3xl w-10 text-center flex-shrink-0"></i>
            <span class="whitespace-nowrap md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300">Dashboard</span>
        </a>
        <a href="profile.php" class="flex items-center space-x-2 text-charcoal-200 hover:text-charcoal-400">
            <i class="fa-solid fa-user text-3xl w-10 text-center flex-shrink-0"></i>
            <span class="whitespace-nowrap md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300">Profile</span>
        </a>
        <a href="settings.php" class="flex items-center space-x-2 text-charcoal-200 hover:text-charcoal-400">
            <i class="fa-solid fa-gear text-3xl w-10 text-center flex-shrink-0"></i>
            <span class="whitespace-nowrap md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300">Settings</span>
        </a>
        <a href="src/logout.php" class="flex items-center space-x-2 text-charcoal-200 hover:text-charcoal-400 mt-auto">
            <i class="fa-solid fa-right-from-bracket text-3xl w-10 text-center flex-shrink-0"></i>
            <span class="whitespace-nowrap md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300">Logout</span>
        </a>
    </div>
</div>