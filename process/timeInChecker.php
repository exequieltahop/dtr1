<?php
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Request Method Not Post');
        }
        $res = timeInChecker($_SESSION['full_name'], $_SESSION['studentID'], $conn);
        $res2 = timeOutChecker($_SESSION['full_name'], $_SESSION['studentID'], $conn);
        if($res == true && $res2 === true){
            $_SESSION['status'] = 'TimeOut';
            header('Content-Type: application/json');
            echo json_encode(['status'=>'time out']);
        }elseif($res == true && $res2 === false){
            $_SESSION['status'] = 'TimeOut';
            header('Content-Type: application/json');
            echo json_encode(['status'=>'time in']);
        }else{
            $_SESSION['status'] = 'okay';
            header('Content-Type: application/json');
            echo json_encode(['status'=>'not time in']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // time in checker
    function timeInChecker(string $fullname, string $studentId, mysqli $conn) : bool {
        try {
            $newDate = new DateTIme('now', new DateTimeZone('Asia/Manila'));
            $month = $newDate->format('Y-m-d');
            $mer = $newDate->format('H');
            if($mer < 12){
                $meridiem = 'AM';
            }else{
                $meridiem = 'PM';
            }
            $stmt = $conn->prepare('SELECT * FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND DATE(date) = ?
                                    AND meridiem = ?
                                    AND time_out IS NULL');
            if(!$stmt){
                throw new Exception('timeInChecker() stmt not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssss', $fullname, $studentId, $month, $meridiem);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows == 1){
                if($meridiem == 'PM'){
                    $stmt->close();
                    return true;
                }else{
                    $stmt->close();
                    return true;
                }
            }else{
                $stmt->close();
                return false;
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
    // time out checker
    function timeOutChecker(string $fullname, string $studentId, mysqli $conn) : bool {
        try {
            $newDate = new DateTIme('now', new DateTimeZone('Asia/Manila'));
            $month = $newDate->format('Y-m-d');
            $mer = $newDate->format('H');
            if($mer < 12){
                $meridiem = 'AM';
            }else{
                $meridiem = 'PM';
            }
            $stmt = $conn->prepare('SELECT * FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND DATE(date) = ?
                                    AND meridiem = ?
                                    AND time_out IS NOT NULL');
            if(!$stmt){
                throw new Exception('timeInChecker() stmt not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssss', $fullname, $studentId, $month, $meridiem);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows == 1){
                if($meridiem == 'PM'){
                    $stmt->close();
                    return true;
                }else{
                    $stmt->close();
                    return true;
                }
            }else{
                $stmt->close();
                return false;
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
?>