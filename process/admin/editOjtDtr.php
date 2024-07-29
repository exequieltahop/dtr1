<?php 
    // <========== INCLUDE DATABASE CONNECTION ==========>
    include_once '../../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        // <========== CHECK SERVER REQUEST METHOD IF NOT GET THROW AN EXCEPTION ==========>
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET!');
        } 
        // <========== GET ID ==========>
        $id = urldecode($_GET['id']);
        $data = getData($id, $conn);
        header('Content-Type:application/json');
        echo json_encode(['data' => $data]);
    } catch (\Throwable $th) {// <========== CATCH THROWABLE ==========>
        header('Content-Type:application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {// <========== FINALLY IF DBCONNECTION WAS OPEN THEN CLOSE IT ==========>
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== GET DATA FOR THE EDIT FORM ==========>
    function getData(int $id, mysqli $conn) : array {
        try {
            $query = 'SELECT * FROM time_in_out
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('getData() stmt not prepare correctly!'
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->bind_param('i', $id)){
                throw new Exception('getData() stmt bind_param failed!'
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->execute()){
                throw new Exception('getData() stmt execution failed!'
                                    .$conn->errno.'/'.$conn->error);
            }
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                throw new Exception('getData() $result has not result row!'
                                    .$conn->errno.'/'.$conn->error);
            }else{  
                if($row = $result->fetch_assoc()){
                    if($row['time_in'] == ''){
                        $timein = '';
                    }else{
                        $rawTimeIn = new DateTime($row['time_in']);
                        $timein = $rawTimeIn->format('H:i');
                    }
                    if($row['time_out'] == ''){
                        $timeOUT = '';
                    }else{
                        $rawTimeOut = new DateTime($row['time_out']);
                        $timeOUT = $rawTimeOut->format('H:i'); // corrected line
                    }
                    $return = [
                        'date' => $row['date'],
                        'meridiem' => $row['meridiem'],
                        'timeIn' => $timein,
                        'timeOut' => $timeOUT
                    ];
                }
            }
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>