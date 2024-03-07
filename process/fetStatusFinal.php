<?php 
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Not GET');
        }
        $res = checkSched($_SESSION["full_name"], $_SESSION["studentID"], $conn);
        // echo $res;
        switch($res){
            case "AM Time in":
                header('Content-Type: application/json');
                echo json_encode(['status'=>$res]);
                break;
            case "AM Time out":
                header('Content-Type: application/json');
                echo json_encode(['status'=>$res]);
                break;
            case "PM Time in":
                header('Content-Type: application/json');
                echo json_encode(['status'=>$res]);
                break;
            case "PM Time out":
                header('Content-Type: application/json');
                echo json_encode(['status'=>$res]);
                break;
            case "blank":
                header('Content-Type: application/json');
                echo json_encode(['status'=>$res]);
                break;
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
           $conn->close(); 
        }
    }
    // function check latest sched
    function checkSched(string $fullname, string $studentId, mysqli $conn) {
        try {
            $timeOut = [];
            $date = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $now = $date->format('Y-m-d');
            $stmt = $conn->prepare('SELECT * FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND DATE(date) = ?');
            if(!$stmt){
                throw new Exception('checkSched() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('sss', $fullname, $studentId, $now);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows == 1){
                while($row = $res->fetch_assoc()){
                    if($row['time_out'] == ''){
                        $stmt->close();
                        return 'AM Time in';
                    }else{
                        $stmt->close();
                        return 'AM Time out';
                    }
                }
            }elseif($res->num_rows == 2){
                while($row = $res->fetch_assoc()){
                    $timeOut[] = $row['time_out'];
                }
                if($timeOut[1] == ''){
                    $stmt->close();
                    return 'PM Time in';
                }else{
                    $stmt->close();
                    return 'PM Time out';
                }
            }else{
                $stmt->close();
                return 'blank';
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
?>