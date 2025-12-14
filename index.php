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

    // Get icons json
    $icons = file_get_contents('icons.json');
    $icons = json_decode($icons, true) ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-950 relative">
    <div class="container mx-auto flex flex-col min-h-screen p-4 pb-24">

        <!-- Navbar -->
        <?php include 'templates/navbar.php'; ?>

        <!-- Header -->
        <header class="flex justify-start items-center flex-col" role="banner">
            <img class="h-16" src="lifelines.png" alt="LifeLines logo">
            <h1 class="text-3xl text-center font-bold text-charcoal-50 mb-2 py-2">LifeLines</h1>
            <hr class="w-1/3 md:w-1/2 border-charcoal-800 mb-4" role="presentation">
        </header>

        <!-- Welcome Section -->
        <main role="main">
            <section class="flex flex-col justify-start grow pt-2 items-center px-4" aria-labelledby="welcome-heading">
                <h2 id="welcome-heading" class="text-charcoal-300 text-2xl mb-4">
                    Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!
                </h2>
                <p class="text-charcoal-400 text-center max-w-md text-lg text-pretty mb-4">
                    This is your dashboard. From here, you can manage your LifeLines and access all the features available to you.
                </p>
                
                <!-- Create Event Button -->
                <button onclick="toggleCreateEventForm()" id="create-event-btn" class="mb-8 p-3 shadow-lg rounded-lg bg-caleadon-600 hover:bg-caleadon-500 cursor-pointer text-white font-bold transition" type="button" aria-expanded="false" aria-controls="create-event-form">
                    Create New Event
                </button>

                <!-- Create Event Form -->
                <form class="hidden flex-col p-8 rounded-xl text-charcoal-50 bg-charcoal-900 border border-charcoal-800 shadow-2xl w-full max-w-md space-y-4" id="create-event-form" action="src/events/create.php" method="POST" aria-labelledby="create-event-heading">
                    
                    <h3 id="create-event-heading" class="sr-only">
                        Create New Event Form
                    </h3>

                    <!-- Event Type -->
                    <div>
                        <label for="event-type" class="block text-charcoal-300 text-sm mb-1 font-medium">
                            Event Title
                        </label>
                        <input id="event-type" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-transparent transition" placeholder="Enter title" type="text" name="event-type" required aria-required="true">
                    </div>
                    
                    <!-- Event Description -->
                    <div>
                        <label for="event-description" class="block text-charcoal-300 text-sm mb-1 font-medium">
                            Event Description
                        </label>
                        <input id="event-description" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-transparent transition" placeholder="Enter description" type="text" name="event-description" required aria-required="true">
                    </div>
                    
                    <!-- Event Datepicker -->
                    <div class="relative flexi-datepicker">
                        <label for="dateInput" class="block text-charcoal-300 text-sm mb-1 font-medium">
                            Event Date
                        </label>

                        <input name="event-date" type="text" id="dateInput" placeholder="Select a date" readonly class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 cursor-pointer focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-transparent transition" required aria-required="true" aria-describedby="date-hint">
                        <span id="date-hint" class="sr-only">
                            Use the date picker to select an event date
                        </span>

                        <!-- Hidden input for actual value -->
                        <input type="hidden" id="dateValue">
                    </div>

                    <!-- Event Icon -->
                    <div class="relative">
                        <label for="event-icon" class="block text-charcoal-300 text-sm mb-1 font-medium">
                            Event Icon
                        </label>
                        <input type="hidden" id="event-icon" name="event-icon" value="fa-solid fa-graduation-cap">
                        
                        <!-- Dropdown Button -->
                        <button type="button" id="icon-dropdown-btn" class="w-full p-3 cursor-pointer rounded-lg bg-charcoal-800 border border-charcoal-700 flex items-center justify-between hover:bg-charcoal-750 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-transparent transition" aria-expanded="false" aria-controls="icon-grid" aria-haspopup="true" aria-label="Select event icon">
                            <span class="flex items-center gap-3">
                                <span id="selected-icon-name" class="text-charcoal-500">
                                    Select Icon
                                </span>
                            </span>
                            <svg class="w-4 h-4 text-charcoal-400 transition-transform" id="dropdown-arrow" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Grid -->
                        <div id="icon-grid" class="hidden absolute left-1/2 -translate-x-1/2 w-96 z-10 mt-2 bg-charcoal-800 border border-charcoal-700 rounded-lg shadow-lg p-3" role="listbox" aria-label="Icon options">
                            <div class="grid grid-cols-6 gap-2">
                                <?php foreach ($icons as $key => $icon): ?>
                                    <button type="button" class="cursor-pointer icon-option w-12 h-12 bg-charcoal-700 hover:bg-charcoal-600 rounded transition-colors flex items-center justify-center text-2xl text-charcoal-300 <?php echo (($key == 0) ? 'selected ring-2 ring-caleadon-500' : '')?>" data-value="<?php echo htmlspecialchars($icon['value'])?>" data-unicode="<?php echo htmlspecialchars($icon['unicode'])?>" role="option" aria-selected="<?php echo (($key == 0) ? 'true' : 'false')?>" aria-label="Icon <?php echo $key + 1?>">
                                        <?php echo $icon['unicode']?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Event Color -->
                    <div class="flex flex-col">
                        <label for="event-color" class="block text-charcoal-300 text-sm mb-1 font-medium">
                            Event Color
                        </label>
                        <input id="event-color" value="#4AAF75" class="appearance-none w-full h-14 bg-transparent cursor-pointer rounded-lg" type="color" name="event-color" aria-label="Choose event color">
                    </div>

                    <button class="p-3 rounded-lg bg-caleadon-600 hover:bg-caleadon-500 cursor-pointer text-white font-bold shadow-lg hover:shadow-caleadon-600/20 transition" type="submit">
                        Create Event
                    </button>
                </form>
            </section>

            <!-- Border line -->
            <div class="flex justify-center w-full">
                <hr class="w-1/3 md:w-1/2 border-charcoal-800 my-16" role="presentation">
            </div>

            <!-- Timeline Events -->
            <section class="flex flex-col w-full md:w-[50vw] mx-auto px-4 md:px-0" aria-labelledby="timeline-heading">
                
                <h2 id="timeline-heading" class="text-charcoal-100 text-2xl mb-8 text-4xl text-center font-bold">
                    Your <span class="text-caleadon-500">LifeLine!</span>
                </h2>

                <?php if (empty($events)): ?>
                    <p class="text-charcoal-400 text-center text-lg">
                        No events yet. Create your first event to get started!
                    </p>
                <?php else: ?>
                    <ol class="list-none" aria-label="Timeline of life events">
                        <?php foreach ($events as $key => $event): ?>

                            <!-- Timeline Event -->
                            <li class="relative mb-5">
                                <!-- Circle -->
                                <div class="hidden md:flex md:items-center md:justify-center absolute w-20 h-20 bg-[<?php echo htmlspecialchars($event['color']); ?>]/20 rounded-full left-0 top-1/2 -translate-y-1/2 border-2 border-[<?php echo htmlspecialchars($event['color']); ?>] z-10" aria-hidden="true">
                                    <i class="<?php echo htmlspecialchars($event['icon']); ?> text-[2.5rem] text-[<?php echo htmlspecialchars($event['color']); ?>] hidden md:inline-flex" aria-hidden="true"></i>
                                </div>
                                
                                <!-- Vertical line -->
                                <?php if ($key != count($events) - 1): ?>
                                    <div class="hidden md:block absolute w-0.5 h-3/7 bg-[<?php echo htmlspecialchars($event['color']); ?>] top-[87.5%] left-[2.437rem] -z-10" aria-hidden="true"></div>
                                <?php endif; ?>

                                <!-- Clickable card -->
                                <a href="event-page.php?id=<?php echo urlencode($event['id']); ?>" class="block md:ml-24" aria-label="View details for <?php echo htmlspecialchars($event['type']); ?> event on <?php echo htmlspecialchars($event['date']); ?>">
                                    <article class="group relative flex flex-col md:flex-row bg-charcoal-100 rounded-lg hover:scale-[1.02] transition-transform duration-200" style="box-shadow: 0 15px 30px -6px <?php echo htmlspecialchars($event['color']); ?>40, 0 9px 18px -9px <?php echo htmlspecialchars($event['color']); ?>4D, 0 -6px 18px -4px <?php echo htmlspecialchars($event['color']); ?>0A;">
                                        <div class="relative flex items-center justify-center bg-[<?php echo htmlspecialchars($event['color']); ?>] rounded-t-lg md:rounded-l-lg md:rounded-tr-none p-5 md:w-[40%]">
                                            <time datetime="<?php echo htmlspecialchars($event['date']); ?>" class="text-[<?php echo htmlspecialchars($event['color']); ?>] text-2xl font-semibold whitespace-nowrap brightness-[0.3]">
                                                <?php echo htmlspecialchars($event['date']); ?>
                                            </time>
                                        </div>
                                        
                                        <div class="p-5 md:w-[60%] flex justify-between items-center gap-4 rounded-br-lg bg-[<?php echo htmlspecialchars($event['color'])?>]/15">
                                            <div class="flex flex-col">
                                                <h3 class="text-[1.25rem] font-extrabold leading-relaxed uppercase font-semibold text-[<?php echo htmlspecialchars($event['color']); ?>] tracking-wide mb-2">
                                                    <?php echo htmlspecialchars($event['type']); ?>
                                                </h3>
                                                <p class="text-[#525f7f]">
                                                    <?php echo htmlspecialchars($event['description']); ?>
                                                </p>
                                            </div>
                                            <button onclick="event.preventDefault(); event.stopPropagation(); if(confirm('Are you sure you want to delete this event?')) window.location.href='src/events/delete.php?id=<?php echo urlencode($event['id']); ?>';" class="ml-auto" type="button" aria-label="Delete <?php echo htmlspecialchars($event['type']); ?> event">
                                                <i class="fa-solid fa-trash-can cursor-pointer text-red-500 text-3xl hover:text-red-600 transition" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </article>
                                </a>
                            </li>

                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </section>
        </main>

    </div>

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>