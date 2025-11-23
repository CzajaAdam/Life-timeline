<?php
    session_start();
    if (isset($_SESSION['user']['id'])) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/head.php'; ?>
<body class="bg-charcoal-950">
    <div class="container mx-auto flex flex-col justify-between min-h-screen p-4 pb-128 md:pb-64">
        <!-- Header -->
        <header class="flex justify-start items-center flex-col">
            <img class="h-16" src="lifelines.png" alt="">
            <h1 class="text-3xl text-center font-bold text-charcoal-50 mb-2 py-2">LifeLines</h1>
            <hr class="w-1/3 md:w-1/2 border-charcoal-700 mb-4">
        </header>

        <div class="flex flex-col justify-start grow pt-2 items-center px-4">

            <!-- Login/register description -->
            <p id="log-reg-desc" class="text-charcoal-400 mb-6 text-center max-w-md text-lg text-pretty">Welcome back! Please enter your credentials to access your <span class="text-caleadon-500 font-bold">LifeLine</span>.</p>

            <!-- Login Form -->
            <div id="login-form" class="bg-charcoal-900 p-8 rounded-xl shadow-2xl mx-auto sm:w-96 w-full border border-charcoal-800">
                <form action="src/login.php" method="POST" class="space-y-4">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-charcoal-300 text-sm mb-1 font-medium">Email</label>
                        <input type="text" id="email" name="email" required class="w-full p-3 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-transparent transition">
                    </div>

                    <!-- Password -->
                    <div class="mb-0">
                        <label for="password" class="block text-charcoal-300 text-sm mb-1 font-medium">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required class="w-full p-3 pr-12 rounded-lg bg-charcoal-800 text-charcoal-50 border border-charcoal-700 focus:outline-none focus:ring-2 focus:ring-caleadon-500 focus:border-transparent transition">
                            <button type="button" onclick="togglePassword()" class="absolute cursor-pointer top-3.5 right-4 text-charcoal-400 hover:text-charcoal-200 focus:outline-none transition">
                                <i id="toggleIcon" class="fas fa-eye w-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="text-right">
                        <a href="forgot_password.php" class="text-caleadon-500 hover:text-caleadon-400 transition cursor-pointer text-sm underline">Forgot Password?</a>
                    </div>

                    <!-- Log In -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-caleadon-600 text-white p-3 rounded-lg hover:bg-caleadon-500 transition cursor-pointer font-bold shadow-lg hover:shadow-caleadon-600/20">Log In</button>
                    </div>

                    <!-- Or -->
                    <div class="flex items-center gap-3 my-6">
                        <div class="flex-1 border-t border-charcoal-700"></div>
                        <span class="text-charcoal-500 text-sm font-medium">OR</span>
                        <div class="flex-1 border-t border-charcoal-700"></div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="space-y-3">
                        <a class="w-full relative flex items-center justify-center bg-charcoal-800 border border-charcoal-700 text-charcoal-200 p-3 rounded-lg hover:bg-charcoal-750 hover:border-charcoal-600 cursor-pointer font-medium transition group">
                            <i class="fa-brands fa-google left-5 absolute text-charcoal-400 group-hover:text-charcoal-300 transition"></i>
                            <span class="text-sm">Continue with Google</span>
                        </a>
                        <a class="w-full relative flex items-center justify-center bg-charcoal-800 border border-charcoal-700 text-charcoal-200 p-3 rounded-lg hover:bg-charcoal-750 hover:border-charcoal-600 cursor-pointer font-medium transition group">
                            <i class="fa-brands fa-facebook-f left-5 absolute text-charcoal-400 group-hover:text-charcoal-300 transition"></i>
                            <span class="text-sm">Continue with Facebook</span>
                        </a>
                        <a class="w-full relative flex items-center justify-center bg-charcoal-800 border border-charcoal-700 text-charcoal-200 p-3 rounded-lg hover:bg-charcoal-750 hover:border-charcoal-600 cursor-pointer font-medium transition group">
                            <i class="fa-brands fa-apple left-5 absolute text-charcoal-400 group-hover:text-charcoal-300 transition"></i>
                            <span class="text-sm">Continue with Apple</span>
                        </a>
                    </div>

                    <div class="text-red-400 mt-4 text-center text-sm">
                        <?php
                            if (isset($_GET['error'])) {
                                echo htmlspecialchars($_GET['error']);
                            }
                        ?>
                    </div>
                    <div class="text-green-400 mt-4 text-center text-sm">
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
                <button id="switch-link" class="text-caleadon-500 hover:text-caleadon-400 transition cursor-pointer mt-6 font-normal underline">Create an Account</button>
            </a>
        </div>
        
        <!-- Footer -->
        
    </div>
</body>
<script src="script.js"></script>
</html>