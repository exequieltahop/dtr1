<?php 
    // <========== SESSION START ==========>
    session_start();
    // <========== INCLUDE DATABASE CONNECTION ==========>
    include_once '../../dbcon/conn.php';
    // <========== MAIN ==========>
    try {
        // <========== CHECK SERVER REQUEST METHOD IF NOT GET THROW AN EXCEPTION ==========>
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET!');
        } 
        
        // <========== GET STUDENT ID in GET VARIALBLE ==========>
        $studentid = urldecode($_GET['id']);
        // <========== GET STUDENT FULLNAME ==========>
        $studentFullname = studentFullname($studentid, $conn);
        if($studentFullname == ''){
            header('Content-Type:application/json');
            echo json_encode(['data' => '']);
        }else{
            $data = getSched($studentFullname, $studentid, $conn);
            header('Content-Type:application/json');
            echo json_encode(['data' => $data]);
        }

    } catch (\Throwable $th) {// <========== CATCH THROWABLE ==========>
        header('Content-Type:application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {// <========== FINALLY IF DBCONNECTION WAS OPEN THEN CLOSE IT ==========>
        if(isset($conn)){
            $conn->close();
        }
    }
    // <========== GET OJT DTR DATA ==========>
    function getSched(string $fullname, string $studentId, mysqli $conn) : string {
        try {
            $return = '';
            $rawNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $finalizeNow = $rawNow->format('n');
            $month = intval($finalizeNow);
            // $month = getMonths($fullname, $studentId, $conn);
            // number of days in each month
            $numberOfDays = [
                31,
                29,
                31,
                30,
                31,
                30,
                31,
                31,
                30,
                31,
                30,
                31
            ];
            $newMonth = $month;//month
            $daysInTheDTR = getDays($fullname, $studentId, $newMonth, $conn);//days in array
            $strvalMonth = strval($newMonth);//convert int month to string
            // loop the days
            for($i = 0; $i < count($numberOfDays); $i++){
                if($i == $newMonth - 1){
                    for($j = 1; $j <= $numberOfDays[$i]; $j++){
                        if(in_array($j, $daysInTheDTR)){
                            // am
                            $am = getSchedPerDayAm($strvalMonth, 
                                                   $fullname, 
                                                   $studentId, 
                                                   $j, 
                                                   $conn);
                            // pm
                            $pm = getSchedPerDayPm($strvalMonth, 
                                                   $fullname, 
                                                   $studentId, 
                                                   $j, 
                                                   $conn);
                            // concat return value
                            $return .= '<tr class="tr-body">
                                            <td class="td-body">'.htmlspecialchars($j,ENT_QUOTES, 'UTF-8').'</td>
                                            '.$am.'
                                            '.$pm.'
                                            <td class="td-body"></td>
                                            <td class="td-body"></td>
                                        </tr>';
                        }else{
                            $return .= '<tr class="tr-body">
                                            <td class="td-body">'.htmlspecialchars($j,ENT_QUOTES, 'UTF-8').'</td>
                                            <td class="td-body"></td>
                                            <td class="td-body"></td>
                                            <td class="td-body"></td>
                                            <td class="td-body"></td>
                                            <td class="td-body"></td>
                                        </tr>';
                        }
                    }
                }
            }
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // get month
    function getMonths(string $fullname, string $studentId, mysqli $conn) : array {
        try {
            $return = [];
            $stmt = $conn->prepare('SELECT date FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    ORDER BY date ASC');
            if(!$stmt){
                throw new Exception('getMonths() stmt not prepared - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ss', $fullname, $studentId);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows < 1){
                $stmt->close();
                return [];
            }else{
                while($row = $res->fetch_assoc()){
                    $newDate = new DateTime($row['date']);
                    $month = $newDate->format('m');
                    $return[] = $month;
                }
                return $return;
            }
        } catch (\Exception $th) {
            throw $th;
        }
    }
    // get days in the month
    function getDays(string $fullname, 
                     string $studentId, 
                     int $month, 
                     mysqli $conn) : array {
        try {
            $stringMonth = strval($month);
            $return = [];
            $stmt = $conn->prepare('SELECT DISTINCT(date) AS days FROM time_in_out
                                    WHERE fullname = ?
                                    AND student_id = ?
                                    AND MONTH(date) = ?');
            if(!$stmt){
                throw new Exception('getDays() stmt not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('sss',$fullname, $studentId, $stringMonth);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows < 1){
                $stmt->close();
                return [];
            }else{
                while($row = $res->fetch_assoc()){
                    $newDate = new DateTime($row['days']);
                    $day = $newDate->format('d');
                    $return[] = $day;
                }
                $stmt->close();
                return $return;
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
    // fetch sched per day
    function getSchedPerDayAm(string $month, 
                            string $fullname, 
                            string $studentId, 
                            int $day, 
                            mysqli $conn) : string {
        try {
            $return = '';
            $newDay = strval($day);
            $stmt = $conn->prepare('SELECT DISTINCT time_in, time_out FROM time_in_out
                                    WHERE MONTH(date) = ?
                                    AND DAY(date) = ?
                                    AND fullname = ?
                                    AND student_id = ?
                                    AND meridiem = "AM"');
            if(!$stmt){
                throw new Exception('getSchedPerDay() stmt is not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssss', $month, 
                                      $newDay, 
                                      $fullname,
                                      $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                $return .= '<td class="td-body"></td>
                            <td class="td-body"></td>
                            ';
            }else{
                while($row = $result->fetch_assoc()){
                   // conditioning time in
                   if($row['time_in'] == ''){
                        $formattedIn = '';
                    }else{
                        $newIn = new DateTime($row['time_in']);
                        $formattedIn = $newIn->format('h:i');
                    }
                    // conditioning time out
                    if($row['time_out'] == ''){
                        $formattedOut = '';
                    }else{
                        $newOut = new DateTime($row['time_out']);
                        $formattedOut = $newOut->format('h:i');
                    }
                    $return .= '<td class="td-body">'.htmlspecialchars($formattedIn,ENT_QUOTES, 'UTF-8').'</td>
                                <td class="td-body">'.htmlspecialchars($formattedOut,ENT_QUOTES, 'UTF-8').'</td>
                                ';
                }
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // pm shedule
    function getSchedPerDayPm(string $month, 
                            string $fullname, 
                            string $studentId, 
                            int $day, 
                            mysqli $conn) : string {
        try {
            $return = '';
            $newDay = strval($day);
            $stmt = $conn->prepare('SELECT time_in, time_out FROM time_in_out
                                    WHERE MONTH(date) = ?
                                    AND DAY(date) = ?
                                    AND fullname = ?
                                    AND student_id = ?
                                    AND meridiem = "PM"');
            if(!$stmt){
                throw new Exception('getSchedPerDay() stmt is not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssss', $month, 
                                      $newDay, 
                                      $fullname,
                                      $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                $return .= '<td class="td-body"></td>
                            <td class="td-body"></td>
                            ';
            }else{
                while($row = $result->fetch_assoc()){
                    // conditioning time in
                    if($row['time_in'] == ''){
                        $formattedIn = '';
                    }else{
                        $newIn = new DateTime($row['time_in']);
                        $formattedIn = $newIn->format('h:i');
                    }
                    // conditioning time out
                    if($row['time_out'] == ''){
                        $formattedOut = '';
                    }else{
                        $newOut = new DateTime($row['time_out']);
                        $formattedOut = $newOut->format('h:i');
                    }
                    $return .= '<td class="td-body">'.htmlspecialchars($formattedIn,ENT_QUOTES, 'UTF-8').'</td>
                                <td class="td-body">'.htmlspecialchars($formattedOut,ENT_QUOTES, 'UTF-8').'</td>
                                ';
                }
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // <========== GET FULLNAME OF THE OJT ==========>
    function studentFullname(string $studentid, mysqli $conn) : string{
        try {
            $return = '';
            // <========== QUERY ==========>
            $query = 'SELECT Full_Name FROM users 
                      WHERE BINARY StudentID = ?';
            // <========== PREPARED QUERY ==========>
            $stmt = $conn->prepare($query);
            // <========== CHECK IF STMT IS CORRECT ==========>
            if(!$stmt){
                throw new Exception('studentFullname() stmt not prepared correctly!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            // <========== CHECK IF STMT PLACEHOLDER IS NOT BINDED CORRECTLY ==========>
            if(!$stmt->bind_param('s', $studentid)){
                throw new Exception('studentFullname() stmt placeholder not binded!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            // <========== CHECK IF STMT WAS EXECUTING ==========>
            if(!$stmt->execute()){
                throw new Exception('studentFullname() stmt failed to execute!/ '
                                    .$conn->errno.'/',$conn->error);
            }
            // <========== GET RESULT FROM QUERY ==========>
            $result = $stmt->get_result();
            // <========== CHECK IF RESULT ROWS WAS 0, IF 0 THEN RETURN '' ==========>
            if($result->num_rows < 1){
                $return .= '';
            }
             // <========== IF RESULT WAS NOT EMPTY THEN PASS IT TO THE $row VARIABLE THEN RETURN IT ==========>
            if($row = $result->fetch_assoc()){
                $return .= $row['Full_Name'];
            }
            // <========== CLOSE STMT AND RETURN THE STRING ==========>
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>