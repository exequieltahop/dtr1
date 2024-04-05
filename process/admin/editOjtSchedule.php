<?php
    // <========== INCLUDE DATABASE CONNECTION ==========>
    include_once '../../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        // <========== CHECK SERVER REQUEST METHOD IF NOT GET THROW AN EXCEPTION ==========>
        if($_SERVER['REQUEST_METHOD'] !== 'PUT'){
            throw new Exception('Server Request Method Not PUT!');
        } 
        // <========== JSON DATA ==========>
        $json = json_decode(file_get_contents('php://input'), true);
        $id = $json['id'];
        $timeIn = $json['timeIn'];
        $timeOut = $json['timeOut'];
        $status = edit($id, $timeIn, $timeOut, $conn);
        if($status) {
            header('Content-Type:application/json');
            echo json_encode(['status' => 'Successfully Edited!']);
        }
    } catch (\Throwable $th) {// <========== CATCH THROWABLE ==========>
        header('Content-Type:application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {// <========== FINALLY IF DBCONNECTION WAS OPEN THEN CLOSE IT ==========>
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== EDIT DATA ==========>
    function edit(int $id,
                  string $timeIn,
                  string $timeOut,
                  mysqli $conn) : bool {
        try {
            $query = 'UPDATE time_in_out
                      SET time_in = ?,
                          time_out = ?
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('edit() stmt not prepared correctly! - '
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->bind_param('ssi', $timeIn, $timeOut, $id)){
                throw new Exception('edit() stmt bind_param failed! - '
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->execute()){
                throw new Exception('edit() stmt execution failed! - '
                                    .$conn->errno.'/'.$conn->error);
            }
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>