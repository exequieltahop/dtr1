<?php
    // db connection
    include_once '../dbcon/conn.php';
    // main
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Not POST');
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $fullname = $data['fullname'] ?? NULL;
        $studentId = $data['studentId'] ?? NULL;
        $hte = $data['hte'] ?? NULL;
        $password = $data['password'] ?? NULL;
        $hteAdviser = $data['hteAdviser'] ?? NULL;

        if($fullname === NULL ||
           $studentId === NULL ||
           $hte === NULL ||
           $password === NULL ||
           $hteAdviser === NULL){
            throw new Exception('JSON Data Has Null Value');
        }

        $class = new SignUp($conn);
        $signUp = $class->signUp($fullname, $studentId, $hte, $password, $hteAdviser);

        if($signUp === true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'SuccessFully Registered Account!']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // class signUp
    class SignUp{
        private $conn;
        // constructor fn
        public function __construct($conn) {
            $this->conn = $conn;
        }
        // signup acc
        public function signUp(string $fullname,
                               string $studentId,
                               string $hte,
                               string $pass,
                               string $hteAdviser) : bool {
            try {
                $validateUsername = $this->validate($studentId);
                if($validateUsername === true){
                    return false;
                }else{
                    $userType = 'student';
                    $stmt = $this->conn->prepare('INSERT INTO users(StudentID,
                                                                    Pass,
                                                                    Full_Name,
                                                                    Company_Office,
                                                                    hte_adviser,
                                                                    user_type)
                                                  VALUES(?, ?, ?, ?, ?, ?)');
                    if(!$stmt){
                        throw new Exception('signUp() stmt not prepare - '
                                            .$this->conn->errno.'/'.$this->conn->error);
                    }
                    $stmt->bind_param('ssssss', $studentId,
                                              $pass,
                                              $fullname,
                                              $hte,
                                              $hteAdviser,
                                              $userType);
                    $stmt->execute();
                    $stmt->close();
                    return true;

                }
            } catch (Exception $th) {
                throw $th;
            }
        }
        // ACCOUNT VALIDATOR
        function validate(string $studentId) : bool {
            try {
                $query = 'SELECT StudentID FROM users
                          WHERE BINARY StudentID = ?';

                $stmt = $this->conn->prepare($query);

                if(!$stmt){
                    throw new Exception('General Exception: validate() stmt not prepared - '
                                        .$this->conn->errno.'/'.$this->conn->error);
                }

                $stmt->bind_param('s', $studentId);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows > 0) {
                    $stmt->close();
                    return true;
                }else{
                    $stmt->close();
                    return false;
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
?>