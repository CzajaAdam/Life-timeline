<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: /login.php');
        exit();
    }

    try {
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $db->beginTransaction();

        // Delete related data from other tables
        $db->prepare('DELETE FROM `event_notes` WHERE created_by = :id')->execute(['id' => $_SESSION['user']['id']]);
        $db->prepare('DELETE FROM `event_photos` WHERE created_by = :id')->execute(['id' => $_SESSION['user']['id']]);
        $db->prepare('DELETE FROM `event_people` WHERE created_by = :id')->execute(['id' => $_SESSION['user']['id']]);
        $db->prepare('DELETE FROM `event_locations` WHERE created_by = :id')->execute(['id' => $_SESSION['user']['id']]);
        $db->prepare('DELETE FROM events WHERE created_by = :id')->execute(['id' => $_SESSION['user']['id']]);

        // Finally delete the user
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user']['id']]);

        $db->commit();

        // Destroy session and redirect to login with message
        session_unset();
        session_destroy();
        header('Location: ../../../login.php?success=Account+deleted+successfully');
        exit();
        
} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    header('Location: ../../../settings.php?error=Database+error+could+not+delete+account');
    exit();
}
