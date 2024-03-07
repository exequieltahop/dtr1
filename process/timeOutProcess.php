<?php 
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server request method not post');
        }
        $res = timeOut($_SESSION['full_name'], $_SESSION['studentID'], $conn);
        if($res === true){
            $_SESSION['status'] = 'okay';
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Time Out!']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // time out
    function timeOut(string $fullname, string $studentId, mysqli $conn) : bool {
        try {
            $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $month = $newDate->format('Y-m-d');
            $time = $newDate->format('H:i:s');
            $hour = $newDate->format('H');
            if($hour < 13){
                $meridiem = 'AM';
            }else{
                $meridiem = 'PM';
            }
            $stmt = $conn->prepare('UPDATE time_in_out SET time_out = ?
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND DATE(date) = ?
                                    AND meridiem = ?');
            if(!$stmt){
                throw new Exception('timeOut() stmt not prepare - '.$conn->errno.'/'. $conn->error);
            }
            $stmt->bind_param('sssss', $time, $fullname, $studentId, $month, $meridiem);
            $stmt->execute();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>