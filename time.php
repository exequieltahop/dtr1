<?php
    // Start the session to access session variables
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION["studentID"])) {
        // Redirect to the login page if not logged in
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="SLSU_Logo.png" class="rounded-circle">
    <title>OJT Daily Time Record</title>
    <!-- css adn script -->
    <link rel="stylesheet" href="css/time.css">
    <!-- MAIN SCRIPT -->
    <script src="js/time.js"></script>
    <!-- <========== SWEET ALERT CDN ==========> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main>
        <!-- burger -->
        <img src="assets/burger-bar.png" alt="menu" class="burger-icon">
        <!-- nav -->
        <aside class="nav">
            <a href="time.php" class="anchor">Home</a>
            <a href="pages/dtr.php" class="anchor">DTR</a>
            <a href="process/logoutProcess.php" class="anchor">Logout</a>
        </aside>
        <!-- time in container -->
        <section class="container">
            <h1 class="time">Loading.....</h1>
            <!-- <========== BUTTON TIME IN ==========> -->
            <input type="hidden" id="hiddenStatus">
            <button id="btnTime" disable>Time in</button>
            <button id="btnTimeOut" disable>Time out</button>
            <!-- <========== TOTAL HOURS ==========> -->
            <div class="container-total-hours">
                <h1 class="h1-total-hours">Loading...</h1>
            </div>
            <!-- <========== END TOTAL HOURS ==========> -->
        </section>
    </main>
</body>
</html>
