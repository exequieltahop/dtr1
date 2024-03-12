<?php
    // $numberOfDays = [
    //     31,
    //     28,
    //     31,
    //     30,
    //     31,
    //     30,
    //     31,
    //     31,
    //     30,
    //     31,
    //     30,
    //     31
    // ];
    // for($i = 0; $i < count($numberOfDays); $i++){
    //     if($i == (4 - 1)){
    //         for($j = 1; $j <= $numberOfDays[$i]; $j++){
    //             echo $j.'<br>';
    //         }
    //     }
    // }
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    // Example usage:
    $string1 = '{"key":"value"}'; // Valid JSON encoded string
    $string2 = '{"key":"value"'; // Invalid JSON encoded string
    
    echo "String 1 is " . (isJson($string1) ? "JSON encoded" : "not JSON encoded") . PHP_EOL;
    echo "String 2 is " . (isJson($string2) ? "JSON encoded" : "not JSON encoded") . PHP_EOL;
?>
