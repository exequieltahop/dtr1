<?php
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Not GET');
        }
        $res = timeInChecker($_SESSION['full_name'], $_SESSION['studentID'], $conn);
        $res2 = timeOutChecker($_SESSION['full_name'], $_SESSION['studentID'], $conn);
        // if($res2 === true){
        //     echo 'true res2'.'<br>';
        // }else{
        //     echo 'false res2'.'<br>';
        // }
        // if($res == true){
        //     echo 'true res';
        // }else{
        //     echo 'true res';
        // }
        if($res === true && $res2 === true){
            $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $hour = $newDate->format('H');
            $min = $newDate->format('i');
            $time = floatval($hour.'.'.$min);
            if($time < 12.50){
                header('Content-Type: application/json');
                echo json_encode(['status'=>'Time Out']);
            }
            if($time > 12.50){
                header('Content-Type: application/json');
                echo json_encode(['status'=>'okay']);
            }
        }elseif($res === true && $res2 === false){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Time In']);
        }
        else{
            header('Content-Type: application/json');
            echo json_encode(['status'=>'okay']);
        }
        // if($res === true){
        //     if($res2 === true){
        //         $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
        //         $hour = $newDate->format('H');
        //         $min = $newDate->format('i');
        //         $time = floatval($hour.'.'.$min);
        //         if($time < 12.50){
        //             header('Content-Type: application/json');
        //             echo json_encode(['status'=>'Time Out']);
        //         }else{
        //             header('Content-Type: application/json');
        //             echo json_encode(['status'=>'okay']);
        //         }
        //     }
        //     else{
        //         header('Content-Type: application/json');
        //         echo json_encode(['status'=>'Time In']);
        //     }
        // }
        // elseif($res === false){
        //     header('Content-Type: application/json');
        //     echo json_encode(['status'=>'okay']);
        // }
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
            if($mer < 12 ){
                $meridiem = 'AM';
            }else{
                $meridiem = 'PM';
            }
            // $meridiem = 'AM';
            $stmt = $conn->prepare('SELECT * FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND DATE(date) = ?
                                    AND meridiem = ?
                                    AND time_in IS NOT NULL');
            if(!$stmt){
                throw new Exception('timeInChecker() stmt not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssss', $fullname, $studentId, $month, $meridiem);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows == 1){
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
    // time out checker
    function timeOutChecker(string $fullname, string $studentId, mysqli $conn) : bool {
        try {
            $newDate = new DateTIme('now', new DateTimeZone('Asia/Manila'));
            $month = $newDate->format('Y-m-d');
            $mer = $newDate->format('H');
            if($mer < 12 ){
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