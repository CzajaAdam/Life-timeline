<?php
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-900">
    <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
    <a href="src/logout.php" class="text-caleadon-600 hover:text-caleadon-700 transition cursor-pointer mt-4 font-light underline">Logout</a>
</body>
<script src="script.js"></script>
</html>