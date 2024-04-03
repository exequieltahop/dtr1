<?php 
// <========== SESSION START ==========>
    session_start();
// <========== INCLUDE DATABASE CONNECTION ==========>
    include_once '../../dbcon/conn.php';
// <========== MAIN ==========>
    try {
        // <========== CHECK SERVER REQUEST METHOD ==========>
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('Server Not POST!');
        }
        // <========== POST VARIABLES ==========>
        $studentid = $_POST['studentid'];
        $date = $_POST['date'];
        $timein = $_POST['timein'];
        $timeout = $_POST['timeout'];
        $meridiem = $_POST['meridiem'];
        // <========== GET STUDENT FULLNAME ==========>
        $fullname = studentFullname($studentid, $conn);
        // <========== CHECK STUDENT FULLNAME WAS NOT EMPTY ==========>
        if(empty($fullname)){
            header('Content-Type: application/json');
            echo json_encode(['status1' => 'Student ID was not registered yet!']);
        }else{
            $status = addSched($studentid, 
                               $date, 
                               $timein, 
                               $timeout, 
                               $meridiem, 
                               $fullname, 
                               $conn);
            if($status === true){
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Successfully Added Schedule']);
            }else{
                header('Content-Type: application/json');
                echo json_encode(['status1' => 'Failed To Add Schedule']);
            }
            
        }
    } catch (\Throwable $th) {
        header('Content-Type: application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally{
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== ADD SCHED ==========>
    function addSched(string $studentid,
                      string $date, 
                      string $timein, 
                      string $timeout, 
                      string $meridiem, 
                      string $fullname,
                      mysqli $conn) : bool {
        try {
            // <========== QUERY ==========>
            $query = 'INSERT INTO time_in_out(fullname, 
                                              student_id, 
                                              date, 
                                              time_in, 
                                              time_out, 
                                              meridiem)
                      VALUES(?, ?, ?, ?, ?, ?)';
            // <========== PREPARED QUERY ==========>
            $stmt = $conn->prepare($query);
            // <========== CHECK IF STMT IS CORRECT ==========>
            if(!$stmt){
                throw new Exception('addSched() stmt not prepared correctly!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            if(!$stmt->bind_param('ssssss', $fullname,
                                            $studentid,
                                            $date,
                                            $timein,
                                            $timeout,
                                            $meridiem)){
                throw new Exception('addSched() stmt bind param failed!/ '
                .$conn->errno.'/',$conn->error);
            }
            if(!$stmt->execute()){
                throw new Exception('addSched() stmt execution failed!/ '
                .$conn->errno.'/',$conn->error);
            }
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function studentFullname(string $studentid, mysqli $conn) : string{
        try {
            $return = '';
            // <========== QUERY ==========>
            $query = 'SELECT Full_Name FROM users 
                      WHERE BINARY StudentID = ?';
            // <========== PREPARED QUERY ==========>
            $stmt = $conn->prepare($query);
            // <========== CHECK IF STMT IS CORRECT ==========>
            if(!$stmt){
                throw new Exception('studentFullname() stmt not prepared correctly!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            // <========== CHECK IF STMT PLACEHOLDER IS NOT BINDED CORRECTLY ==========>
            if(!$stmt->bind_param('s', $studentid)){
                throw new Exception('studentFullname() stmt placeholder not binded!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            // <========== CHECK IF STMT WAS EXECUTING ==========>
            if(!$stmt->execute()){
                throw new Exception('studentFullname() stmt failed to execute!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            // <========== GET RESULT FROM QUERY ==========>
            $result = $stmt->get_result();
            // <========== CHECK IF RESULT ROWS WAS 0, IF 0 THEN RETURN '' ==========>
            if($result->num_rows < 1){
                $return .= '';
            }
             // <========== IF RESULT WAS NOT EMPTY THEN PASS IT TO THE $row VARIABLE THEN RETURN IT ==========>
            if($row = $result->fetch_assoc()){
                $return .= $row['Full_Name'];
            }
            // <========== CLOSE STMT AND RETURN THE STRING ==========>
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>