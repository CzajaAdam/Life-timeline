<?php
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../../login.php');
        exit();
    }

    // Check if photo ID is provided
    if (!isset($_GET['id'])) {
        header('Location: ../../../index.php');
        exit();
    }
    
    // Filter data
    $photoId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $eventId = filter_var($_GET['eventId'], FILTER_VALIDATE_INT);

    try {
        
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get photo path
        $stmt = $db->prepare("SELECT * FROM `event_photos` WHERE `id` = :id AND `created_by` = :user_id LIMIT 1");
        $stmt->execute([
            'id' => $photoId,
            'user_id' => $_SESSION['user']['id']
        ]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Delete uploaded file
        $photoPath = $photo['photo_path'];
        if ($photoPath && file_exists('../../../' . $photoPath)) {
            unlink('../../../' . $photoPath);
        }

        // Delete the event from the database
        $stmt = $db->prepare("DELETE FROM `event_photos` WHERE `id` = :id AND `created_by` = :user_id LIMIT 1");
        $stmt->execute([
            'id' => $photoId,
            'user_id' => $_SESSION['user']['id']
        ]);

        // Redirect to dashboard after successful deletion
        header('Location: ../../../event-page.php?id=' . $eventId . '&success=Photo+deleted+successfully');
        exit();

    } catch (PDOException $e) {
        // Error Handling
        error_log($e->getMessage());
        header('Location: ../../../event-page.php?id=' . $eventId . '&error=Database+error');
        exit();
    }
?>