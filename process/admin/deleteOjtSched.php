<?php 
    // <========== SESSION START ==========>
    session_start();
    // <========== INCLUDE DATABASE CONNECTION ==========>
    include_once '../../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        // <========== CHECK SERVER REQUEST METHOD IF NOT GET THROW AN EXCEPTION ==========>
        if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
            throw new Exception('Server Request Method Not DELETE!');
        } 
        // <========== GET id ==========>
        $id = urldecode($_GET['id']);
        $status = deleteData($id, $conn);
        if($status === true){
            header('Content-Type:application/json');
            echo json_encode(['status' => 'Sucessfully Deleted Schedule!']);
        }
    } catch (\Throwable $th) {// <========== CATCH THROWABLE ==========>
        header('Content-Type:application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {// <========== FINALLY IF DBCONNECTION WAS OPEN THEN CLOSE IT ==========>
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== DELETE DATA IN THE DATABASE ==========>
    function deleteData(int $id, mysqli $conn) : bool {
        try {
            $query = 'DELETE from time_in_out
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('deleteData() stmt not prepare correctly!'
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->bind_param('i', $id)){
                throw new Exception('deleteData() stmt bind_param failed!'
                                    .$conn->errno.'/'.$conn->error);
            }
            if(!$stmt->execute()){
                throw new Exception('deleteData() stmt execution failed!'
                                    .$conn->errno.'/'.$conn->error);
            }
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>