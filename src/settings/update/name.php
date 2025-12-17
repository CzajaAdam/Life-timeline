<?php
    // Start session and check authentication
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if form data is set
    if (!isset($_POST['full-name'], $_POST['email'])) {
        header('Location: /index.php?error=Missing+form+data');
        exit();
    }

    // Validate and sanitize inputs
    $fullName = filter_var($_POST['full-name'], FILTER_DEFAULT);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $bio = filter_var($_POST['bio'], FILTER_DEFAULT);

    try {
        // Database connection
        $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');

        // Split full name into first and last name
        $nameParts = explode(' ', trim($fullName));
        if (count($nameParts) > 0) {
            $firstName = array_shift($nameParts);
            $lastName = implode(' ', $nameParts);
        }

        // Update user information in database
        $stmt = $db->prepare("UPDATE `users` SET `first_name` = :firstName, `last_name` = :lastName, `email` = :email, `bio` = :bio WHERE `id` = :id");
        $stmt->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'bio' => $bio,
            'id' => $_SESSION['user']['id']
        ]);
    } catch (PDOException $e) {
        header('Location: /index.php?error=Database+error');
        exit();
    }
    

    // Redirect to dashboard after successful creation
    header('Location: ../../../settings.php?success=Profile+updated+successfully');
    exit();