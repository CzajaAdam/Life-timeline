<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Retrieve form data
    $search = $_GET['search'] ?? '';

    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');

    // Fetch events from database
    $stmt = $db->prepare("SELECT * FROM `events` WHERE `created_by` = :id AND (`type` LIKE :search OR `description` LIKE :search) ORDER BY `date` DESC");
    $stmt->execute([
        'id' => $_SESSION['user']['id'],
        'search' => "%$search%"
    ]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return events as JSON
    header('Content-Type: application/json');
    echo json_encode($events);
    exit();
