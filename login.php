<?php
    session_start();
    if (isset($_SESSION['user']['id'])) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body class="bg-charcoal-900">
    <div class="container mx-auto p-4 flex flex-col justify-between min-h-screen">
        <!-- Header -->
        <header class="flex justify-start items-center flex-col">
            <img class="h-16" src="lifelines.png" alt="">
            <h1 class="text-3xl text-center font-bold text-charcoal-50 mb-2 py-2">LifeLines</h1>
            <hr class="w-1/3 md:w-1/2 border-charcoal-700 mb-4">
        </header>

        <div class="flex flex-col justify-start grow pt-2 items-center px-4">

            <!-- Login/register description -->
            <p id="log-reg-desc" class="text-charcoal-300 mb-4 text-center max-w-md text-lg text-pretty">Welcome back! Please enter your credentials to access your <span class="text-caleadon-600 font-bold">LifeLine</span>.</p>

            <!-- Login Form -->
            <div id="login-form" class="bg-charcoal-800 p-6 rounded-lg shadow-lg mx-auto w-full xl:w-1/3 lg:w-2/5 md:w-1/2 sm:w-2/3">
                <form action="src/login.php" method="POST" class="space-y-4">
                    <div>
                        <label for="email" class="block text-charcoal-200 mb-2">Email Address</label>
                        <input type="text" id="email" name="email" required class="w-full p-2 rounded-lg bg-charcoal-700 text-charcoal-50 focus:outline-none focus:ring-2 focus:ring-caleadon-500">
                    </div>
                    <div>
                        <label for="password" class="block text-charcoal-200 mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="w-full p-2 rounded-lg bg-charcoal-700 text-charcoal-50 focus:outline-none focus:ring-2 focus:ring-caleadon-500">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-caleadon-600 text-charcoal-50 p-2 rounded-lg hover:bg-caleadon-700 transition cursor-pointer">Login</button>
                    </div>
                    <div class="text-red-500 mt-4 text-center">
                        <?php
                            if (isset($_GET['error'])) {
                                echo htmlspecialchars($_GET['error']);
                            }
                        ?>
                    </div>
                    <div class="text-green-500 mt-4 text-center">
                        <?php
                            if (isset($_GET['success'])) {
                                echo htmlspecialchars($_GET['success']);
                            }
                        ?>
                    </div>
                </form>
            </div>
    
            <!-- Change to Registration -->
            <a href="register.php">
                <button id="switch-link" class="text-caleadon-600 hover:text-caleadon-700 transition cursor-pointer mt-4 font-light underline">Create an Account</button>
            </a>
        </div>
        
        <!-- Footer -->
        <footer class="flex justify-end items-center flex-col">
            <hr class="border-charcoal-700 mt-8 mb-4 w-1/3 md:w-1/2">
            <p class="text-center text-charcoal-400 text-sm py-2">© 2025 Life Timeline. All rights reserved.</p>
        </footer>
    </div>
</body>
<script src="script.js"></script>
</html>