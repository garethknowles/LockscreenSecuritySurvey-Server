<?php
    function guid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                    .substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12)
                    .chr(125);// "}"
            return $uuid;
        }
    }

    function saveReponse($user_id, $question_id, $answer_id, $answer_string) {
        include 'function/settings.php';

        $con = new mysqli($host, $username, $password, $db, $port);
        if ($answer_id > 0) {    
            $query = $con->prepare("INSERT INTO data (question_id, answer_id, answer_string, user_id) VALUES (?, ?, ?, ?)");
            $query->bind_param('iiss', $question_id, $answer_id, $answer_string, $user_id);
            //$sql="INSERT INTO data (question_id, answer_id, answer_string, user_id) VALUES ('$question_id', '$answer_id', '$answer_string', '$user_id')";
        } else {
            $query = $con->prepare("INSERT INTO data (question_id, answer_string, user_id) VALUES (?, ?, ?)");
            $query->bind_param('iss', $question_id, $answer_string, $user_id);
            //$sql="INSERT INTO data (question_id, answer_string, user_id) VALUES ('$question_id', '$answer_string', '$user_id')";
        }
        if (!$query->execute()) {
            die('Error: ' . mysqli_error());
        }
        mysqli_close($con);
    }

    function saveData($answers) {

        $user_id = guid();
        foreach ($answers as $answer) {
            $qID = $answer->{'question_id'};
            $aID = $answer->{'answer_id'};
            $aString = $answer->{'answer_string'};
            saveReponse($user_id, $qID, $aID, $aString);
        }
        return 1;
    }

    function getQuestions() {
        include 'function/settings.php';

        $jsonData = array();

        $con = new mysqli($host, $username, $password, $db, $port);
        $questions = mysqli_query($con,"SELECT * FROM questions WHERE question_order >= 0 ORDER BY question_order");

        while ($row = mysqli_fetch_array($questions)) {
            $question = array(
                'question_id'       =>$row['question_id'], 
                'question_type'     =>$row['question_type'], 
                'question_text'     =>$row['question_text'], 
                'question_order'    =>$row['question_order'],
                'question_notes'    =>$row['question_notes']);
            $qID = $row['question_id'];
            $answers = mysqli_query($con,"SELECT * FROM answers WHERE question_id = '$qID'");
            $answersData = array();
            while ($answer = mysqli_fetch_array($answers)) {
                $answerData = array(
                'answer_id'     =>$answer['answer_id'],
                'answer_text'   =>$answer['answer_text']);
                array_push($answersData, $answerData);
            }

            $questionData = array('question'=>$question, 'answers'=>$answersData);
            array_push($jsonData, $questionData);
        }   

        mysqli_close($con);

        return json_encode($jsonData);
    }
?>