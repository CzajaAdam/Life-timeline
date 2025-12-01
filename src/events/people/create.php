<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../../../login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['event-id'], $_POST['person-name'])) {
        header('Location: ../../../event-page.php?error=Missing+form+data');
        exit();
    }
    
    // Validate and sanitize inputs
    $eventId = filter_var($_POST['event-id'], FILTER_VALIDATE_INT);
    $personName = trim(filter_var($_POST['person-name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $userId = $_SESSION['user']['id'];
    
    // Validate event ID
    if ($eventId === false || $eventId <= 0) {
        header('Location: ../../../event-page.php?error=Invalid+event+ID');
        exit();
    }
    
    // Validate person name
    if (strlen($personName) < 1 || strlen($personName) > 255) {
        header('Location: ../../../event-page.php?id=' . $eventId . '&error=Invalid+person+name');
        exit();
    }
    
    // Handle photo upload
    $photoPath = null;
    if (isset($_FILES['person-photo']) && $_FILES['person-photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        $fileType = $_FILES['person-photo']['type'];
        $fileSize = $_FILES['person-photo']['size'];
        
        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            header('Location: ../../../event-page.php?id=' . $eventId . '&error=Invalid+file+type');
            exit();
        }
        
        // Validate file size
        if ($fileSize > $maxFileSize) {
            header('Location: ../../../event-page.php?id=' . $eventId . '&error=File+too+large');
            exit();
        }
        
        // Generate unique filename
        $extension = pathinfo($_FILES['person-photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('person_', true) . '.' . $extension;
        $uploadDir = '../../../uploads/people/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $uploadPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['person-photo']['tmp_name'], $uploadPath)) {
            $photoPath = 'uploads/people/' . $filename;
        } else {
            header('Location: ../../../event-page.php?id=' . $eventId . '&error=Upload+failed');
            exit();
        }
    }

    try {
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verify that the event belongs to the current user
        $checkStmt = $db->prepare("SELECT id FROM `events` WHERE id = :event_id AND created_by = :user_id LIMIT 1");
        $checkStmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId
        ]);
        
        if ($checkStmt->rowCount() === 0) {
            // Delete uploaded file if event not found
            if ($photoPath && file_exists('../../../' . $photoPath)) {
                unlink('../../../' . $photoPath);
            }
            header('Location: ../../../index.php?error=Event+not+found');
            exit();
        }

        // Add person to database
        $stmt = $db->prepare("INSERT INTO `event_people` (`event_id`, `created_by`, `person_name`, `photo_path`, `created_at`) VALUES (:event_id, :user_id, :person_name, :photo_path, current_timestamp())");
        $stmt->execute([
            'user_id' => $userId,
            'event_id' => $eventId,
            'person_name' => $personName,
            'photo_path' => $photoPath
        ]);

        // Redirect back to event page after successful creation
        header('Location: ../../../event-page.php?id=' . $eventId . '&success=Person+added+successfully');
        exit();
        
    } catch (PDOException $e) {
        // Delete uploaded file if database operation fails
        if ($photoPath && file_exists('../../../' . $photoPath)) {
            unlink('../../../' . $photoPath);
        }
        error_log($e->getMessage());
        header('Location: ../../../event-page.php?id=' . $eventId . '&error=Database+error');
        exit();
    }