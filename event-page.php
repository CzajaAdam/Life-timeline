<?php
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if event ID is provided
    if (!isset($_GET['id'])) {
        header('Location: index.php');
        exit();
    }
    $eventId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    

    // Preserve session for cURL
    session_write_close();

    // Define all endpoints
    $endpoints = [
        'events' => 'http://localhost/Lifelines/src/events/read.php',
        'notes' => "http://localhost/Lifelines/src/events/notes/read.php?id={$eventId}",
        'photos' => "http://localhost/Lifelines/src/events/photos/read.php?id={$eventId}",
        'people' => "http://localhost/Lifelines/src/events/people/read.php?id={$eventId}",
        'location' => "http://localhost/Lifelines/src/events/locations/read.php?id={$eventId}"
    ];

    // Initialize multi-handle
    $multiHandle = curl_multi_init();
    $handles = [];

    // Create individual cURL handles
    foreach ($endpoints as $key => $url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . session_id());
        
        curl_multi_add_handle($multiHandle, $curl);
        $handles[$key] = $curl;
    }

    // Execute all requests simultaneously
    $running = null;
    do {
        curl_multi_exec($multiHandle, $running);
        curl_multi_select($multiHandle);
    } while ($running > 0);

    // Collect results
    $results = [];
    foreach ($handles as $key => $curl) {
        $results[$key] = json_decode(curl_multi_getcontent($curl), true) ?? [];
        curl_multi_remove_handle($multiHandle, $curl);
    }

    curl_multi_close($multiHandle);

    // Now you have all data available
    $events = $results['events'];
    $notes = $results['notes'];
    $photos = $results['photos'];
    $people = $results['people'];
    $locations = $results['location'];

    // Find event by ID
    $filteredEvents = array_filter($events, function($event) use ($eventId) {
        return $event['id'] == $eventId;
    });

    if (!empty($filteredEvents)) {
        $event = reset($filteredEvents);
    } else {
        $event = false;
    }

    // var_dump($notes);
    // exit();

    // If event not found return to index
    if ($event == false){
        header('Location: index.php?error=Event+not+found');
        exit();
    }

    // Helper function to format time ago
    function timeAgo($timestamp) {
        // Convert timestamp string to Unix timestamp
        $time = strtotime($timestamp);
        
        // Calculate the difference in seconds between now and the timestamp
        $diff = time() - $time;
        
        // Less than 60 seconds ago
        if ($diff < 60) return 'Just now';
        
        // Less than 1 hour ago (3600 seconds)
        if ($diff < 3600) return floor($diff / 60) . ' minute' . (floor($diff / 60) != 1 ? 's' : '') . ' ago';
        
        // Less than 1 day ago (86400 seconds)
        if ($diff < 86400) return floor($diff / 3600) . ' hour' . (floor($diff / 3600) != 1 ? 's' : '') . ' ago';
        
        // Less than 1 week ago (604800 seconds)
        if ($diff < 604800) return floor($diff / 86400) . ' day' . (floor($diff / 86400) != 1 ? 's' : '') . ' ago';
        
        // Less than 1 month ago (2592000 seconds = ~30 days)
        if ($diff < 2592000) return floor($diff / 604800) . ' week' . (floor($diff / 604800) != 1 ? 's' : '') . ' ago';
        
        // Less than 1 year ago (31536000 seconds = 365 days)
        if ($diff < 31536000) return floor($diff / 2592000) . ' month' . (floor($diff / 2592000) != 1 ? 's' : '') . ' ago';
        
        // 1 year or more ago
        return floor($diff / 31536000) . ' year' . (floor($diff / 31536000) != 1 ? 's' : '') . ' ago';
    }

    // Helper function to get initials from name
    function getInitials($name) {
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-950 relative">
    <div class="container mx-auto flex flex-col min-h-screen p-4 pb-24">

        <!-- Navbar -->
        <?php include 'templates/navbar.php'; ?>

        <!-- Event Header -->
        <header class="flex justify-start items-center flex-col pt-8 pb-4 animate-fade-in" role="banner">
            <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl p-8 md:p-10 border border-charcoal-700 shadow-xl w-full max-w-4xl mb-8">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-6">
                    <div class="w-24 h-24 bg-[<?php echo htmlspecialchars($event['color']); ?>]/20 rounded-2xl border-2 border-[<?php echo htmlspecialchars($event['color']); ?>] flex items-center justify-center flex-shrink-0 shadow-lg">
                        <i class="<?php echo htmlspecialchars($event['icon']); ?> text-5xl text-[<?php echo htmlspecialchars($event['color']); ?>]"></i>
                    </div>
                    <div class="flex-grow">
                        <h1 class="text-4xl md:text-5xl font-bold text-charcoal-50 mb-2 tracking-tight"><?php echo htmlspecialchars($event['type']); ?></h1>
                        <p class="text-charcoal-400 text-lg font-semibold"><?php echo htmlspecialchars($event['date']); ?></p>
                    </div>
                </div>
                <p class="text-charcoal-300 text-base leading-relaxed"><?php echo htmlspecialchars($event['description']); ?></p>
            </div>
        </header>

        <!-- Main Content -->
        <main role="main" class="flex-grow">
            <section class="flex flex-col justify-start items-center px-4 py-8 max-w-5xl mx-auto w-full" aria-labelledby="event-details-heading">
                
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full mb-8">
                    
                    <!-- Notes Panel -->
                    <div class="bg-charcoal-900 border border-charcoal-800 rounded-2xl shadow-xl p-6 md:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl md:text-2xl font-bold text-charcoal-50 flex items-center gap-3">
                                <i class="fa-solid fa-note-sticky text-[<?php echo htmlspecialchars($event['color']); ?>] text-2xl"></i>
                                Notes
                            </h2>
                            <button onclick="toggleNotesForm()" class="text-sm cursor-pointer bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white py-2 px-4 rounded-lg transition-all duration-300 font-semibold shadow-lg hover:shadow-[<?php echo htmlspecialchars($event['color']); ?>]/50">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        </div>
                        
                        <!-- Add Note Form -->
                        <form id="notes-form" class="hidden mb-6 animate-slide-down" action="src/events/notes/create.php" method="POST">
                            <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                            <textarea name="note-content" class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-[<?php echo htmlspecialchars($event['color']); ?>] focus:border-[<?php echo htmlspecialchars($event['color']); ?>] resize-none transition-all" rows="4" placeholder="Write your note here..."></textarea>
                            <div class="flex gap-2 mt-3">
                                <button type="submit" class="bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white px-4 py-2 rounded-lg transition-all text-sm font-semibold shadow-lg">Save</button>
                                <button type="button" onclick="toggleNotesForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">Cancel</button>
                            </div>
                        </form>

                        <!-- Notes List -->
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <?php foreach ($notes as $note): ?>
                                <div class="bg-charcoal-800 p-4 rounded-xl flex justify-between items-start gap-3 hover:bg-charcoal-750 transition-colors">
                                    <div class="flex flex-col space-y-2 flex-grow">
                                        <p class="text-charcoal-200 text-sm leading-relaxed"><?php echo htmlspecialchars($note['note'])?></p>
                                        <span class="text-xs text-charcoal-500"><?php echo htmlspecialchars(timeAgo($note['created_at']))?></span>
                                    </div>
                                    <a href="http://localhost/Lifelines/src/events/notes/delete.php?id=<?php echo htmlspecialchars($note['id'])?>&event_id=<?php echo htmlspecialchars($eventId)?>" class="text-red-400 hover:text-red-500 cursor-pointer transition flex-shrink-0">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            <?php if (!isset($notes[0])): ?>
                                <div class="text-center text-charcoal-500 text-sm py-8">
                                    <i class="fa-solid fa-note-sticky text-3xl text-charcoal-700 mb-2 block"></i>
                                    No notes yet
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- People Panel -->
                    <div class="bg-charcoal-900 border border-charcoal-800 rounded-2xl shadow-xl p-6 md:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl md:text-2xl font-bold text-charcoal-50 flex items-center gap-3">
                                <i class="fa-solid fa-users text-[<?php echo htmlspecialchars($event['color']); ?>] text-2xl"></i>
                                People
                            </h2>
                            <button onclick="togglePeopleForm()" class="text-sm cursor-pointer bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white py-2 px-4 rounded-lg transition-all duration-300 font-semibold shadow-lg hover:shadow-[<?php echo htmlspecialchars($event['color']); ?>]/50">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        </div>

                        <!-- Add Person Form -->
                        <form id="people-form" class="hidden mb-6 animate-slide-down" action="src/events/people/create.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                            <div class="space-y-3">
                                <input type="text" name="person-name" class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-[<?php echo htmlspecialchars($event['color']); ?>] focus:border-[<?php echo htmlspecialchars($event['color']); ?>] transition-all" placeholder="Person's name">
                                <div>
                                    <label class="block text-sm text-charcoal-400 mb-2 font-semibold">Photo (optional)</label>
                                    <input type="file" name="person-photo" accept="image/*" class="w-full p-2 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-[<?php echo htmlspecialchars($event['color']); ?>] file:text-white hover:file:bg-[<?php echo htmlspecialchars($event['color']); ?>]/80 transition-all">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white px-4 py-2 rounded-lg transition-all text-sm font-semibold shadow-lg">Add</button>
                                    <button type="button" onclick="togglePeopleForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">Cancel</button>
                                </div>
                            </div>
                        </form>

                        <!-- People List -->
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <?php foreach ($people as $person): ?>
                                <div class="flex items-center gap-3 bg-charcoal-800 p-4 rounded-xl hover:bg-charcoal-750 transition-colors">
                                    <div class="w-12 h-12 bg-[<?php echo htmlspecialchars($event['color']); ?>] rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 shadow-lg">
                                        <?php if ($person['photo_path'] === null): ?>
                                            <?php echo htmlspecialchars(getInitials($person['person_name'])); ?>
                                        <?php else: ?>
                                            <img class="rounded-full object-cover aspect-square w-12" src="<?php echo htmlspecialchars($person['photo_path']); ?>" alt="<?php echo htmlspecialchars($person['person_name']); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-charcoal-200 flex-grow font-semibold"><?php echo htmlspecialchars($person['person_name'])?></span>
                                    <a href="src/events/people/delete.php?id=<?php echo htmlspecialchars($person['id'])?>&event_id=<?php echo htmlspecialchars($eventId)?>" class="text-red-400 hover:text-red-500 cursor-pointer transition flex-shrink-0">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            <?php if (!isset($people[0])): ?>
                                <div class="text-center text-charcoal-500 text-sm py-8">
                                    <i class="fa-solid fa-users text-3xl text-charcoal-700 mb-2 block"></i>
                                    No people added yet
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Location Panel -->
                    <div class="bg-charcoal-900 border border-charcoal-800 rounded-2xl shadow-xl p-6 md:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl md:text-2xl font-bold text-charcoal-50 flex items-center gap-3">
                                <i class="fa-solid fa-location-dot text-[<?php echo htmlspecialchars($event['color']); ?>] text-2xl"></i>
                                Location
                            </h2>
                            <button onclick="toggleLocationForm()" class="text-sm cursor-pointer bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white py-2 px-4 rounded-lg transition-all duration-300 font-semibold shadow-lg hover:shadow-[<?php echo htmlspecialchars($event['color']); ?>]/50">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        </div>

                        <!-- Add Location Form -->
                        <form id="location-form" class="hidden mb-6 animate-slide-down" action="src/events/locations/create.php" method="POST">
                            <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                            <div class="space-y-3">
                                <input type="text" name="location-name" class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-[<?php echo htmlspecialchars($event['color']); ?>] focus:border-[<?php echo htmlspecialchars($event['color']); ?>] transition-all" placeholder="Location name">
                                <input type="text" name="location-address" class="w-full p-4 rounded-xl bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-[<?php echo htmlspecialchars($event['color']); ?>] focus:border-[<?php echo htmlspecialchars($event['color']); ?>] transition-all" placeholder="Address (optional)">
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white px-4 py-2 rounded-lg transition-all text-sm font-semibold shadow-lg">Save</button>
                                    <button type="button" onclick="toggleLocationForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">Cancel</button>
                                </div>
                            </div>
                        </form>

                        <!-- Location Display -->
                        <div class="space-y-3">
                            <?php foreach ($locations as $location): ?>
                                <div class="bg-charcoal-800 p-4 rounded-xl hover:bg-charcoal-750 transition-colors">
                                    <div class="flex justify-between items-start gap-3">
                                        <div class="flex-grow">
                                            <h3 class="text-charcoal-200 font-semibold mb-1"><?php echo htmlspecialchars($location['location_name'])?></h3>
                                            <p class="text-charcoal-400 text-sm"><?php echo htmlspecialchars($location['location_address'])?></p>
                                        </div>
                                        <a href="src/events/locations/delete.php?id=<?php echo htmlspecialchars($location['id'])?>&event_id=<?php echo htmlspecialchars($eventId)?>" class="text-red-400 hover:text-red-500 cursor-pointer transition flex-shrink-0">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (!isset($locations[0])): ?>
                                <div class="text-center text-charcoal-500 text-sm py-8">
                                    <i class="fa-solid fa-location-dot text-3xl text-charcoal-700 mb-2 block"></i>
                                    No locations added yet
                                </div>
                            <?php endif;?>
                        </div>
                    </div>

                </div>

                <!-- Photos Panel -->
                <div class="bg-charcoal-900 border border-charcoal-800 rounded-2xl shadow-xl p-6 md:p-8 w-full">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-charcoal-50 flex items-center gap-3">
                            <i class="fa-solid fa-images text-[<?php echo htmlspecialchars($event['color']); ?>] text-2xl"></i>
                            Photos
                        </h2>
                        <button onclick="togglePhotosForm()" class="text-sm cursor-pointer bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white py-2 px-4 rounded-lg transition-all duration-300 font-semibold shadow-lg hover:shadow-[<?php echo htmlspecialchars($event['color']); ?>]/50">
                            <i class="fa-solid fa-plus"></i> Add
                        </button>
                    </div>

                    <!-- Add Photos Form -->
                    <form id="photos-form" class="hidden mb-6 animate-slide-down" action="src/events/photos/create.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                        <div class="space-y-3">
                            <input type="file" name="photos[]" multiple accept="image/*" class="w-full p-2 rounded-lg bg-charcoal-800 text-charcoal-50 border-2 border-charcoal-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-[<?php echo htmlspecialchars($event['color']); ?>] file:text-white hover:file:bg-[<?php echo htmlspecialchars($event['color']); ?>]/80 transition-all">
                            <div class="flex gap-2">
                                <button type="submit" class="bg-gradient-to-r from-[<?php echo htmlspecialchars($event['color']); ?>] to-[<?php echo htmlspecialchars($event['color']); ?>]/80 hover:from-[<?php echo htmlspecialchars($event['color']); ?>]/90 hover:to-[<?php echo htmlspecialchars($event['color']); ?>] text-white px-4 py-2 rounded-lg transition-all text-sm font-semibold shadow-lg">Upload</button>
                                <button type="button" onclick="togglePhotosForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">Cancel</button>
                            </div>
                        </div>
                    </form>

                    <!-- Photos Grid -->
                    <div id="gallery" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                        <?php foreach ($photos as $photo): ?>
                            <div class="relative group">

                                <!-- Get photo width and height -->
                                <?php [$width, $height] = getimagesize($photo['photo_path']); ?>

                                <!-- Clickable image for fullscreen carousel -->
                                <a class="pswp-item block overflow-hidden rounded-xl" href="<?php echo $photo['photo_path']; ?>" data-pswp-width="<?php echo $width; ?>" data-pswp-height="<?php echo $height; ?>" target="_blank">
                                    <img src="<?php echo $photo['photo_path']; ?>" alt="Event photo" class="w-full h-32 object-cover rounded-xl cursor-pointer hover:scale-110 transition-transform duration-300">
                                </a>

                                <!-- Delete button -->
                                <a href="src/events/photos/delete.php?id=<?php echo htmlspecialchars($photo['id']); ?>&eventId=<?php echo htmlspecialchars($eventId); ?>" class="flex justify-center items-center absolute top-2 right-2 bg-red-500 hover:bg-red-600 aspect-square w-10 cursor-pointer text-white p-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>

                            </div>
                        <?php endforeach; ?>

                        <?php if (!isset($photos[0])): ?>
                            <div class="col-span-2 md:col-span-3 lg:col-span-4 text-center text-charcoal-500 text-sm py-12">
                                <i class="fa-solid fa-images text-4xl text-charcoal-700 mb-3 block"></i>
                                No photos added yet
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            </section>
        </main>

        <!-- Back Button -->
        <div class="mt-12 text-center">
            <a href="index.php" class="inline-block bg-charcoal-800 hover:bg-charcoal-700 text-charcoal-200 px-8 py-3 rounded-xl transition font-semibold shadow-lg hover:shadow-charcoal-800/50">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Timeline
            </a>
        </div>

    </div> 

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css">
    <script type="module">
        import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.js';

        const lightbox = new PhotoSwipeLightbox({
            gallery: '#gallery',
            children: 'a.pswp-item',
            pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.js')
        });

        lightbox.init();
    </script>
    <script src="script.js"></script>
</body>
</html>
