<?php
    // <========== START SESSION ==========>
    session_start();
    // <========== INCLUDE DB CONNECTION ==========>
    include_once '../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        $uname = $_SESSION["studentID"];
        $data = getTotalHours($uname, $conn);
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    } catch (\Throwable $th) {
        header('Content-Type:application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== GET TOTAL HOURS ==========>
    function getTotalHours(string $uname, mysqli $conn) : string {
        try {
            $return = '';
            $query = 'SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS total_time
                        FROM time_in_out
                        WHERE student_id = ?
                        AND time_in IS NOT NULL
                        AND time_out IS NOT NULL;';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('getTotalHours() stmt not prepared correctly - '
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->bind_param('s',$uname)){
                throw new Exception('getTotalHours() stmt not binded correctly - '
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->execute()){
                throw new Exception('getTotalHours() stmt execution failed - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $result = $stmt->get_result();
            if($result->num_rows == 0){
                
                $return .= '0 TOTAL HOURS';
            }else{
                if($row = $result->fetch_assoc()){
                    $hour = substr(str_replace(':','.',$row['total_time']), 0, 5);
                    $return .= $hour.' TOTAL HOURS';
                }
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>