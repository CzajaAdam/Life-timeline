<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../../login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['event-id'], $_POST['location-name'])) {
        header('Location: ../../event-page.php?error=Missing+form+data');
        exit();
    }
    
    // Validate and sanitize inputs
    $eventId = filter_var($_POST['event-id'], FILTER_VALIDATE_INT);
    $locationName = trim(filter_var($_POST['location-name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $locationAddress = isset($_POST['location-address']) ? trim(filter_var($_POST['location-address'], FILTER_SANITIZE_SPECIAL_CHARS)) : null;
    $userId = $_SESSION['user']['id'];
    
    // Validate event ID
    if ($eventId === false || $eventId <= 0) {
        header('Location: ../../event-page.php?error=Invalid+event+ID');
        exit();
    }
    
    // Validate location name
    if (strlen($locationName) < 1 || strlen($locationName) > 255) {
        header('Location: ../../event-page.php?id=' . $eventId . '&error=Invalid+location+name');
        exit();
    }
    
    // Validate address length if provided
    if ($locationAddress !== null && strlen($locationAddress) > 500) {
        header('Location: ../../event-page.php?id=' . $eventId . '&error=Address+too+long');
        exit();
    }

    try {
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verify that the event belongs to the current user
        $checkStmt = $db->prepare("SELECT id FROM `events` WHERE id = :event_id AND created_by = :user_id");
        $checkStmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId
        ]);
        
        if ($checkStmt->rowCount() === 0) {
            header('Location: ../../index.php?error=Event+not+found');
            exit();
        }

        // Add location to database
        $stmt = $db->prepare("INSERT INTO `event_locations` (`event_id`, `created_by`, `location_name`, `location_address`, `created_at`) VALUES (:event_id, :user_id, :location_name, :location_address, current_timestamp())");
        $stmt->execute([
            'user_id' => $userId,
            'event_id' => $eventId,
            'location_name' => $locationName,
            'location_address' => $locationAddress
        ]);

        // Redirect back to event page after successful creation
        header('Location: ../../event-page.php?id=' . $eventId . '&success=Location+added+successfully');
        exit();
        
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header('Location: ../../event-page.php?id=' . $eventId . '&error=Database+error');
        exit();
    }