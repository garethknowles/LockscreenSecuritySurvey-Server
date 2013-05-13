<!DOCTYPE html>

<html>
    <head>
      <title>Lockscreen Survey Question Data</title>
    </head>
    <body>
        <?php 
          require 'function/settings.php';
          include 'function/questions.php';
          if (isset($_POST['aText'])) {
              addNewAnswer($_POST['aText'], $_POST['qID']);
          }
          else if (isset($_POST['qOrder'])) {
              setQuestionOrder($_POST['qID'], $_POST['qOrder']);
          }
          else if (isset($_POST['qID'])) {
              deleteQuestion($_POST['qID']);
          }
          else if (isset($_POST['aID'])) {
              deleteAnswer($_POST['aID']);
          }
          else if (isset($_POST['qText'])) {
              addNewQuestion($_POST['qText'], $_POST['qType'], $_POST['qNotes']);
          }
        ?>
        <h1>LOCKSCREEN SURVEY QUESTION DATA ON SERVER</h1>
        <?php

        $con = new mysqli($host, $username, $password, $db, $port);

        // QUESTION TYPES
        $types = mysqli_query($con,"SELECT * FROM question_types");

        echo "<h2>QUESTION TYPES</h2>";
        echo "<table border='1'>
        <tr>
        <th>Type ID</th>
        <th>Type Name</th>
        <th>Type Notes</th>
        </tr>";

        while($row = mysqli_fetch_array($types))
          {
          echo "<tr>";
          echo "<td>" . $row['type_id'] . "</td>";
          echo "<td>" . $row['type_name'] . "</td>";
          echo "<td>" . $row['type_notes'] . "</td>";
          echo "</tr>";
          }
        echo "</table>";

        // QUESTIONS
        $questions = mysqli_query($con,"SELECT * FROM questions");
        echo "<h2>QUESTIONS</h2>";
        echo "<table border='1'>
        <tr>
        <th>Question ID</th>
        <th>Question Text</th>
        <th>Question Type</th>
        <th>Question Notes</th>
        <th>Question Order</th>
        <th>Controls</th>
        </tr>";

        while($row = mysqli_fetch_array($questions))
          {
          echo "<tr>";
          echo "<td>" . $row['question_id'] . "</td>";
          echo "<td>" . $row['question_text'] . "</td>";
          echo "<td>" . $row['question_type'] . "</td>";
          echo "<td>" . $row['question_notes'] . "</td>";
          echo "<td>" . $row['question_order'] . "</td>";
          echo "<td>" . "<form id = \"deleteQuesiton\" action=\"?deleteQuestion\" method=\"post\">
          <input type=\"hidden\" name=\"qID\" value=\"" . $row['question_id'] . "\"><input type=\"submit\" value=\"Delete\"></form>" . "</td>";
          echo "</tr>";
          }
        echo "</table>";

        // ANSWERS
        $answers = mysqli_query($con,"SELECT * FROM answers");
        echo "<h2>ANSWERS</h2>";
        echo "<table border='1'>
        <tr>
        <th>Answer ID</th>
        <th>Question ID</th>
        <th>Answer Text</th>
        <th>Controls</th>
        </tr>";

        $types = mysqli_query($con,"SELECT * FROM question_types");
        while($row = mysqli_fetch_array($answers))
          {
          echo "<tr>";
          echo "<td>" . $row['answer_id'] . "</td>";
          echo "<td>" . $row['question_id'] . "</td>";
          echo "<td>" . $row['answer_text'] . "</td>";
          echo "<td>" . "<form id = \"deleteAnswer\" action=\"?deleteAnswer\" method=\"post\">
          <input type=\"hidden\" name=\"aID\" value=\"" . $row['answer_id'] . "\"><input type=\"submit\" value=\"Delete\"></form>" . "</td>";
          echo "</tr>";
          }
        echo "</table>";

        ?> 

        <h1>Add New Question</h1>
        <form id = "addNewQuestion" action="?newQuestion" method="post">
          Question Text: <input type="text" name="qText">
          Question Type: 
          <select name="qType">
          <?php
            $types = mysqli_query($con,"SELECT * FROM question_types");
            while ($row = mysqli_fetch_array($types)) {
              echo "<option value=\"" . $row['type_id'] . "\">" . $row['type_name'] . "</option>";
            }
          ?>
          </select>
          Question Notes: <input type = "text" name = "qNotes">
          <input type="submit" value = "Add Question">
        </form> 

        <h1>Add New Answer</h1>
        <form id = "addNewAnswer" action="?newAnswer" method="post">
          Answer Text: <input type="text" name="aText">
          Question: 
          <select name="qID">
          <?php
            $questions = mysqli_query($con,"SELECT * FROM questions");
            while ($row = mysqli_fetch_array($questions)) {
              echo "<option value=\"" . $row['question_id'] . "\">" . $row['question_text'] . "</option>";
            }
          ?>
          </select>
          <input type="submit" value = "Add Answer">
        </form> 

        <h1>Change Question Order</h1>
        <form id = "changeOrder" action="?changeOrder" method="post">
          Order Value: <input type="text" name="qOrder">
          Question: 
          <select name="qID">
          <?php
            $questions = mysqli_query($con,"SELECT * FROM questions");
            while ($row = mysqli_fetch_array($questions)) {
              echo "<option value=\"" . $row['question_id'] . "\">" . $row['question_text'] . "</option>";
            }
          ?>
          </select>
          <input type="submit" value = "Change Order">
        </form> 


    </body>
</html>