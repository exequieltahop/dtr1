<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="SLSU_Logo.png" class="rounded-circle">
        <!-- css and script-->
        <link rel="stylesheet" href="css/index.css">
        <script src="js/index.js"></script>
        <title>Login</title>
    </head>
    <body>
        <!-- all container -->
        <div class="all-container">
            <!-- logo container -->
            <div class="img-logo-container">
                <!-- <img src="INTERN.webp" alt="dtr-logo" class="dtr-logo"> -->
            </div>
            <!-- form -->
            <form class="form-login">
                <h2 class="h2-login-header">OJT DTR Login</h2>
                <div class="username">
                    <label for="student" class="form-label">StudentID:</label>
                    <input type="text" id="student" name="student" class="input" required>
                </div>
                <div class="username">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="input" required>
                    <img src="assets/hide.png" alt="show-hide" class="show-hide-icon">
                </div>
                <button class="btn btn-login" id="submitBtn">Login</button>
                <a href="pages/signUp.php" class="sign-up-link">Don't Have An Account Yet? Sign Up Here</a>
            </form>
        </div>
    </body>
</html>
