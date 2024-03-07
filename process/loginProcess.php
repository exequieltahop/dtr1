<?php
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Not POST');
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $username = $data['username'];
        $password = $data['password'];
        $auth = auth($username, $password, $conn);
        if($auth == true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'ok']);    
        }else{
            header('Content-Type: application/json');
            echo json_encode(['status'=>'!ok']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // authenticate data
    function auth(string $username, string $password, mysqli $conn) : bool {
        try {
            $query = 'SELECT * FROM users
                      WHERE BINARY StudentID = ?
                      AND BINARY Pass = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('auth() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){   
                    // Store user data in session variables
                    $_SESSION["userID"] = $row["UserID"];
                    $_SESSION["studentID"] = $row["StudentID"];
                    $_SESSION["full_name"] = $row["Full_Name"];
                    $_SESSION["company_office"] = $row["Company_Office"];
                    $_SESSION["registered_at"] = $row["Registered_at"];
                }
                $stmt->close();
                return true;
            }else{
                $stmt->close();
                return false;
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
?>  