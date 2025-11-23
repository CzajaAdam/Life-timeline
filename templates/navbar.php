<a id="list-button" class="absolute top-0 left-0 md:hidden text-charcoal-200 hover:text-charcoal-400 mx-auto inline-flex items-center justify-center p-4" onclick="menuToggle()">
    <i class="fa-solid fa-list text-4xl"></i>
</a>
<nav id="nav" class="fixed left-0 top-0 h-full md:border-r border-charcoal-700 z-20">
    <div id="menu" class="h-full bg-charcoal-800 shadow-lg p-4 hidden md:flex">
        <div class="flex flex-col space-y-6 w-full">
            <a id="close-button" onclick="menuToggle()" class="flex items-center mb-4 text-charcoal-200 hover:text-red-400 md:hidden cursor-pointer">
                <i class="fa-solid fa-xmark text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="hidden md:inline-flex">Close</span>
            </a>
            <a id="desktop-close-button" onclick="desktopMenuToggle()" class="flex items-center mb-4 text-charcoal-200 hover:text-red-400 hidden cursor-pointer">
                <i class="fa-solid fa-xmark text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="hidden md:inline-flex">Close</span>
            </a>
            <a id="menu-button" onclick="desktopMenuToggle()" class="hidden md:flex mb-4 items-center text-charcoal-200 hover:text-charcoal-400 cursor-pointer">
                <i class="fa-solid fa-list text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="whitespace-nowrap hidden">Menu</span>
            </a>
            <hr class="w-full border-charcoal-700">
            <a href="index.php" class="flex items-center text-charcoal-200 hover:text-charcoal-400">
                <i class="fa-solid fa-house-chimney-user text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="whitespace-nowrap hidden menu-text">Dashboard</span>
            </a>
            <a href="profile.php" class="flex items-center text-charcoal-200 hover:text-charcoal-400">
                <i class="fa-solid fa-user text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="whitespace-nowrap hidden menu-text">Profile</span>
            </a>
            <a href="settings.php" class="flex items-center text-charcoal-200 hover:text-charcoal-400">
                <i class="fa-solid fa-gear text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="whitespace-nowrap hidden menu-text">Settings</span>
            </a>
            <hr class="w-full border-charcoal-700">
            <!-- Logout with a confirmation -->
            <a href="src/logout.php" class="flex items-center text-charcoal-200 hover:text-red-400">
                <i class="fa-solid fa-right-from-bracket text-3xl w-10 text-center flex-shrink-0"></i>
                <span class="whitespace-nowrap hidden menu-text">Logout</span>
            </a>
        </div>
    </div>
</nav>