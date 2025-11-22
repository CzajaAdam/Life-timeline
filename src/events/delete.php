<?php
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../login.php');
        exit();
    }

    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');

    // Check if event ID is provided
    if (!isset($_GET['id'])) {
        header('Location: index.php');
        exit();
    }

    // Delete the event from the database
    $eventId = $_GET['id'];
    $stmt = $db->prepare("DELETE FROM `events` WHERE `id` = :id AND `created_by` = :user_id");
    $stmt->execute([
        'id' => $eventId,
        'user_id' => $_SESSION['user']['id']
    ]);

    // Redirect to dashboard after successful deletion
    header('Location: ../../index.php?success=Event+deleted+successfully');
    exit();
    
?>