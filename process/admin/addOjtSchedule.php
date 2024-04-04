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
            // <========== CHECK IF THE SCHEDULE WAS DUPLICATE ==========>
            $duplicateChecker = dupSchedChecker($studentid, 
                                                $date, 
                                                $meridiem, 
                                                $conn);
            if($duplicateChecker === true){
                header('Content-Type: application/json');
                echo json_encode(['status1' => 'Duplicate Schedule!']);
            }else{
                // <========== IF THE SCHEDULE WAS !DUPLICATE ==========>
                // <========== ADD SCHEDULE ==========>
                $status = addSched($studentid, 
                                   $date, 
                                   $timein, 
                                   $timeout, 
                                   $meridiem, 
                                   $fullname, 
                                   $conn);
                if($status === true){// <========== CHECK IF SCHEDULE WAS SUCCESSFULLY ADDED ==========>
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'Successfully Added Schedule']);
                }else{// <========== CHECK IF SCHEDULE WAS NOT SUCCESSFULLY ADDED ==========>
                    header('Content-Type: application/json');
                    echo json_encode(['status1' => 'Failed To Add Schedule']);
                }
            }
            
        }
    } catch (\Throwable $th) {// <========== CATCH ERROR AND EXCEPTION ==========>
        header('Content-Type: application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally{// <========== FINALLY CLOSE THE CONNECTION FROM THE DATABASE ==========>
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
            // <========== CHECK IF STMT BIND PARAM WAS SUCCESSFUL ==========>
            if(!$stmt->bind_param('ssssss', $fullname,
                                            $studentid,
                                            $date,
                                            $timein,
                                            $timeout,
                                            $meridiem)){
                throw new Exception('addSched() stmt bind param failed!/ '
                .$conn->errno.'/',$conn->error);
            }
            // <========== CHECK IF STMT SUCCESSFULLY EXECUTE ==========>
            if(!$stmt->execute()){
                throw new Exception('addSched() stmt execution failed!/ '
                .$conn->errno.'/',$conn->error);
            }
            return true;
        } catch (\Throwable $th) {// <========== CATCH ERRORS AND EXCEPTIONS ==========>
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
    // <========== DUPLICATE SCHEDULE CHECKER ==========>
    function dupSchedChecker(string $studentid, 
                             string $date, 
                             string $meridiem, 
                             mysqli $conn) : bool {
        try {
            // <========== QUERY ==========>
            $query = 'SELECT * FROM time_in_out
                      WHERE student_id = ?
                      AND DATE(date) = ?
                      AND meridiem = ?';
            // <========== PREPARE QUERY ==========>
            $stmt = $conn->prepare($query);
            // <========== CHECK PREPARE QUERY IF CORRRECT ==========>
            if(!$stmt){
                throw new Exception('dupSchedChecker() stmt not prepared correctly! - '
                                    .$conn->errno.'/'.$conn->error);
            }
            // <========== BIND PARAM ==========>
            if(!$stmt->bind_param('sss', $studentid, $date, $meridiem)){
                throw new Exception('dupSchedChecker() stmt bind_param incorrect! - '
                                    .$conn->errno.'/'.$conn->error);
            }
            // <========== EXECUTE QUERY ==========>
            if(!$stmt->execute()){
                throw new Exception('dupSchedChecker() stmt execution failed! - '
                                    .$conn->errno.'/'.$conn->error);
            } 
            // <========== GET RESULT ==========>
            $result = $stmt->get_result();
            // <========== CHECK RESULT ROW ==========>
            if($result->num_rows < 1){
                $stmt->close();
                return false;
            }else{
                $stmt->close();
                return true;
            }
        } catch (\Throwable $th) {// <========== CATCH ERRORS AND EXCEPTIONS ==========>
            throw $th;
        }
    }
?>