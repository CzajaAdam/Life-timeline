<?php
    session_start();

    // Check if form is submitted
    if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['confirmPassword'])) {
        header("Location: ../register.php");
        exit();
    }

    // Retrieve and sanitize user inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_DEFAULT);
    $confirmPassword = filter_var($_POST['confirmPassword'], FILTER_DEFAULT);
    $firstName = filter_var($_POST['firstName'], FILTER_DEFAULT);
    $lastName = filter_var($_POST['lastName'], FILTER_DEFAULT);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        header("Location: ../register.php?error=Passwords do not match");
        exit();
    }

    // Hash the password before storing it
    $password = password_hash('df^H9$(jgs'.$password.'gsiuI5^O$WUYHg6', PASSWORD_BCRYPT);

    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=lifelines;charset=utf8mb4', 'root', '');

    // Prepare and execute query to check if user exists
    $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
    $stmt->execute(['email' => $email]);

    // Check if user already exists
    if ($stmt->rowCount() !== 0) {
        header("Location: ../register.php?error=Email already registered");
        exit();
    }

    // Prepare and execute query to insert user
    $stmt = $db->prepare("INSERT INTO `users` (`email`, `password`, `first_name`, `last_name`) VALUES (:email, :password, :first_name, :last_name)");
    $stmt->execute(['email' => $email, 'password' => $password, 'first_name' => $firstName, 'last_name' => $lastName]);

    // Redirect to login page after successful registration
    header("Location: ../login.php?success=Account created successfully");
    exit();