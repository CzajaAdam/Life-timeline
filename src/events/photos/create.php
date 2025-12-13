<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../../../login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['event-id'])) {
        header('Location: ../../../event-page.php?error=Missing+form+data');
        exit();
    }
    
    // Validate and sanitize inputs
    $eventId = filter_var($_POST['event-id'], FILTER_VALIDATE_INT);
    $userId = $_SESSION['user']['id'];
    
    // Validate event ID
    if ($eventId === false || $eventId <= 0) {
        header('Location: ../../../event-page.php?error=Invalid+event+ID');
        exit();
    }
    
    // Check if event belongs to user
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
            header('Location: ../../../index.php?error=Event+not+found');
            exit();
        }
        
    } catch (PDOException $e) {
        // Delete uploaded file if database operation fails
        if ($photoPath && file_exists('../../../' . $photoPath)) {
            unlink('../../../' . $photoPath);
        }
        error_log($e->getMessage());
        header('Location: ../../../event-page.php?id=' . $eventId . '&error=Database+error');
        exit();
    }

    // Handle photos upload
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    if (isset($_FILES['photos'])) {
        
        if (!is_array($_FILES['photos']['type'])){
            $photos = [$_FILES['photos']];
        }else{
            $photos = [];
            foreach ($_FILES['photos']['type'] as $i => $type){
                $photos[] = [
                    'name' => $_FILES['photos']['name'][$i],
                    'error' => $_FILES['photos']['error'][$i],
                    'type' => $_FILES['photos']['type'][$i],
                    'size' => $_FILES['photos']['size'][$i],
                    'tmp_name' => $_FILES['photos']['tmp_name'][$i]
                ];
            }
        }

        foreach ($photos as $photo) {
            $photoPath = null;

            // Check file error
            if ($photo['error'] !== UPLOAD_ERR_OK){
                header('Location: ../../../event-page.php?id=' . $eventId . '&error=File+upload+error');
                exit();
            }
            
            // Validate file type
            if (!in_array($photo['type'], $allowedTypes)) {
                header('Location: ../../../event-page.php?id=' . $eventId . '&error=Invalid+file+type');
                exit();
            }
            
            // Validate file size
            if ($photo['size'] > $maxFileSize) {
                header('Location: ../../../event-page.php?id=' . $eventId . '&error=File+too+large');
                exit();
            }
            
            // Generate unique filename
            $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
            $filename = uniqid('photo_', true) . '.' . $extension;
            $uploadDir = '../../../uploads/photos/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $uploadPath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                $photoPath = 'uploads/photos/' . $filename;
            } else {
                header('Location: ../../../event-page.php?id=' . $eventId . '&error=Upload+failed');
                exit();
            }

            try {
                // Database connection
                $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Add photo to database
                $stmt = $db->prepare("INSERT INTO `event_photos` (`event_id`, `created_by`, `photo_path`, `created_at`) VALUES (:event_id, :user_id, :photo_path, current_timestamp())");
                $stmt->execute([
                    'user_id' => $userId,
                    'event_id' => $eventId,
                    'photo_path' => $photoPath
                ]);
                
            } catch (PDOException $e) {
                // Delete uploaded file if database operation fails
                if ($photoPath && file_exists('../../../' . $photoPath)) {
                    unlink('../../../' . $photoPath);
                }
                error_log($e->getMessage());
                header('Location: ../../../event-page.php?id=' . $eventId . '&error=Database+error');
                exit();
            }
        }
    }

    // Redirect back to event page after successful creation
    header('Location: ../../../event-page.php?id=' . $eventId . '&success=Photo+added+successfully');
    exit();