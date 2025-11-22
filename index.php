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
                <input class="p-3 shadow-lg mb-2 rounded bg-charcoal-700 text-charcoal-50 placeholder-charcoal-300" placeholder="Title" type="text" name="event-type">
                <input class="p-3 shadow-lg mb-2 rounded bg-charcoal-700 text-charcoal-50 placeholder-charcoal-300" placeholder="Description" type="text" name="event-description">
                
                <!-- Custom Datepicker -->
                <div class="relative mb-2">
                    <input type="text" id="dateInput" placeholder="Select a date" readonly class="w-full p-3 shadow-lg rounded bg-charcoal-700 text-charcoal-50 placeholder-charcoal-300 border border-transparent cursor-pointer focus:outline-none focus:border-caleadon-600 transition">
                
                    <!-- Hidden input to store actual date value -->
                    <input type="hidden" id="dateValue" name="event-date">

                    <!-- Datepicker -->
                    <div id="datepicker" class="datepicker absolute top-full left-0 w-76 bg-charcoal-800 border border-charcoal-700 rounded-xl shadow-2xl p-5 mt-2 z-50">
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

                <label for="event-color" class="text-charcoal-300 text-pretty">Event Color:</label>
                <input value="#15A85F" class="shadow-lg appearance-none w-full h-14 bg-transparent border-none cursor-pointer" type="color" name="event-color">
                <label for="event-icon" class="text-charcoal-300 text-pretty">Event Icon:</label>
                <select class="p-3 shadow-lg mb-2 rounded bg-charcoal-700" name="event-icon">
                    <option class="bg-charcoal-700 custom-select" value="1">1</option>
                    <option class="bg-charcoal-700 custom-select" value="2">2</option>
                    <option class="bg-charcoal-700 custom-select" value="3">3</option>
                </select>
                <button class="p-3 shadow-lg rounded bg-caleadon-600 hover:bg-caleadon-700 cursor-pointer text-charcoal-50 font-semibold" type="submit">Create Event</button>
            </form>
        </div>

        <!-- Timeline Events -->
        <div class="flex flex-col w-full md:w-[50vw] mx-auto my-[5%] px-4 md:px-0">
            
            <?php foreach ($events as $event): ?>
                <!-- Timeline Event -->
                <div class="relative flex flex-col md:flex-row bg-charcoal-100 mb-5 rounded-lg shadow-[0_30px_60px_-12px_rgba(50,50,93,0.25),0_18px_36px_-18px_rgba(0,0,0,0.3),0_-12px_36px_-8px_rgba(0,0,0,0.025)]">
                    <!-- Vertical line -->
                    <div class="hidden md:block absolute w-0.5 h-full bg-[<?php echo htmlspecialchars($event['color']); ?>] -left-14 top-1/2 -z-10"></div>
                    <!-- Circle -->
                    <div class="hidden md:block absolute w-20 h-20 bg-[<?php echo htmlspecialchars($event['color']); ?>] contrast-75 brightness-50 rounded-full -left-24 top-1/2 -translate-y-1/2 border-2 border-[<?php echo htmlspecialchars($event['color']); ?>]"></div>
                    
                    <div class="relative flex items-center justify-center bg-[<?php echo htmlspecialchars($event['color']); ?>] rounded-t-lg md:rounded-l-lg md:rounded-tr-none p-5 md:w-[40%]">
                        <i class="lni lni-cake absolute md:top-1/2 md:-left-[65px] md:-translate-y-1/2 text-[2.5rem] text-[<?php echo htmlspecialchars($event['color']); ?>] hidden md:block"></i>
                        <div class="text-[<?php echo htmlspecialchars($event['color']); ?>] contrast-75 brightness-50 text-2xl font-semibold whitespace-nowrap">
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