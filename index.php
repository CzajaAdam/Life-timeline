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

        <!-- Header with improved spacing and animation -->
        <header class="flex justify-start items-center flex-col pt-8 pb-4 animate-fade-in" role="banner">
            <img class="h-20 mb-4 transition-transform duration-300 hover:scale-110" src="lifelines.png" alt="LifeLines logo">
            <h1 class="text-4xl md:text-5xl text-center font-bold text-charcoal-50 mb-3 tracking-tight">LifeLines</h1>
            <hr class="w-24 border-2 border-caleadon-500 mb-2 rounded-full" role="presentation">
            <p class="text-charcoal-400 text-center text-sm tracking-wider uppercase">Your Life's Journey</p>
        </header>

        <!-- Welcome Section with improved layout -->
        <main role="main" class="flex-grow">
            <section class="flex flex-col justify-start items-center px-4 py-8 max-w-4xl mx-auto" aria-labelledby="welcome-heading">
                
                <!-- Welcome card -->
                <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 mb-8 w-full border border-charcoal-700 shadow-xl">
                    <h2 id="welcome-heading" class="text-charcoal-100 text-3xl md:text-4xl mb-3 font-bold">
                        Welcome back, <span class="text-caleadon-500"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>!
                    </h2>
                    <p class="text-charcoal-400 text-lg leading-relaxed">
                        Capture and chronicle the moments that define your journey. Create new milestones and watch your story unfold.
                    </p>
                </div>

                <!-- Stats Overview -->
                <?php if (!empty($events)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full mb-8">
                    <div class="bg-charcoal-900 rounded-xl p-6 border border-charcoal-800 text-center">
                        <div class="text-4xl font-bold text-caleadon-500 mb-2"><?php echo count($events); ?></div>
                        <div class="text-charcoal-400 text-sm uppercase tracking-wide">Total Events</div>
                    </div>
                    <div class="bg-charcoal-900 rounded-xl p-6 border border-charcoal-800 text-center">
                        <div class="text-4xl font-bold text-caleadon-500 mb-2">
                            <?php 
                            $dates = array_map(fn($e) => strtotime($e['date']), $events);
                            echo date('Y', min($dates)); 
                            ?>
                        </div>
                        <div class="text-charcoal-400 text-sm uppercase tracking-wide">First Event</div>
                    </div>
                    <div class="bg-charcoal-900 rounded-xl p-6 border border-charcoal-800 text-center">
                        <div class="text-4xl font-bold text-caleadon-500 mb-2">
                            <?php echo date('Y', max($dates)); ?>
                        </div>
                        <div class="text-charcoal-400 text-sm uppercase tracking-wide">Latest Event</div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Create Event Button with improved styling -->
                <button onclick="toggleCreateEventForm()" id="create-event-btn" class="group mb-8 px-8 py-4 rounded-xl bg-gradient-to-r from-caleadon-600 to-caleadon-500 hover:from-caleadon-500 hover:to-caleadon-400 cursor-pointer text-white font-bold transition-all duration-300 shadow-lg hover:shadow-caleadon-500/50 hover:scale-105 flex items-center gap-3" type="button" aria-expanded="false" aria-controls="create-event-form">
                    <i class="fa-solid fa-plus text-lg transition-transform group-hover:rotate-90 duration-300" aria-hidden="true"></i>
                    <span class="text-lg">Create New Event</span>
                </button>

                <!-- Create Event Form with improved design -->
                <form class="hidden flex-col p-8 md:p-10 rounded-2xl text-charcoal-50 bg-gradient-to-br from-charcoal-900 to-charcoal-800 border border-charcoal-700 shadow-2xl w-full max-w-2xl space-y-6 animate-slide-down" id="create-event-form" action="src/events/create.php" method="POST" aria-labelledby="create-event-heading">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="create-event-heading" class="text-2xl font-bold text-charcoal-50">
                            <i class="fa-solid fa-calendar-plus text-caleadon-500 mr-2" aria-hidden="true"></i>
                            New Life Event
                        </h3>
                        <button type="button" onclick="toggleCreateEventForm()" class="text-charcoal-400 hover:text-charcoal-200 transition">
                            <i class="fa-solid fa-times text-2xl" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Event Type -->
                    <div class="form-group">
                        <label for="event-type" class="block text-charcoal-200 text-base mb-2 font-semibold">
                            <i class="fa-solid fa-heading text-caleadon-500 mr-2" aria-hidden="true"></i>
                            Event Title
                        </label>
                        <input id="event-type" class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all" placeholder="e.g., Graduation, New Job, First Home" type="text" name="event-type" required aria-required="true">
                    </div>
                    
                    <!-- Event Description -->
                    <div class="form-group">
                        <label for="event-description" class="block text-charcoal-200 text-base mb-2 font-semibold">
                            <i class="fa-solid fa-align-left text-caleadon-500 mr-2" aria-hidden="true"></i>
                            Event Description
                        </label>
                        <textarea id="event-description" rows="3" class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all resize-none" placeholder="Describe this memorable moment..." name="event-description" required aria-required="true"></textarea>
                    </div>
                    
                    <!-- Event Datepicker -->
                    <div class="relative flexi-datepicker form-group">
                        <label for="dateInput" class="block text-charcoal-200 text-base mb-2 font-semibold">
                            <i class="fa-solid fa-calendar text-caleadon-500 mr-2" aria-hidden="true"></i>
                            Event Date
                        </label>

                        <input name="event-date" type="text" id="dateInput" placeholder="Select a date" readonly class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 cursor-pointer focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all" required aria-required="true" aria-describedby="date-hint">
                        <span id="date-hint" class="sr-only">
                            Use the date picker to select an event date
                        </span>

                        <!-- Hidden input for actual value -->
                        <input type="hidden" id="dateValue">
                    </div>

                    <!-- Two column layout for icon and color -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Event Icon -->
                        <div class="relative form-group">
                            <label for="event-icon" class="block text-charcoal-200 text-base mb-2 font-semibold">
                                <i class="fa-solid fa-icons text-caleadon-500 mr-2" aria-hidden="true"></i>
                                Event Icon
                            </label>
                            <input type="hidden" id="event-icon" name="event-icon" value="fa-solid fa-graduation-cap">
                            
                            <!-- Dropdown Button -->
                            <button type="button" id="icon-dropdown-btn" class="w-full p-4 cursor-pointer rounded-xl bg-charcoal-800 border-2 border-charcoal-700 flex items-center justify-between hover:border-charcoal-600 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-caleadon-500 transition-all" aria-expanded="false" aria-controls="icon-grid" aria-haspopup="true" aria-label="Select event icon">
                                <span class="flex items-center gap-3">
                                    <span id="selected-icon-name" class="text-charcoal-500">
                                        Select Icon
                                    </span>
                                </span>
                                <svg class="w-5 h-5 text-charcoal-400 transition-transform" id="dropdown-arrow" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Grid -->
                            <div id="icon-grid" class="hidden absolute left-1/2 -translate-x-1/2 w-75 md:w-500 max-w-md z-10 mt-2 bg-charcoal-800 border-2 border-charcoal-700 rounded-xl shadow-2xl p-4" role="listbox" aria-label="Icon options">
                                <div class="grid grid-cols-5 md:grid-cols-6 gap-2">
                                    <?php foreach ($icons as $key => $icon): ?>
                                        <button type="button" class="cursor-pointer mx-auto icon-option w-12 h-12 md:w-14 md:h-14 bg-charcoal-700 hover:bg-charcoal-600 rounded-lg transition-all duration-200 flex items-center justify-center text-2xl text-charcoal-300 hover:scale-110 <?php echo (($key == 0) ? 'selected ring-2 ring-caleadon-500' : '')?>" data-value="<?php echo htmlspecialchars($icon['value'])?>" data-unicode="<?php echo htmlspecialchars($icon['unicode'])?>" role="option" aria-selected="<?php echo (($key == 0) ? 'true' : 'false')?>" aria-label="Icon <?php echo $key + 1?>">
                                            <?php echo $icon['unicode']?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Event Color -->
                        <div class="flex flex-col form-group">
                            <label for="event-color" class="block text-charcoal-200 text-base mb-2 font-semibold">
                                <i class="fa-solid fa-palette text-caleadon-500 mr-2" aria-hidden="true"></i>
                                Event Color
                            </label>
                            <input id="event-color" value="#4AAF75" class="appearance-none w-full h-14 bg-transparent cursor-pointer rounded-xl border-2 border-charcoal-700 hover:border-charcoal-600 transition-all" type="color" name="event-color" aria-label="Choose event color">
                        </div>
                    </div>

                    <button class="w-full p-4 rounded-xl bg-gradient-to-r from-caleadon-600 to-caleadon-500 hover:from-caleadon-500 hover:to-caleadon-400 cursor-pointer text-white font-bold shadow-lg hover:shadow-caleadon-500/50 transition-all duration-300 hover:scale-[1.02] text-lg mt-4" type="submit">
                        <i class="fa-solid fa-check mr-2" aria-hidden="true"></i>
                        Create Event
                    </button>
                </form>
            </section>

            <!-- Divider with icon -->
            <div class="flex justify-center items-center w-full my-16">
                <hr class="flex-grow border-charcoal-800" role="presentation">
                <div class="mx-4 text-charcoal-700">
                    <i class="fa-solid fa-timeline text-3xl" aria-hidden="true"></i>
                </div>
                <hr class="flex-grow border-charcoal-800" role="presentation">
            </div>

            <!-- Timeline Events with improved layout -->
            <section class="flex flex-col w-full max-w-5xl mx-auto px-4 md:px-8 pb-16" aria-labelledby="timeline-heading">
                
                <div class="text-center mb-12">
                    <h2 id="timeline-heading" class="text-charcoal-100 text-3xl md:text-5xl font-bold mb-4">
                        Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-caleadon-500 to-caleadon-400">LifeLine</span>
                    </h2>
                    <p class="text-charcoal-400 text-lg">A visual journey through your most important moments</p>
                </div>

                <?php if (empty($events)): ?>
                    <div class="bg-charcoal-900 border-2 border-dashed border-charcoal-700 rounded-2xl p-16 text-center">
                        <i class="fa-solid fa-timeline text-6xl text-charcoal-700 mb-6" aria-hidden="true"></i>
                        <p class="text-charcoal-400 text-xl mb-2">Your timeline is waiting to be written</p>
                        <p class="text-charcoal-500">Create your first event to begin your journey</p>
                    </div>
                <?php else: ?>
                    <ol class="list-none relative" aria-label="Timeline of life events">
                        <?php foreach ($events as $key => $event): ?>

                            <!-- Timeline Event -->
                            <li class="relative mb-8 md:mb-12 timeline-item" style="animation-delay: <?php echo $key * 100; ?>ms">
                                <!-- Circle with pulse effect -->
                                <div class="hidden md:flex md:items-center md:justify-center absolute w-24 h-24 bg-[<?php echo htmlspecialchars($event['color']); ?>]/20 rounded-full left-0 top-1/2 -translate-y-1/2 border-4 border-[<?php echo htmlspecialchars($event['color']); ?>] z-10 shadow-lg hover:scale-110 transition-transform duration-300" aria-hidden="true">
                                    <i class="<?php echo htmlspecialchars($event['icon']); ?> text-[2.75rem] text-[<?php echo htmlspecialchars($event['color']); ?>]" aria-hidden="true"></i>
                                </div>
                                
                                <!-- Vertical line with gradient -->
                                <?php if ($key != count($events) - 1): ?>
                                    <div class="hidden md:block absolute w-1 h-[calc(100%+2rem)] top-[87.5%] left-[2.875rem] -z-10 rounded-full" style="background: linear-gradient(to bottom, <?php echo htmlspecialchars($event['color']); ?>, <?php echo htmlspecialchars($events[$key + 1]['color']); ?>);" aria-hidden="true"></div>
                                <?php endif; ?>

                                <!-- Clickable card with enhanced design -->
                                <a href="event-page.php?id=<?php echo urlencode($event['id']); ?>" class="block md:ml-32 group" aria-label="View details for <?php echo htmlspecialchars($event['type']); ?> event on <?php echo htmlspecialchars($event['date']); ?>">
                                    <article class="relative flex flex-col md:flex-row bg-charcoal-900 rounded-2xl hover:scale-[1.02] transition-all duration-300 overflow-hidden border border-charcoal-800 hover:border-[<?php echo htmlspecialchars($event['color']); ?>]/50" style="box-shadow: 0 20px 40px -12px <?php echo htmlspecialchars($event['color']); ?>30;">
                                        
                                        <!-- Mobile icon -->
                                        <div class="md:hidden absolute top-4 left-4 w-12 h-12 bg-[<?php echo htmlspecialchars($event['color']); ?>]/20 rounded-full flex items-center justify-center border-2 border-[<?php echo htmlspecialchars($event['color']); ?>]">
                                            <i class="<?php echo htmlspecialchars($event['icon']); ?> text-xl text-[<?php echo htmlspecialchars($event['color']); ?>]" aria-hidden="true"></i>
                                        </div>

                                        <!-- Date section -->
                                        <div class="relative flex items-center justify-center bg-gradient-to-br from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 p-8 md:w-[35%] md:min-h-[160px]">
                                            <div class="text-center">
                                                <time datetime="<?php echo htmlspecialchars($event['date']); ?>" class="text-white text-3xl md:text-4xl font-bold drop-shadow-lg">
                                                    <?php $date = new DateTime($event['date']); echo $date->format('M d');?>
                                                </time>
                                                <div class="text-white/80 text-xl font-semibold mt-1">
                                                    <?php echo $date->format('Y'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Content section -->
                                        <div class="p-6 md:p-8 md:w-[65%] flex justify-between items-center gap-4 bg-charcoal-800 group-hover:bg-charcoal-750 transition-colors">
                                            <div class="flex flex-col flex-grow">
                                                <h3 class="text-xl md:text-2xl font-bold leading-relaxed uppercase text-[<?php echo htmlspecialchars($event['color']); ?>] tracking-wide mb-3 group-hover:text-[<?php echo htmlspecialchars($event['color']); ?>] transition-colors">
                                                    <?php echo htmlspecialchars($event['type']); ?>
                                                </h3>
                                                <p class="text-charcoal-300 text-base leading-relaxed">
                                                    <?php echo htmlspecialchars($event['description']); ?>
                                                </p>
                                            </div>
                                            <button onclick="event.preventDefault(); event.stopPropagation(); if(confirm('Are you sure you want to delete this event?')) window.location.href='src/events/delete.php?id=<?php echo urlencode($event['id']); ?>';" class="ml-auto shrink-0 w-12 h-12 rounded-xl bg-charcoal-700 hover:bg-red-500/20 flex items-center justify-center transition-all duration-300 group/delete" type="button" aria-label="Delete <?php echo htmlspecialchars($event['type']); ?> event">
                                                <i class="fa-solid fa-trash-can text-red-500 text-xl group-hover/delete:scale-110 transition-transform" aria-hidden="true"></i>
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