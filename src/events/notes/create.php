<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../../../login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['event-id'], $_POST['note-content'])) {
        header('Location: ../../../event-page.php?error=Missing+form+data');
        exit();
    }
    
    // Validate and sanitize inputs
    $eventId = filter_var($_POST['event-id'], FILTER_VALIDATE_INT);
    $noteContent = trim(filter_var($_POST['note-content'], FILTER_DEFAULT));
    $userId = filter_var($_SESSION['user']['id'], FILTER_DEFAULT);
    
    // Validate event ID
    if ($eventId === false || $eventId <= 0) {
        header('Location: ../../../event-page.php?error=Invalid+event+ID');
        exit();
    }
    
    // Validate note content length
    if (strlen($noteContent) < 1 || strlen($noteContent) > 1024) {
        header('Location: ../../../event-page.php?id=' . $eventId . '&error=Invalid Note Length');
        exit();
    }

    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');

    // Verify that the event belongs to the current user
    $checkStmt = $db->prepare("SELECT id FROM `events` WHERE id = :event_id AND created_by = :user_id");
    $checkStmt->execute([
        'event_id' => $eventId,
        'user_id' => $userId
    ]);
    
    if ($checkStmt->rowCount() === 0) {
        header('Location: ../../../index.php?error=Event+not+found');
        exit();
    }

    // Add note to database
    $stmt = $db->prepare("INSERT INTO `event_notes` (`event_id`, `created_by`, `note`, `created_at`) VALUES (:event_id, :user_id, :content, current_timestamp())");
    $stmt->execute([
        'user_id' => $userId,
        'event_id' => $eventId,
        'content' => $noteContent
    ]);

    // Redirect back to event page after successful creation
    header('Location: ../../../event-page.php?id=' . $eventId . '&success=Note+added+successfully');
    exit();