<?php
    session_start();
    if (!isset($_SESSION['user']['id'])) {
        header('Location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body class="bg-charcoal-900">
    <?php echo htmlspecialchars($_SESSION['user']['email']); ?>

</body>
<script src="script.js"></script>
</html>