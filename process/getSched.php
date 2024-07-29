<?php
    session_start();
    include_once '../dbcon/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Method Not GET');
        }else{
            // get data
            $data = getSched($_SESSION['full_name'], $_SESSION['studentID'], $conn);
            $rawNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $finalizeNow = $rawNow->format('m');     
            // TOTAL HOURS
            $totalHours = getTotalHours($_SESSION['full_name'], 
                                        $_SESSION['studentID'],
                                        $finalizeNow,
                                        $conn);
            // HTE ADVISER
            $adviser = hteAdviser($_SESSION['studentID'], $conn);
            // GIVE RESPONSE
            header('Content-Type: application/json');
            echo json_encode([
                              'data' => $data, 
                              'totalHours' => $totalHours,
                              'hteAdviser' => $adviser
                            ]);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err' => $th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // get time in and out
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
    // get total hours
    function getTotalHours(string $fullname, 
                           string $studentId,
                           string $month,
                           mysqli $conn) {
        try {
            $intvalMonth = $month;
            $return = '';
            $stmt  = $conn->prepare('SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS totalHours
                                     FROM time_in_out
                                     WHERE student_id = ?
                                     AND month(date) = ?
                                     AND time_in IS NOT NULL
                                     AND time_out IS NOT NULL');
            if(!$stmt){
                throw new Exception('getTotalHours() stmt not prepare - '.$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ss', $studentId, $intvalMonth);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                if($row = $result->fetch_assoc()){
                    if($row['totalHours'] == NULL){
                        $return .= '0 Hours';
                    }else{
                        $rawHour = str_replace(":", ".",substr($row['totalHours'], 0 , 5));
                        $return .= $rawHour.' Hours';
                    }
                }
            }else{
                $return .= '0 Hours';
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // GET HTE ADVISER
    function hteAdviser(string $uname, mysqli $conn) : string {
        try {
            $query = 'SELECT hte_adivser FROM users
                      WHERE StudentID = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('hteAdviser() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $uname);
            if(!$stmt->execute()){
                throw new Exception('hteAdviser() stmt not executed! - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                throw new Exception('hteAdviser() row result 0');
            }else{
                if($row = $result->fetch_assoc()){
                    $return = $row['hte_adivser'];
                }
            }
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>