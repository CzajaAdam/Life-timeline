<?php
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-950 relative">
    <div class="container mx-auto flex flex-col min-h-screen p-4 pb-24">

        <!-- Navbar -->
        <?php include 'templates/navbar.php'; ?>

        <!-- Header -->
        <header class="flex justify-start items-center flex-col pt-8 pb-4 animate-fade-in" role="banner">
            <h1 class="text-4xl md:text-5xl text-center font-bold text-charcoal-50 mb-3 tracking-tight">Settings</h1>
            <p class="text-charcoal-400 text-center text-sm tracking-wider uppercase">Manage your account and preferences</p>
        </header>

        <!-- Main Content -->
        <main role="main" class="flex-grow">
            <section class="flex flex-col justify-start items-center px-4 py-8 max-w-4xl mx-auto w-full" aria-labelledby="settings-heading">
                
                <!-- Settings Navigation Tabs -->
                <div class="w-full mb-8 flex flex-wrap gap-2 justify-center md:justify-start border-b border-charcoal-800">
                    <button class="settings-tab cursor-pointer active px-6 py-3 text-charcoal-200 font-semibold border-b-2 border-caleadon-500 transition-all" data-tab="account">
                        <i class="fa-solid fa-user mr-2"></i>Account
                    </button>
                    <button class="settings-tab cursor-pointer px-6 py-3 text-charcoal-400 font-semibold border-b-2 border-transparent hover:text-charcoal-200 transition-all" data-tab="privacy">
                        <i class="fa-solid fa-lock mr-2"></i>Privacy
                    </button>
                    <button class="settings-tab cursor-pointer px-6 py-3 text-charcoal-400 font-semibold border-b-2 border-transparent hover:text-charcoal-200 transition-all" data-tab="notifications">
                        <i class="fa-solid fa-bell mr-2"></i>Notifications
                    </button>
                    <button class="settings-tab cursor-pointer px-6 py-3 text-charcoal-400 font-semibold border-b-2 border-transparent hover:text-charcoal-200 transition-all" data-tab="appearance">
                        <i class="fa-solid fa-palette mr-2"></i>Appearance
                    </button>
                </div>

                <!-- Account Settings Tab -->
                <div id="account-tab" class="settings-content w-full">
                    <div class="space-y-6">
                        <!-- Profile Section -->
                        <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 border border-charcoal-700 shadow-xl">
                            <h2 class="text-2xl font-bold text-charcoal-50 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-user-circle text-caleadon-500"></i>
                                Profile Information
                            </h2>
                            
                            <form class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-charcoal-200 text-sm font-semibold mb-2">
                                            <i class="fa-solid fa-user mr-2 text-caleadon-500"></i>Full Name
                                        </label>
                                        <input type="text" placeholder="Your full name" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-charcoal-200 text-sm font-semibold mb-2">
                                            <i class="fa-solid fa-envelope mr-2 text-caleadon-500"></i>Email Address
                                        </label>
                                        <input type="email" placeholder="your.email@example.com" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-charcoal-200 text-sm font-semibold mb-2">
                                        <i class="fa-solid fa-align-left mr-2 text-caleadon-500"></i>Bio
                                    </label>
                                    <textarea rows="3" placeholder="Tell us about yourself..." class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all resize-none"></textarea>
                                </div>
                                <button type="submit" class="bg-gradient-to-r from-caleadon-600 to-caleadon-500 hover:from-caleadon-500 hover:to-caleadon-400 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-caleadon-500/50 cursor-pointer">
                                    <i class="fa-solid fa-save mr-2"></i>Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Password Section -->
                        <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 border border-charcoal-700 shadow-xl">
                            <h2 class="text-2xl font-bold text-charcoal-50 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-key text-caleadon-500"></i>
                                Change Password
                            </h2>
                            
                            <form class="space-y-4">
                                <div>
                                    <label class="block text-charcoal-200 text-sm font-semibold mb-2">
                                        <i class="fa-solid fa-lock mr-2 text-caleadon-500"></i>Current Password
                                    </label>
                                    <input type="password" placeholder="Enter your current password" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-charcoal-200 text-sm font-semibold mb-2">
                                        <i class="fa-solid fa-lock mr-2 text-caleadon-500"></i>New Password
                                    </label>
                                    <input type="password" placeholder="Enter your new password" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-charcoal-200 text-sm font-semibold mb-2">
                                        <i class="fa-solid fa-lock mr-2 text-caleadon-500"></i>Confirm Password
                                    </label>
                                    <input type="password" placeholder="Confirm your new password" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all">
                                </div>
                                <button type="submit" class="bg-gradient-to-r from-caleadon-600 to-caleadon-500 hover:from-caleadon-500 hover:to-caleadon-400 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-caleadon-500/50 cursor-pointer">
                                    <i class="fa-solid fa-check mr-2"></i>Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings Tab -->
                <div id="privacy-tab" class="settings-content hidden w-full">
                    <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 border border-charcoal-700 shadow-xl">
                        <h2 class="text-2xl font-bold text-charcoal-50 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-shield text-caleadon-500"></i>
                            Privacy Settings
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-charcoal-800 rounded-lg hover:bg-charcoal-750 transition-colors">
                                <div>
                                    <h3 class="text-charcoal-200 font-semibold">Profile Visibility</h3>
                                    <p class="text-charcoal-400 text-sm">Allow others to view your profile</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-charcoal-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-caleadon-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-caleadon-500"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-charcoal-800 rounded-lg hover:bg-charcoal-750 transition-colors">
                                <div>
                                    <h3 class="text-charcoal-200 font-semibold">Event Sharing</h3>
                                    <p class="text-charcoal-400 text-sm">Allow sharing of your events</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-charcoal-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-caleadon-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-caleadon-500"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-charcoal-800 rounded-lg hover:bg-charcoal-750 transition-colors">
                                <div>
                                    <h3 class="text-charcoal-200 font-semibold">Data Collection</h3>
                                    <p class="text-charcoal-400 text-sm">Allow analytics and usage tracking</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-charcoal-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-caleadon-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-caleadon-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings Tab -->
                <div id="notifications-tab" class="settings-content hidden w-full">
                    <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 border border-charcoal-700 shadow-xl">
                        <h2 class="text-2xl font-bold text-charcoal-50 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-bell text-caleadon-500"></i>
                            Notification Preferences
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-charcoal-800 rounded-lg hover:bg-charcoal-750 transition-colors">
                                <div>
                                    <h3 class="text-charcoal-200 font-semibold">Email Notifications</h3>
                                    <p class="text-charcoal-400 text-sm">Receive updates via email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-charcoal-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-caleadon-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-caleadon-500"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-charcoal-800 rounded-lg hover:bg-charcoal-750 transition-colors">
                                <div>
                                    <h3 class="text-charcoal-200 font-semibold">Event Reminders</h3>
                                    <p class="text-charcoal-400 text-sm">Get reminded about upcoming events</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-charcoal-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-caleadon-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-caleadon-500"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-charcoal-800 rounded-lg hover:bg-charcoal-750 transition-colors cursor-not-allowed opacity-60">
                                <div>
                                    <h3 class="text-charcoal-200 font-semibold">Weekly Digest</h3>
                                    <p class="text-charcoal-400 text-sm">Receive a weekly summary of your events</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-not-allowed">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-charcoal-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-caleadon-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-caleadon-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance Settings Tab -->
                <div id="appearance-tab" class="settings-content hidden w-full">
                    <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 border border-charcoal-700 shadow-xl">
                        <h2 class="text-2xl font-bold text-charcoal-50 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-palette text-caleadon-500"></i>
                            Appearance Settings
                        </h2>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-charcoal-200 font-semibold mb-4">Theme</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="theme" value="dark" class="sr-only peer" checked>
                                        <div class="p-4 rounded-lg bg-charcoal-800 border-2 border-charcoal-700 peer-checked:border-caleadon-500 peer-checked:bg-charcoal-750 transition-all hover:border-charcoal-600">
                                            <i class="fa-solid fa-moon text-caleadon-500 text-2xl mb-2 block"></i>
                                            <p class="text-charcoal-200 font-semibold">Dark</p>
                                            <p class="text-charcoal-400 text-sm">Dark theme (default)</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="theme" value="light" class="sr-only peer">
                                        <div class="p-4 rounded-lg bg-charcoal-800 border-2 border-charcoal-700 peer-checked:border-caleadon-500 peer-checked:bg-charcoal-750 transition-all hover:border-charcoal-600">
                                            <i class="fa-solid fa-sun text-caleadon-500 text-2xl mb-2 block"></i>
                                            <p class="text-charcoal-200 font-semibold">Light</p>
                                            <p class="text-charcoal-400 text-sm">Light theme</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="theme" value="auto" class="sr-only peer">
                                        <div class="p-4 rounded-lg bg-charcoal-800 border-2 border-charcoal-700 peer-checked:border-caleadon-500 peer-checked:bg-charcoal-750 transition-all hover:border-charcoal-600">
                                            <i class="fa-solid fa-circle-half-stroke text-caleadon-500 text-2xl mb-2 block"></i>
                                            <p class="text-charcoal-200 font-semibold">Auto</p>
                                            <p class="text-charcoal-400 text-sm">System preference</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-charcoal-200 font-semibold mb-4">Font Size</h3>
                                <div class="flex items-center gap-4">
                                    <span class="text-charcoal-400">Small</span>
                                    <input type="range" min="12" max="18" value="14" class="flex-grow h-2 bg-charcoal-800 rounded-lg appearance-none cursor-pointer accent-caleadon-500">
                                    <span class="text-charcoal-400">Large</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="w-full mt-8 bg-gradient-to-br from-red-950 to-red-900 rounded-2xl p-8 border border-red-800 shadow-xl">
                    <h2 class="text-2xl font-bold text-red-50 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        Danger Zone
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-red-900/30 rounded-lg">
                            <div>
                                <h3 class="text-red-200 font-semibold">Delete Account</h3>
                                <p class="text-red-400 text-sm">Permanently delete your account and all associated data</p>
                            </div>
                            <button class="px-6 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition-all duration-300 cursor-pointer">
                                <i class="fa-solid fa-trash-can mr-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>

            </section>
        </main>

    </div>

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
    
    <script>
        // Settings tab switching
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                const tabName = tab.dataset.tab;
                
                // Hide all tabs
                document.querySelectorAll('.settings-content').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Remove active state from all tabs
                document.querySelectorAll('.settings-tab').forEach(t => {
                    t.classList.remove('border-caleadon-500', 'text-charcoal-200');
                    t.classList.add('border-transparent', 'text-charcoal-400');
                });
                
                // Show selected tab
                document.getElementById(tabName + '-tab').classList.remove('hidden');
                
                // Add active state to clicked tab
                tab.classList.remove('border-transparent', 'text-charcoal-400');
                tab.classList.add('border-caleadon-500', 'text-charcoal-200');
            });
        });
    </script>
    <script src="script.js"></script>
</body>
</html>
