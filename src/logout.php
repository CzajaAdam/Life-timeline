<?php
    session_start();
    setcookie(session_name(), '', time() - 3600, '/');
    session_abort();
    header('Location: ../login.php?success=Logged out successfully');
    exit();