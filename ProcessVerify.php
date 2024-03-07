<?php
// Include the database connection file
session_start();
include("connection.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST["student"];
    $password = $_POST["password"];

    // Validate the login using your database (replace 'users' with your actual table name)
    $query = "SELECT * FROM users 
              WHERE BINARY StudentID = ? 
              AND BINARY Pass = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error in query preparation: " . $conn->error);
    }

    $stmt->bind_param("ss", $studentID, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Fetch user data from the result
        $userData = $result->fetch_assoc();

        // Store user data in session variables
        $_SESSION["userID"] = $userData["UserID"];
        $_SESSION["studentID"] = $userData["StudentID"];
        $_SESSION["full_name"] = $userData["Full_Name"];
        $_SESSION["company_office"] = $userData["Company_Office"];
        $_SESSION["registered_at"] = $userData["Registered_at"];
        $_SESSION['status'] = 'okay';
        // Insert data into 'login' table
        $insertQuery = "INSERT INTO login (StudentID, Full_name, logged_in) VALUES (?, ?, CURRENT_TIMESTAMP)";
        $insertStmt = $conn->prepare($insertQuery);

        if (!$insertStmt) {
            die("Error in insert query preparation: " . $conn->error);
        }

        $insertStmt->bind_param("ss", $userData["StudentID"], $userData["Full_Name"]);
        $insertStmt->execute();

        // Redirect to the time.php page on successful login
        header("Location: time.php");
        exit();
    } else {
        echo "<p class='alert alert-danger'>Invalid student ID or password</p>";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
