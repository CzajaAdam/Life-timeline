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

    // Validate event ID
    $eventId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $userId = $_SESSION['user']['id'];

    if ($eventId === false || $eventId <= 0) {
        header('Location: index.php?error=Invalid+event+ID');
        exit();
    }

    try {
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch event details and verify ownership
        $eventStmt = $db->prepare("SELECT * FROM `events` WHERE id = :event_id AND created_by = :user_id");
        $eventStmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId
        ]);
        
        $event = $eventStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$event) {
            header('Location: index.php?error=Event+not+found');
            exit();
        }

        // Fetch people for this event
        $peopleStmt = $db->prepare("SELECT * FROM `event_people` WHERE event_id = :event_id ORDER BY created_at DESC");
        $peopleStmt->execute(['event_id' => $eventId]);
        $people = $peopleStmt->fetchAll(PDO::FETCH_ASSOC);

        // Return events as JSON
        header('Content-Type: application/json');
        echo json_encode($people);
        exit();

    } catch (PDOException $e) {
        error_log($e->getMessage());
        header('Location: index.php?error=Database+error');
        exit();
    }