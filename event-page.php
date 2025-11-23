<?php
    // Start session verify login
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Get event ID from URL
    $eventId = $_GET['id'] ?? null;
    
    if (!$eventId) {
        header('Location: index.php');
        exit();
    }

    $event = [
        'id' => $eventId,
        'type' => 'Graduation',
        'description' => 'Completed my Bachelor\'s degree',
        'date' => '2023-05-15',
        'color' => '#4AAF75',
        'icon' => 'fa-solid fa-graduation-cap'
    ];
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-950 relative">
    <div class="container mx-auto flex flex-col justify-between min-h-screen p-4 pb-128 md:pb-64">

        <!-- Navbar -->
        <?php include 'templates/navbar.php'; ?>

        <!-- Event Header -->
        <header class="flex justify-start items-center flex-col mb-8">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-[<?php echo htmlspecialchars($event['color']); ?>]/20 rounded-full border-2 border-[<?php echo htmlspecialchars($event['color']); ?>] flex items-center justify-center">
                    <i class="<?php echo htmlspecialchars($event['icon']); ?> text-3xl text-[<?php echo htmlspecialchars($event['color']); ?>]"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-charcoal-50"><?php echo htmlspecialchars($event['type']); ?></h1>
                    <p class="text-charcoal-400"><?php echo htmlspecialchars($event['date']); ?></p>
                </div>
            </div>
            <p class="text-charcoal-300 text-center max-w-2xl"><?php echo htmlspecialchars($event['description']); ?></p>
        </header>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-7xl mx-auto w-full">
            
            <!-- Notes Panel -->
            <div class="bg-charcoal-900 border border-charcoal-800 rounded-xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-charcoal-50 flex items-center gap-2">
                        <i class="fa-solid fa-note-sticky text-caleadon-500"></i>
                        Notes
                    </h2>
                    <button onclick="toggleNotesForm()" class="text-sm cursor-pointer bg-caleadon-600 hover:bg-caleadon-500 text-charcoal-50 py-2.5 px-6 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Add Note
                    </button>
                </div>
                
                <!-- Add Note Form -->
                <form id="notes-form" class="hidden mb-4" action="src/events/add-note.php" method="POST">
                    <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                    <textarea name="note-content" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500 resize-none" rows="4" placeholder="Write your note here..."></textarea>
                    <div class="flex gap-2 mt-2">
                        <button type="submit" class="bg-caleadon-600 hover:bg-caleadon-500 text-white px-4 py-2 rounded-lg transition text-sm">Save</button>
                        <button type="button" onclick="toggleNotesForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm">Cancel</button>
                    </div>
                </form>

                <!-- Notes List -->
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Notes -->
                    <div class="bg-charcoal-800 p-4 rounded-lg flex justify-between items-center">
                        <div class="flex flex-col space-y-2">
                            <p class="text-charcoal-200 text-sm">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Natus, nihil?</p>
                            <span class="text-xs text-charcoal-500">2 days ago</span>
                        </div>
                        <button class="text-red-400 hover:text-red-500 cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    <div class="text-center text-charcoal-500 text-sm py-8">
                        No notes yet. Click "Add Note" to create one.
                    </div>
                </div>
            </div>

            <!-- People Panel -->
            <div class="bg-charcoal-900 border border-charcoal-800 rounded-xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-charcoal-50 flex items-center gap-2">
                        <i class="fa-solid fa-users text-caleadon-500"></i>
                        People
                    </h2>
                    <button onclick="togglePeopleForm()" class="text-sm cursor-pointer bg-caleadon-600 hover:bg-caleadon-500 text-charcoal-50 py-2.5 px-6 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Add Person
                    </button>
                </div>

                <!-- Add Person Form -->
                <form id="people-form" class="hidden mb-4" action="src/events/add-person.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                    <div class="space-y-3">
                        <input type="text" name="person-name" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500" placeholder="Person's name">
                        <div>
                            <label class="block text-sm text-charcoal-400 mb-1">Photo (optional)</label>
                            <input type="file" name="person-photo" accept="image/*" class="w-full p-2 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-caleadon-600 file:text-white hover:file:bg-caleadon-500">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-caleadon-600 hover:bg-caleadon-500 text-white px-4 py-2 rounded-lg transition text-sm">Add</button>
                            <button type="button" onclick="togglePeopleForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm">Cancel</button>
                        </div>
                    </div>
                </form>

                <!-- People List -->
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Example people - will be dynamic -->
                    <div class="flex items-center gap-3 bg-charcoal-800 p-3 rounded-lg">
                        <div class="w-12 h-12 bg-caleadon-600 rounded-full flex items-center justify-center text-white font-bold">
                            JD
                        </div>
                        <span class="text-charcoal-200 flex-grow">John Doe</span>
                        <button class="text-red-400 hover:text-red-500 cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    <div class="text-center text-charcoal-500 text-sm py-8">
                        No people added yet.
                    </div>
                </div>
            </div>

            <!-- Location Panel -->
            <div class="bg-charcoal-900 border border-charcoal-800 rounded-xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-charcoal-50 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-caleadon-500"></i>
                        Location
                    </h2>
                    <button onclick="toggleLocationForm()" class="text-sm cursor-pointer bg-caleadon-600 hover:bg-caleadon-500 text-charcoal-50 py-2.5 px-6 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Add Location
                    </button>
                </div>

                <!-- Add Location Form -->
                <form id="location-form" class="hidden mb-4" action="src/events/add-location.php" method="POST">
                    <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                    <div class="space-y-3">
                        <input type="text" name="location-name" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500" placeholder="Location name">
                        <input type="text" name="location-address" class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-caleadon-500" placeholder="Address (optional)">
                        <div class="flex gap-2">
                            <button type="submit" class="bg-caleadon-600 hover:bg-caleadon-500 text-white px-4 py-2 rounded-lg transition text-sm">Save</button>
                            <button type="button" onclick="toggleLocationForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm">Cancel</button>
                        </div>
                    </div>
                </form>

                <!-- Location Display -->
                <div class="space-y-3">
                    <!-- Example location - will be dynamic -->
                    <div class="bg-charcoal-800 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-charcoal-200 font-semibold mb-1">University Campus</h3>
                                <p class="text-charcoal-400 text-sm">123 University Ave, City, State</p>
                            </div>
                            <button class="text-red-400 hover:text-red-500 cursor-pointer">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center text-charcoal-500 text-sm py-8">
                        No location added yet.
                    </div>
                </div>
            </div>

            <!-- Photos Panel -->
            <div class="bg-charcoal-900 border border-charcoal-800 rounded-xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-charcoal-50 flex items-center gap-2">
                        <i class="fa-solid fa-images text-caleadon-500"></i>
                        Photos
                    </h2>
                    <button onclick="togglePhotosForm()" class="text-sm cursor-pointer bg-caleadon-600 hover:bg-caleadon-500 text-charcoal-50 py-2.5 px-6 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Add Photos
                    </button>
                </div>

                <!-- Add Photos Form -->
                <form id="photos-form" class="hidden mb-4" action="src/events/add-photos.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="event-id" value="<?php echo htmlspecialchars($eventId); ?>">
                    <div class="space-y-3">
                        <input type="file" name="photos[]" multiple accept="image/*" class="w-full p-2 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-caleadon-600 file:text-white hover:file:bg-caleadon-500">
                        <div class="flex gap-2">
                            <button type="submit" class="bg-caleadon-600 hover:bg-caleadon-500 text-white px-4 py-2 rounded-lg transition text-sm">Upload</button>
                            <button type="button" onclick="togglePhotosForm()" class="bg-charcoal-700 hover:bg-charcoal-600 text-white px-4 py-2 rounded-lg transition text-sm">Cancel</button>
                        </div>
                    </div>
                </form>

                <!-- Photos Grid -->
                <div class="grid grid-cols-2 gap-3 max-h-96 overflow-y-auto">
                    <!-- Photos -->
                    <div class="relative group">
                        <img src="lifelines.png" alt="Event photo" class="w-full h-32 object-contain aspect-square rounded-lg">
                        <button class="absolute top-2 right-2 bg-red-400 hover:bg-red-500 aspect-square w-10 cursor-pointer text-white p-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    <div class="col-span-2 text-center text-charcoal-500 text-sm py-8">
                        No photos added yet.
                    </div>
                </div>
            </div>

        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-charcoal-800 hover:bg-charcoal-700 text-charcoal-200 px-6 py-3 rounded-lg transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Timeline
            </a>
        </div>

    </div> 

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>