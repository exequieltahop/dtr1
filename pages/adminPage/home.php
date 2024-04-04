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
    <!-- <========== BOOTSTRAP ICON CDN ==========> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    <main class="container p-3 d-flex flex-column justify-content-center align-items-center gap-3">
        <!-- <========== FORM IN ADDING OJT SCHEDULE ==========> -->
        <form class="shadow p-3 w-100 border border-dark rounded bg-white" id="form">
            <h2 class="h2 text-center">Add OJT Schedule</h1>
            <div class="form-group">
                <label for="studentid">Student ID</label>
                <select name="studentid" id="studentid" class="form-select">
                    <!-- <========== THE OPTION WAS DYNAMICALLY FETCHED FROM THE DATABASE ==========> -->
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
                <label for="meridiem">Meridiem</label>
                <select name="meridiem" id="meridiem" class="form-select">
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            </div>
            <div class="form-group">
                <label for="timeIn">Time In</label>
                <input type="time" class="form-control" id="timeIn" name="timein">
            </div>
            <div class="form-group">
                <label for="timeOut">Time Out</label>
                <input type="time" class="form-control" id="timeOut" name="timeout">
            </div>
            <button class="btn btn-primary mt-3" id="submitbtn">Submit</button>
        </form>
        <!-- <========== END FORM IN ADDING OJT SCHEDULE ==========> -->

        <!-- <========== ADD OJT SCHED BTN ==========> -->
        <div class="d-flex w-100 gap-3 justify-content-between">
            <button class="btn btn-primary text-nowrap" style="width: fit-content;"><i class="bi bi-plus-square" style="margin-right: 1em;"></i>ADD SCHEDULE</button>
            <select id="studentIdPicker" class="form-select" style="width:fit-content;">
                <!-- <========== STUDENT ID PICKER OPTIONS WAS ADDED DYNAMICALLY WITH JS ==========> -->
            </select>
        </div>
        <!-- <========== END ADD OJT SCHED BTN ==========> -->

        <!-- <========== OJT DTR ==========> -->
        <div class="container card p-0 shadow border border-dark">
            <div class="card-header">
                <h1 class="h1">OJT DTR</h1>
            </div>
            <div class="card-body table-responsive">
                <table class="table text-nowrap text-center table-hover">
                    <tr class="table-dark">
                        <th rowspan="2">Day</th>
                        <th colspan="2">A.M</th>
                        <th colspan="2">P.M</th>
                        <th colspan="2">Undertime</th>
                        <th>Action</th>
                    </tr>
                    <tr class="table-dark">
                        <th colspan="2">Arrival</th>
                        <th colspan="2">Arrival</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Enim, recusan</td>
                            <td>Voluptas </td>
                            <td>Dolor </td>
                            <td>Nesciunt </td>
                            <td>Quia c</td>
                            <td>Soluta </td>
                            <td>
                                <i class="bi bi-pencil-square text-primary" style="cursor: pointer;"></i>
                                <i class="bi bi-archive text-danger" style="cursor: pointer;"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Enim, recusan</td>
                            <td>Voluptas </td>
                            <td>Dolor </td>
                            <td>Nesciunt </td>
                            <td>Quia c</td>
                            <td>Soluta </td>
                            <td>
                                <i class="bi bi-pencil-square text-primary" style="cursor: pointer;"></i>
                                <i class="bi bi-archive text-danger" style="cursor: pointer;"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Enim, recusan</td>
                            <td>Voluptas </td>
                            <td>Dolor </td>
                            <td>Nesciunt </td>
                            <td>Quia c</td>
                            <td>Soluta </td>
                            <td>
                                <i class="bi bi-pencil-square text-primary" style="cursor: pointer;"></i>
                                <i class="bi bi-archive text-danger" style="cursor: pointer;"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <========== END OJT DTR ==========> -->
    </main>
    <!-- <========== END MAIN ==========> -->
    <footer></footer>
</body>
</html>