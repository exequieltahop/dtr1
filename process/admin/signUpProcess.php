<?php 
    // <========== START SESSION ==========>
    session_start();
    // <========== INCLUDE THE DATABASE ONCE ==========>
    include_once '../../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('General Exception: Server Request Method Not POST');
        }
        $json = json_decode(file_get_contents('php://input'));
        $uname = $json->uname;
        $password = $json->pass;
        $fullname = $json->fullname;
        $hte = $json->hte;
        if($uname == '' || $password == '' || $fullname == '' || $hte == ''){
            throw new Exception('General Exception: uname, pass, fullname, or hte json data was empty!');
        }
        $unameValidation = validate($uname, $conn);
    } catch (\Throwable $th) {
        header('Content-Type: application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== SIGNUP ==========>
    function signUp(string $fullname, 
                    string $uname, 
                    string $password, 
                    string $hte, 
                    mysqli $conn) : bool {
        try {
            $date = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $now = $date->format('Y-m-d H:i:s');
            $query = 'INSERT INTO admin_user_account(fullname,
                                                     username,
                                                     password,
                                                     hte,
                                                     created_at)
                      VALUES(?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('signUp() stmt not prepare well - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('sssss', $fullname, $uname, $password, $hte, $now);
            if(!$stmt){
                throw new Exception('signUp() stmt execution failed - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->close();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // <========== VALIDATE USERNAME ==========>
    function validate(string $uname, mysqli $conn) : bool {
        try {
            $stmt = $conn->prepare('SELECT username FROM admin_user_account
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('validate() stmt not prepare well - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s',$uname);
            if(!$stmt->execute()){
                throw new Exception('validate() stmt not executed - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $result = $stmt->get_result();
            if($result->num_rows < 0){
                $stmt->close();
                return false;
            }else{
                $stmt->close();
                return true;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>