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
        if($uname == '' || $password == ''){
            throw new Exception('General Exception: uname or pass json data was empty!');
        }
        $auth = authenticate($uname, $password, $conn);
        switch ($auth) {
            case 'invalid uname':
                header('Content-Type:application/json');
                echo json_encode(['status' => 'Invalid Account!']);
                break;
            case 'not okay':
                header('Content-Type:application/json');
                echo json_encode(['status' => 'Wrong Password!']);
                break;   
            case 'okay':
                header('Content-Type:application/json');
                echo json_encode(['status' => 'Successfully Login!']);
                break; 
            default:
                header('Content-Type:application/json');
                echo json_encode(['status' => 'Something Went Wrong! Try Again!']);
                break;
        }
    } catch (\Throwable $th) {
        header('Content-Type: application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== AUTHENTICATE ==========>
    function authenticate(string $uname, string $pass, mysqli $conn) : string {
        try {  
            $query = 'SELECT * FROM admin_user_account
                      WHERE BINARY username = ?'; 
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('authenticate() stmt not has error - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $pass);
            if(!$stmt->execute()){
                throw new Exception('authenticate() stmt execution failed - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                $stmt->close();
                return 'invalid uname';
            }
            if($row = $result->fetch_assoc()){
                if(password_verify($pass, $row['password'])){
                    $stmt->close();
                    return 'okay';
                }else{
                    $stmt->close();
                    return 'not okay';
                }
            }else{
                throw new Exception('authenticate() Can\'t Retrieve Data - '
                .$conn->errno.'/'.$conn->error);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>