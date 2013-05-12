<?php

	function setQuestionOrder($qID, $qOrder) {
		include 'function/settings.php';
		$con = new mysqli($host, $username, $password, $db, $port);
		$query = $con->prepare("UPDATE questions SET question_order=? WHERE question_id=?");
		$query->bind_param('ii', $qOrder, $qID);
		$query->execute();
		//mysqli_query($con,"UPDATE questions SET question_order = '$qOrder' WHERE question_id = '$qID'");
		mysqli_close($con);
	}

	function addNewQuestion($qText, $qType, $qNotes) {
		include 'function/settings.php';
		$con = new mysqli($host, $username, $password, $db, $port);
		//mysqli_query($con,"INSERT INTO questions (question_text, question_type, question_notes) VALUES ('$qText', '$qType', '$qNotes')");
		$query = $con->prepare("INSERT INTO questions (question_text, question_type, question_notes) VALUES (?, ?, ?)");
		$query->bind_param('sis', $qText, $qType, $qNotes);
		$query->execute();
		mysqli_close($con);
	}

	function deleteQuestion($qID) {
		include 'function/settings.php';
		$con = new mysqli($host, $username, $password, $db, $port);
		$query = $con->prepare("DELETE FROM questions WHERE question_id=?");
		$query->bind_param('i', $qID);
		$query->execute();
		$query = $con->prepare("DELETE FROM answers WHERE question_id=?");
		$query->bind_param('i', $qID);
		$query->execute();
		//mysqli_query($con,"DELETE FROM questions WHERE question_id='$qID'");
		//mysqli_query($con,"DELETE FROM answers WHERE question_id='$qID'");
		mysqli_close($con);
	}

	function addNewAnswer($aText, $qID) {
		include 'function/settings.php';
		$con = new mysqli($host, $username, $password, $db, $port);
		//mysqli_query($con,"INSERT INTO answers (question_id, answer_text) VALUES ('$qID', '$aText')");
		$query = $con->prepare("INSERT INTO answers (question_id, answer_text) VALUES (?, ?)");
		$query->bind_param('is', $qID, $aText);
		$query->execute();
		mysqli_close($con);
	}

	function deleteAnswer($aID) {
		include 'function/settings.php';
		$con = new mysqli($host, $username, $password, $db, $port);
		//mysqli_query($con,"DELETE FROM answers WHERE answer_id='$aID'");
		$query = $con->prepare("DELETE FROM answers WHERE answer_id=?");
		$query->bind_param('i', $aID);
		$query->execute();
		mysqli_close($con);
	}
?>