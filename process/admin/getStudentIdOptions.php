<?php 
    // <========== SESSION START ==========>
    session_start();
    // <========== INCLUDE DATABASE CONNECTION ==========>
    include_once '../../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        // <========== CHECK SERVER REQUEST METHOD IF NOT GET THROW AN EXCEPTION ==========>
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET!');
        } 
        $opt = getStudentids($conn);
        header('Content-Type:application/json');
        echo json_encode(['data' => $opt]);
    } catch (\Throwable $th) {// <========== CATCH THROWABLE ==========>
        header('Content-Type:application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {// <========== FINALLY IF DBCONNECTION WAS OPEN THEN CLOSE IT ==========>
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== GET STUDENTIDS FROM THE DATABASE ==========>
    function getStudentids(mysqli $conn) : string {
        try {
            $return = '';
            $query = 'SELECT DISTINCT StudentID FROM users
                      WHERE user_type = "student"
                      ORDER BY StudentID ASC';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('getStudentids() stmt not prepared correctly!');
            }
            if(!$stmt->execute()){
                throw new Exception('getStudentids() stmt not prepared correctly!');
            }
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                $return .= '<option>None</option>';
            }else{
                $return .= '<option value=""></option>';
                while($row = $result->fetch_assoc()){
                    $return .= '<option value="'.htmlspecialchars($row['StudentID'], ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($row['StudentID'], ENT_QUOTES, 'UTF-8').'</option>';
                }
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>