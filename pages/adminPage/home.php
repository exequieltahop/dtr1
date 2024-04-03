<?php 
    // <========== SESSION START ==========>
    session_start();
    // <========== AUTH ==========>
    if(!isset($_SESSION['hasLogin'])){
        if($_SESSION['role'] != 'admin'){
            header('Location: login.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- <========== BOOTSTRAP CSS CDN ==========> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <========== MAIN STYLE ==========> -->
    <link rel="stylesheet" href="../../css/admin/home.css">
    <!-- <========== MAIN STYLE ==========> -->
    <script src="../../js/admin/home.js"></script>
    <!-- <========== SWEET ALERT CDN ==========> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <!-- <========== LOADDER ==========> -->
    <div class="loader-div" id="loader">
        <div class="loader"></div>
    </div>
    <!-- <========== HEADER ==========> -->
    <header class="container-fluid bg-white p-2 shadow">
        <nav class="nav w-100 d-flex justify-content-end">
            <div class="nav-item">
                <a href="logout.php" class="nav-link border border-primary fw-bold">Logout</a>
            </div>
        </nav>
    </header>
    <!-- <========== END HEADER ==========> -->
    <!-- <========== MAIN ==========> -->
    <main class="container-fluid p-3 d-flex justify-content-center">
        <!-- <========== FORM IN ADDING OJT SCHEDULE ==========> -->
        <form class="shadow p-3 w-100 border rounded bg-white" id="form">
            <h2 class="h2">Add OJT Schedule</h1>
            <div class="form-group">
                <label for="studentid">Student ID</label>
                <input type="text" class="form-control" placeholder="Enter Student ID" id="studentid" name="studentid">
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
                <label for="timeIn">Time In</label>
                <input type="time" class="form-control" id="timeIn" name="timein">
            </div>
            <div class="form-group">
                <label for="timeOut">Time Out</label>
                <input type="time" class="form-control" id="timeOut" name="timeout">
            </div>
            <div class="form-group">
                <label for="meridiem">Meridiem</label>
                <select name="meridiem" id="meridiem" class="form-select">
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            </div>
            <button class="btn btn-primary mt-3" id="submitbtn">Submit</button>
        </form>
        <!-- <========== END FORM IN ADDING OJT SCHEDULE ==========> -->
    </main>
    <!-- <========== END MAIN ==========> -->
    <footer></footer>
</body>
</html>