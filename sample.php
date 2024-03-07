<?php
    $numberOfDays = [
        31,
        28,
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
    for($i = 0; $i < count($numberOfDays); $i++){
        if($i == (4 - 1)){
            for($j = 1; $j <= $numberOfDays[$i]; $j++){
                echo $j.'<br>';
            }
        }
    }
?>