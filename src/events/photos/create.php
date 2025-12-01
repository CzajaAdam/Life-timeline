<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: ../../login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['event-id'])) {
        header('Location: ../../event-page.php?error=Missing+form+data');
        exit();
    }
    
    // Validate event ID
    $eventId = filter_var($_POST['event-id'], FILTER_VALIDATE_INT);
    $userId = $_SESSION['user']['id'];
    
    if ($eventId === false || $eventId <= 0) {
        header('Location: ../../event-page.php?error=Invalid+event+ID');
        exit();
    }
    
    // Check if files were uploaded
    if (!isset($_FILES['photos']) || empty($_FILES['photos']['name'][0])) {
        header('Location: ../../event-page.php?id=' . $eventId . '&error=No+photos+selected');
        exit();
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB per file
    $maxFiles = 10; // Maximum 10 files per upload
    $uploadedPhotos = [];
    
    // Validate number of files
    $fileCount = count($_FILES['photos']['name']);
    if ($fileCount > $maxFiles) {
        header('Location: ../../event-page.php?id=' . $eventId . '&error=Too+many+files+(max+' . $maxFiles . ')');
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

        // Create upload directory if it doesn't exist
        $uploadDir = '../../uploads/photos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Process each uploaded file
        for ($i = 0; $i < $fileCount; $i++) {
            // Check for upload errors
            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) {
                continue; // Skip this file
            }
            
            $fileType = $_FILES['photos']['type'][$i];
            $fileSize = $_FILES['photos']['size'][$i];
            $fileTmpName = $_FILES['photos']['tmp_name'][$i];
            $originalName = $_FILES['photos']['name'][$i];
            
            // Validate file type
            if (!in_array($fileType, $allowedTypes)) {
                continue; // Skip invalid file types
            }
            
            // Validate file size
            if ($fileSize > $maxFileSize) {
                continue; // Skip files that are too large
            }
            
            // Generate unique filename
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $filename = uniqid('photo_', true) . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                $photoPath = 'uploads/photos/' . $filename;
                
                // Insert into database
                $stmt = $db->prepare("INSERT INTO `event_photos` (`event_id`, `created_by`, `photo_path`, `created_at`) VALUES (:event_id, :user_id, :photo_path, current_timestamp())");
                $stmt->execute([
                    'user_id' => $userId,
                    'event_id' => $eventId,
                    'photo_path' => $photoPath
                ]);
                
                $uploadedPhotos[] = $photoPath;
            }
        }

        // Check if any photos were successfully uploaded
        if (empty($uploadedPhotos)) {
            header('Location: ../../event-page.php?id=' . $eventId . '&error=No+valid+photos+uploaded');
            exit();
        }

        // Redirect with success message
        $photoCount = count($uploadedPhotos);
        header('Location: ../../event-page.php?id=' . $eventId . '&success=' . $photoCount . '+photo(s)+added+successfully');
        exit();
        
    } catch (PDOException $e) {
        // Delete uploaded files if database operation fails
        foreach ($uploadedPhotos as $photoPath) {
            if (file_exists('../../' . $photoPath)) {
                unlink('../../' . $photoPath);
            }
        }
        error_log($e->getMessage());
        header('Location: ../../event-page.php?id=' . $eventId . '&error=Database+error');
        exit();
    }