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
    <title>OJT List</title>
    <!-- <========== BOOTSTRAP CSS CDN ==========> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <========== BOOTSTRAP ICON CDN ==========> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <========== PLAIN CSS ==========> -->
    <link rel="stylesheet" href="../../css/admin/ojtList.css">
    <!-- <========== PLAIN CSS ==========> -->
    <script src="../../js/admin/ojtList.js"></script>
</head>
<body class="position-relative bg-light">
    <!-- <==========<========== MODALS ==========>==========> -->
    <!-- <========== DTR-- ==========> -->
    <section class="d-none container-fluid p-3 position-absolute bg-white" id="dtr-pop-up">
        <table class="table-dtr">
            <tbody class="tbody"> 
                <!-- tr header -->
                <tr class="tr-bold">
                    <td class="td"  rowspan="2">Day</td>
                    <td class="td" colspan="2">A.M</td>
                    <td class="td" colspan="2">P.M</td>
                    <td class="td" colspan="2">Undertime</td>
                </tr>
                <tr class="tr-bold">
                    <td class="td">Arrival</td>
                    <td class="td">Departure</td>
                    <td class="td">Arrival</td>
                    <td class="td">Departure</td>
                    <td class="td">Hours</td>
                    <td class="td">Minutes</td>
                </tr>
                <!-- the days here -->
            </tbody>
        </table>
    </section>
    <!-- <========== HEADER --NAV-- ==========> -->
    <header class="container-fluid nav p-2 bg-dark justify-content-center">
        <div class="nav-item">
            <a href="#" class="nav-link fw-bold text-white">Home</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link fw-bold text-white">FAQS</a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link fw-bold text-white">Sign Out</a>
        </div>
    </header>
    <!-- <========== MAIN ==========> -->
    <main class="container-fluid p-3">
        <div class="card shadow">
            <div class="card-header bg-primary">
                <h1 class="text-white">OJT LIST</h1>
            </div>
            <div class="card-body table-responsive">
                <!-- <========== OJT LIST ==========> -->
                <table class="table table-sm table-hover text-nowrap">
                    <thead>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Registered Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2010480-1</td>
                            <td class="see-dtr">Exequiel D. Tahop</td>
                            <td>Jan. 15, 2024</td>
                            <td>
                                <i class="bi bi-pencil-square cursor-pointer edit-icon text-success" title="Edit OJT Details"></i>
                                <i class="bi bi-trash delete-icon text-danger" title="Delete OJT"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>2010480-1</td>
                            <td class="see-dtr">Exequiel D. Tahop</td>
                            <td>Jan. 15, 2024</td>
                            <td>
                                <i class="bi bi-pencil-square cursor-pointer edit-icon text-success" title="Edit OJT Details"></i>
                                <i class="bi bi-trash delete-icon text-danger" title="Delete OJT"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>2010480-1</td>
                            <td class="see-dtr">Exequiel D. Tahop</td>
                            <td>Jan. 15, 2024</td>
                            <td>
                                <i class="bi bi-pencil-square cursor-pointer edit-icon text-success" title="Edit OJT Details"></i>
                                <i class="bi bi-trash delete-icon text-danger" title="Delete OJT"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer></footer>
</body>
</html>