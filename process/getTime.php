<?php 
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request not GET');
        }
        $now = getTime();
        header('Content-Type: application/json');
        echo json_encode(['dataTime'=>$now]);
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } 
    // get cur time
    function getTime() : string {
        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $finalTime = $now->format('h:i:s A');
        return $finalTime;
    }
?>