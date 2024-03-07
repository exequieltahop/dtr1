<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign Up</title>
    <!-- css and script -->
    <link rel="stylesheet" href="../css/signUp.css">
    <script type="module" src="../js/signUp.js"></script>
</head>
<body>
    <!-- sign up -->
    <main>
        <!-- img -->
        <div class="img-wrapper">
            <img src="../INTERN.WEBP" alt="" class="img-side">
        </div>
        <!-- section sign up form -->
        <section class="sign-up-container">
            <h1 class="h1-sign-up">Sign Up Here</h1>
            <div class="input-wrapper">
                <label for="fullName" class="input-label">Full Name</label>
                <input type="text" class="input" id="fullName">
            </div>
            <div class="input-wrapper">
                <label for="studentId" class="input-label">StudentId</label>
                <input type="text" class="input" id="studentId">
            </div>
            <div class="input-wrapper">
                <label for="hte" class="input-label">HTE</label>
                <input type="text" class="input" id="hte">
            </div>
            <div class="input-wrapper">
                <label for="hteAdviser" class="input-label">HTE Adviser</label>
                <input type="text" class="input" id="hteAdviser">
            </div>
            <div class="input-wrapper">
                <label for="password" class="input-label">Password</label>
                <input type="password" class="input" id="password">
            </div>
            <div class="show-pass-container">
                <input type="checkbox" id="showHidePassword">
                <span class="span -show-pass">Show Password</span>
            </div>
            <!-- btn sign up -->
            <div class="btn-wrapper">
                <button class="btn-sign-up" id="btnSignUp">Sign Up</button>
                <button class="btn-clear" id="btnClear">Clear</button>
            </div>
            <a href="../index.php" class="sign-in-link">Already have an account? Sign In here!</a>
        </section>
    </main>
</body>
</html>