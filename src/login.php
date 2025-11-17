<?php
    session_start();

    // Check if form is submitted
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        header("Location: ../login.php");
        exit();
    }

    // Retrieve and sanitize user inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_DEFAULT);

    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');
    
    // Prepare and execute query to fetch user
    $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    
    // Check if user exists
    if ($stmt->rowCount() === 0) {
        header("Location: ../login.php?error=Invalid credentials");
        exit();
    }
    
    // Fetch user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if (!password_verify('df^H9$(jgs'.$password.'gsiuI5^O$WUYHg6', $user['password'])) {
        header("Location: ../login.php?error=Invalid credentials");
        exit();
    }

    // Set session variables
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'name' => $user['name']
    ];

    // Redirect to dashboard or home page after successful login
    header("Location: ../index.php");
    exit();