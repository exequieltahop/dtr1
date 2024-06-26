<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- <========== BOOTSTRAP CSS CDN ==========> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <========== CSS ==========> -->
    <link rel="stylesheet" href="../../css/admin/login.css">
    <!-- <========== SCRIPT ==========> -->
    <script src="../../js/admin/login.js"></script>
    <!-- <========== SCRIPT FOR SWEET ALERT ==========> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <!-- <========== LOADING ANIMATION ==========> -->
    <div class="loadingio-spinner-spinner-xo3sojksn5j" id="blurLoading">
        <div class="ldio-6lw6slcyjk5">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <main class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <!-- <========== FORM LOGIN ==========> -->
        <form class="p-4 border rounded w-100 bg-dark overflow-auto" id="form">
            <h1 class="text-white text-center">Login As Admin</h1>
            <div class="form-group d-flex flex-column gap-2 w-100">
                <label for="username" class="fw-bold text-white">Username</label>
                <input type="text" class="form-control w-100 rounded" id="username" autocomplete="username">
            </div>
            <div class="form-group d-flex flex-column gap-2">
                <label for="password" class="fw-bold text-white">Password</label>
                <input type="password" class="form-control w-100 rounded" id="password" autocomplete="current-password">
            </div>
            <div class="form-group d-flex gap-2">
                <input type="checkbox" class="input-check" id="showPassword">
                <label for="showPassword" class="text-white">Show Password</label>
            </div>
            <div class="d-flex mt-2 justify-content-end gap-2 overflow-auto">
                <button class="btn btn-primary" id="loginBtn">Login</button>
                <button class="btn btn-success"><a href="../../index.php" class="text text-white text-decoration-none">Back</a></button>
            </div>
        </form>
        <!-- <========== END FORM LOGIN ==========> -->
    </main>
</body>
</html>