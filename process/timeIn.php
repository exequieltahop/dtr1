<?php 
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Method not POST!');
        }
        $rawJson = file_get_contents('php://input');
        $data = json_decode($rawJson, true);
        $ini = $data['ini'] ?? NULL;

        $checker = duplicateChecker($_SESSION['studentID'], $_SESSION['full_name'], $conn);
        $newTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $hour = $newTime->format('H');
        $minute = $newTime->format('i');
        $intMinute = intval($minute);
        if($ini === NULL){
            throw new Exception('Json Data was NULL');
        }
        if($checker === true){
            if($hour < 12){
                throw new Exception('You Already Time In this Morning!');
            }elseif($intMinute > 50){
                $res = timeIn($_SESSION['studentID'], $_SESSION['full_name'], $conn);
                if($res === true){// if true then echo a json status
                    $_SESSION['status'] = 'TimeIn';
                    header('Content-Type: application/json');
                    echo json_encode(['status'=>'Successfully Time In!']);
                }
            }
            else{
                throw new Exception('You Already Time In this Afternoon!');
            }
        }else{
            // time in save the data to the db
            $res = timeIn($_SESSION['studentID'], $_SESSION['full_name'], $conn);
            // check if successfully save the data
            if($res === true){// if true then echo a json status
                $_SESSION['status'] = 'TimeIn';
                header('Content-Type: application/json');
                echo json_encode(['status'=>'Successfully Time In!']);
            }
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
            exit;
        }
    }
    // add time in db
    function timeIn(string $studentId, string $studentName, mysqli $conn) : bool {
        try {
            $newTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $timeIn = $newTime->format('H:i:s');
            $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $date = $newDate->format('Y-m-d');
            $mer = $newDate->format('H');
            if($mer < 12){
                $meridiem = 'AM';
            }else{
                $meridiem = 'PM';
            }
            $stmt = $conn->prepare('INSERT INTO time_in_out(fullname,
                                                            student_id,
                                                            date,
                                                            time_in,
                                                            meridiem)
                                    VALUES(?, ?, ?, ?, ?)');
            if(!$stmt){
                throw new Error('timeIn() stmt not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('sssss', $studentName, $studentId, $date, $timeIn, $meridiem);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // time in checker for duplicate schedule checker
    function duplicateChecker(string $fullname, string $studentId, mysqli $conn) : bool {
        try {
            $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $month = $newDate->format('m');
            $time = $newDate->format('H');
            if($time < 12){
                $meridiem = 'AM';
            }else{
                $meridiem = 'PM';
            }
            $stmt = $conn->prepare('SELECT * FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND meridiem = ?');
            if(!$stmt){
                throw new Exception('duplicateChecker() stmt not prepare - '.$conn->errno.'/'. $conn->error);
            }
            $stmt->bind_param('sss', $fullname, $studentId, $meridiem);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 0){
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
