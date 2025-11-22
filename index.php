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
    <div class="container mx-auto flex flex-col justify-between min-h-screen p-4">

        <!-- Navbar -->
        <nav class="absolute left-0 top-0 h-full">
            <?php include 'templates/navbar.php'; ?>
        </nav>

        <!-- Header -->
        <header class="flex justify-start items-center flex-col">
            <img class="h-16" src="lifelines.png" alt="">
            <h1 class="text-3xl text-center font-bold text-charcoal-50 mb-2 py-2">LifeLines</h1>
            <hr class="w-1/3 md:w-1/2 border-charcoal-700 mb-4">
        </header>

        <!-- Main Content -->
        <div class="flex flex-col justify-start grow pt-2 items-center px-4">
            <h2 class="text-charcoal-200 text-2xl mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h2>
            <p class="text-charcoal-300 text-center max-w-md text-lg text-pretty">This is your dashboard. From here, you can manage your LifeLines and access all the features available to you.</p>
        </div>



    </div>

</body>
<script src="script.js"></script>
</html>