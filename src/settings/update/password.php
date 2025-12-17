<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['current-password'], $_POST['new-password'], $_POST['confirm-new-password'])) {
        header('Location: /index.php?error=Missing+form+data');
        exit();
    }

    // Validate and sanitize inputs
    $currentPassword = filter_var($_POST['current-password'], FILTER_DEFAULT);
    $newPassword = filter_var($_POST['new-password'], FILTER_DEFAULT);
    $confirmNewPassword = filter_var($_POST['confirm-new-password'], FILTER_DEFAULT);

    // Check if passwords match
    if ($newPassword !== $confirmNewPassword) {
        header("Location: ../../../settings.php?error=Passwords do not match");
        exit();
    }

    try {
        
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
        
        // Check if provided current password is correct
        $stmt = $db->prepare("SELECT `password` FROM `users` WHERE `id` = :id LIMIT 1");
        $stmt->execute([
            'id' => $_SESSION['user']['id']
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || !password_verify('df^H9$(jgs'.$currentPassword.'gsiuI5^O$WUYHg6', $user['password'])) {
            header("Location: ../../../settings.php?error=Current password is incorrect");
            exit();
        }

        // Hash the password before storing it
        $newPassword = password_hash('df^H9$(jgs'.$newPassword.'gsiuI5^O$WUYHg6', PASSWORD_BCRYPT);

        // Prepare and execute query to update user
        $stmt = $db->prepare("UPDATE `users` SET `password` = :password WHERE `id` = :id");
        $stmt->execute([
            'password' => $newPassword,
            'id' => $_SESSION['user']['id']
        ]);
    }
    catch (PDOException $e) {
        header('Location: /index.php?error=Database+error');
        exit();
    }

    // Redirect to settings page after successful password update
    header("Location: ../../../settings.php?success=Password updated successfully");
    exit();