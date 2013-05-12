<?php
    include 'function/data.php';

    $json = file_get_contents('php://input');
    $obj = json_decode($json);
    if ($obj === FALSE) {
         throw new Exception('Bad JSON format.');
    }    
    $answers = $obj->{'answers'};
    echo saveData($answers);
?>