<?php
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Preserve session for cURL
    session_write_close();
    
    // Get user events from the database
    $curl = curl_init('http://localhost/Lifelines/src/events/read.php');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . session_id());
    $events = curl_exec($curl);

    $events = json_decode($events, true) ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-900 relative">
    <div class="container mx-auto flex flex-col justify-between min-h-screen p-4 pb-128 md:pb-64">

        <!-- Navbar -->
        <?php include 'templates/navbar.php'; ?>

        <!-- Header -->
        <header class="flex justify-start items-center flex-col">
            <img class="h-16" src="lifelines.png" alt="">
            <h1 class="text-3xl text-center font-bold text-charcoal-50 mb-2 py-2">LifeLines</h1>
            <hr class="w-1/3 md:w-1/2 border-charcoal-700 mb-4">
        </header>

        <!-- Welcome Section -->
        <div class="flex flex-col justify-start grow pt-2 items-center px-4">
            <h2 class="text-charcoal-200 text-2xl mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h2>
            <p class="text-charcoal-300 text-center max-w-md text-lg text-pretty mb-4">This is your dashboard. From here, you can manage your LifeLines and access all the features available to you.</p>
            
            <form class="flex flex-col p-4 rounded-xl text-charcoal-50 bg-charcoal-800 border border-charcoal-700 shadow-lg" action="src/events/create.php" method="POST">
                <!-- Event Type -->
                <input class="p-3 shadow-lg mb-2 rounded bg-charcoal-700 text-charcoal-50 placeholder-charcoal-300" placeholder="Title" type="text" name="event-type">
                
                <!-- Event Description -->
                <input class="p-3 shadow-lg mb-2 rounded bg-charcoal-700 text-charcoal-50 placeholder-charcoal-300" placeholder="Description" type="text" name="event-description">
                
                <!-- Event Datepicker -->
                <div class="relative mb-2">
                    <input type="text" id="dateInput" placeholder="Select a date" readonly class="w-full p-3 shadow-lg rounded bg-charcoal-700 text-charcoal-50 placeholder-charcoal-300 border border-transparent cursor-pointer focus:outline-none focus:border-caleadon-600 transition">
                
                    <!-- Hidden input to store actual date value -->
                    <input type="hidden" id="dateValue" name="event-date">

                    <!-- Datepicker -->
                    <div id="datepicker" class="datepicker absolute top-full left-1/2 -translate-x-1/2 w-76 bg-charcoal-800 border border-charcoal-700 rounded-xl shadow-2xl p-5 mt-2 z-50">
                        <!-- Header -->
                        <div class="flex justify-between gap-5 items-center mb-5">
                            <button id="prevMonth" type="button" class="bg-charcoal-700 hover:bg-charcoal-700/80 text-charcoal-50 w-8 h-8 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <span id="monthYear" class="text-charcoal-50 font-semibold text-nowrap"></span>
                            <button id="nextMonth" type="button" class="bg-charcoal-700 hover:bg-charcoal-700/80 text-charcoal-50 w-8 h-8 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>

                        <!-- Days Grid -->
                        <div class="grid grid-cols-7 gap-2">
                            <!-- Day Names -->
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">Su</div>
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">Mo</div>
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">Tu</div>
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">We</div>
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">Th</div>
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">Fr</div>
                            <div class="text-center text-xs text-charcoal-300 font-semibold py-2">Sa</div>
                            
                            <!-- Days will be inserted here -->
                            <div id="daysContainer" class="col-span-7 grid grid-cols-7 gap-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Event Color -->
                <label for="event-color" class="text-charcoal-300 text-pretty">Event Color:</label>
                <input id="event-color" value="#15A85F" class="appearance-none w-full h-14 bg-transparent border-none cursor-pointer shadow-lg rounded-2xl" type="color" name="event-color">

                <!-- Event Icon -->
                <label for="event-icon" class="text-charcoal-300 text-pretty">Event Icon:</label>
                <div class="relative mb-2">
                    <input type="hidden" id="event-icon" name="event-icon" value="fa-solid fa-graduation-cap">
                    
                    <!-- Dropdown Button -->
                    <button type="button" id="icon-dropdown-btn" class="w-full p-3 shadow-lg rounded bg-charcoal-700 flex items-center justify-between hover:bg-charcoal-600 focus:outline-none focus:border-caleadon-600 border-2 border-transparent transition-colors">
                        <span class="flex items-center gap-3">
                            <span id="selected-icon-name" class="text-charcoal-300">Select Icon</span>
                        </span>
                        <svg class="w-4 h-4 transition-transform" id="dropdown-arrow" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Grid -->
                    <div id="icon-grid" class="hidden absolute left-1/2 -translate-x-1/2 w-96 z-10 mt-2 bg-charcoal-700 rounded shadow-lg p-3">
                        <div class="grid grid-cols-6 gap-2">
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl selected ring-2 ring-caleadon-500" data-value="fa-solid fa-graduation-cap" data-unicode="&#xf19d;">&#xf19d;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-ring" data-unicode="&#xf70b;">&#xf70b;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-heart" data-unicode="&#xf004;">&#xf004;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-baby" data-unicode="&#xf77c;">&#xf77c;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-house" data-unicode="&#xf015;">&#xf015;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-briefcase" data-unicode="&#xf0b1;">&#xf0b1;</button>
                            
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-plane" data-unicode="&#xf072;">&#xf072;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-globe" data-unicode="&#xf0ac;">&#xf0ac;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-mountain" data-unicode="&#xf6fc;">&#xf6fc;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-car" data-unicode="&#xf1b9;">&#xf1b9;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-book" data-unicode="&#xf02d;">&#xf02d;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-university" data-unicode="&#xf19c;">&#xf19c;</button>
                            
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-bullhorn" data-unicode="&#xf0a1;">&#xf0a1;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-trophy" data-unicode="&#xf091;">&#xf091;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-heartbeat" data-unicode="&#xf21e;">&#xf21e;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-person-running" data-unicode="&#xf70c;">&#xf70c;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-star" data-unicode="&#xf005;">&#xf005;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-music" data-unicode="&#xf001;">&#xf001;</button>
                            
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-camera" data-unicode="&#xf030;">&#xf030;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-palette" data-unicode="&#xf53f;">&#xf53f;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-gamepad" data-unicode="&#xf11b;">&#xf11b;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-users" data-unicode="&#xf0c0;">&#xf0c0;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-building" data-unicode="&#xf1ad;">&#xf1ad;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-gift" data-unicode="&#xf06b;">&#xf06b;</button>
                            
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-cake-candles" data-unicode="&#xf1fd;">&#xf1fd;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-bolt" data-unicode="&#xf0e7;">&#xf0e7;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-fire" data-unicode="&#xf06d;">&#xf06d;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-flag-checkered" data-unicode="&#xf11e;">&#xf11e;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-medal" data-unicode="&#xf5a2;">&#xf5a2;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-moon" data-unicode="&#xf186;">&#xf186;</button>
                            
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-sun" data-unicode="&#xf185;">&#xf185;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-champagne-glasses" data-unicode="&#xf79f;">&#xf79f;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-dog" data-unicode="&#xf6d3;">&#xf6d3;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-tree" data-unicode="&#xf1bb;">&#xf1bb;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-umbrella-beach" data-unicode="&#xf5ca;">&#xf5ca;</button>
                            <button type="button" class="icon-option w-12 h-12 bg-charcoal-600 hover:bg-charcoal-500 rounded transition-colors flex items-center justify-center text-2xl" data-value="fa-solid fa-lightbulb" data-unicode="&#xf0eb;">&#xf0eb;</button>
                        </div>
                    </div>
                </div>

                <button class="p-3 shadow-lg rounded bg-caleadon-600 hover:bg-caleadon-700 cursor-pointer text-charcoal-50 font-semibold" type="submit">Create Event</button>
            </form>
        </div>

        <div class="flex justify-center w-full">
            <hr class="w-1/3 md:w-1/2 border-charcoal-700 my-16">
        </div>

        <!-- Timeline Events -->
        <div class="flex flex-col w-full md:w-[50vw] mx-auto px-4 md:px-0">
            
            <h2 class="text-charcoal-100 text-2xl mb-8 text-4xl text-center font-bold">Your <span class="text-caleadon-600">LifeLine!</span></h2>

            <?php foreach ($events as $event): ?>
                <!-- Timeline Event -->
                <div class="relative flex flex-col md:flex-row bg-charcoal-100 mb-5 rounded-lg shadow-[0_30px_60px_-12px_rgba(50,50,93,0.25),0_18px_36px_-18px_rgba(0,0,0,0.3),0_-12px_36px_-8px_rgba(0,0,0,0.025)]">
                    <!-- Vertical line -->
                    <div class="hidden md:block absolute w-0.5 h-full bg-[<?php echo htmlspecialchars($event['color']); ?>] -left-14 top-1/2 -z-10"></div>
                    <!-- Circle -->
                    <div class="hidden md:block absolute w-20 h-20 bg-[<?php echo htmlspecialchars($event['color']); ?>]/20 rounded-full -left-24 top-1/2 -translate-y-1/2 border-2 border-[<?php echo htmlspecialchars($event['color']); ?>]"></div>
                    
                    <div class="relative flex items-center justify-center bg-[<?php echo htmlspecialchars($event['color']); ?>] rounded-t-lg md:rounded-l-lg md:rounded-tr-none p-5 md:w-[40%]">
                        <i class="<?php echo htmlspecialchars($event['icon']); ?> absolute md:top-1/2 md:-left-[71px] md:-translate-y-1/2 text-[2.5rem] text-[<?php echo htmlspecialchars($event['color']); ?>] hidden md:block"></i>
                        <div class="text-[<?php echo htmlspecialchars($event['color']); ?>] text-2xl font-semibold whitespace-nowrap brightness-[0.3]">
                            <?php echo htmlspecialchars($event['date']); ?>
                        </div>
                    </div>
                    
                    <div class="p-5 md:w-[60%]">
                        <div class="text-[1.2rem] leading-relaxed uppercase font-semibold text-[<?php echo htmlspecialchars($event['color']); ?>] tracking-widest mb-2">
                            <a href="event-page.php">
                                <?php echo htmlspecialchars($event['type']); ?>
                            </a>
                        </div>
                        <div class="text-[#525f7f]">
                            <p>
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer -->
        <?php include 'templates/footer.php'; ?>
    </div>  
    <script src="script.js"></script>
</body>
</html>