<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['event-date'], $_POST['event-type'], $_POST['event-description'], $_POST['event-color'], $_POST['event-icon'])) {
        header('Location: /index.php?error=Missing+form+data');
        exit();
    }
    
    // Validate and sanitize inputs
    $eventDate = filter_var($_POST['event-date'], FILTER_DEFAULT);
    $eventType = filter_var($_POST['event-type'], FILTER_DEFAULT);
    $eventDescription = filter_var($_POST['event-description'], FILTER_DEFAULT);
    $eventColor = filter_var($_POST['event-color'], FILTER_DEFAULT);
    $eventIcon = filter_var($_POST['event-icon'], FILTER_DEFAULT);
    
    // Validate Date
    $dateTime = DateTime::createFromFormat('Y-m-d', $eventDate);
    if (!$dateTime || $dateTime->format('Y-m-d') !== $eventDate) {
        header('Location: /index.php?error=Invalid+date+format');
        exit();
    }

    // Validate Color
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $eventColor)) {
        header('Location: /index.php?error=Invalid+color+format');
        exit();
    }
    
    // Validate Event Type length
    if (strlen($_POST['event-type']) < 2) {
        header('Location: /index.php?error=Missing+form+data');
        exit();
    }

    // Retrieve form data
    $eventDate = $_POST['event-date'] ?? '';
    $eventType = $_POST['event-type'] ?? '';
    $eventDescription = $_POST['event-description'] ?? '';
    $eventColor = $_POST['event-color'] ?? '';
    $eventIcon = $_POST['event-icon'] ?? '';

    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');

    // Add event to database
    $stmt = $db->prepare("INSERT INTO `events` (`created_by`, `date`, `type`, `description`, `color`, `icon`) VALUES (:id, :date, :type, :description, :color, :icon)");
    $stmt->execute([
        'id' => $_SESSION['user']['id'],
        'date' => $eventDate,
        'type' => $eventType,
        'description' => $eventDescription,
        'color' => $eventColor,
        'icon' => $eventIcon
    ]);

    // Redirect to dashboard after successful creation
    header('Location: ../../index.php?success=Event+created+successfully');
    exit();