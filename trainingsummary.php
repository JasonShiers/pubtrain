<?php

	// configuration
	require("includes/config.php"); 
	
	if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_GET["admin"]) && $_GET["admin"] == 1)
		{
			$trainingopts = query("SELECT trainingid, type FROM traininglibrary ORDER BY type ASC");
		}
		else
		{
			$trainingopts = query("SELECT l.trainingid, l.type FROM traininglibrary l, trainingsuperusers s 
				WHERE s.userid = ? AND l.trainingid = s.trainingid ORDER BY type ASC");
		}
		
		$depts = query("SELECT department, depmask from departments");
		
		// render table
		render("templates/trainingsummary.php", ["trainingopts" => $trainingopts, "depts" => $depts, "title" => "Training Summary"]);
	}
	else if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		// validate submission
		if (!isset($_POST["trainingid"]))
		{
			apologize("Error: No training type submitted");
		}
		else if($_SESSION["admin"] == 0 || !isset($_POST["admin"]) || $_POST["admin"] == 0)
		{
			$trainingopts = query("SELECT l.trainingid, l.type FROM traininglibrary l, trainingsuperusers s 
				WHERE s.userid = ? AND l.trainingid = s.trainingid ORDER BY type ASC");
			if (array_search($_POST["trainingid"], array_column($trainingopts, "id")) === FALSE)
			{
				apologize("Error: You are not a super user for this type of training");
			}
		}
		else
		{
			$trainingopts = query("SELECT trainingid, type FROM traininglibrary ORDER BY type ASC");
		}
		
		$depmask = 0;
		if(isset($_POST["departments"]))
		{
			foreach ($_POST["departments"] AS $department)
			{
				$depmask |= $department;
			}
		}

		// get verified users
		$verified = query("SELECT t.recordid, u.firstname, u.lastname, u.userid, COUNT(u.userid) AS count FROM users u, trainingrecords t, departments d 
			WHERE u.userid = t.userid AND t.verified = 1 AND t.trainingid = ? AND t.description LIKE ? 
			AND t.date > ? AND t.date < ? AND u.department = d.department AND d.depmask | ?
			GROUP BY u.userid ORDER BY u.lastname, u.firstname", $_POST["trainingid"], 
			"%" . $_POST["description"] . "%", $_POST["startdate"], $_POST["enddate"], $depmask);
		
		// get users with record
		$unverified = query("SELECT t.recordid, u.firstname, u.lastname, u.userid, COUNT(u.userid) AS count,
			CASE confirmed
				WHEN 1 THEN 1
				WHEN NULL THEN 1
				WHEN 0 THEN 0
			END AS confirmed 
			FROM users u, trainingrecords t, departments d 
			WHERE u.userid = t.userid AND t.verified = 0 AND t.trainingid = ? AND t.description LIKE ? 
			AND t.date > ? AND t.date < ? AND u.department = d.department AND d.depmask & ? 
			GROUP BY u.userid ORDER BY u.lastname, u.firstname", $_POST["trainingid"], "%" . $_POST["description"] . "%", 
			$_POST["startdate"], $_POST["enddate"], $depmask);

		// get unverified, unconfirmed users
		$unconfirmed = query("SELECT u.firstname, u.lastname, u.userid FROM users u, departments d WHERE u.department = d.department AND d.depmask & ?
		AND NOT EXISTS (
				SELECT 1 FROM trainingrecords t WHERE u.userid = t.userid 
				AND t.trainingid = ? AND t.description LIKE ? AND t.date > ? AND t.date < ?
			) ORDER BY u.lastname, u.firstname", $depmask, $_POST["trainingid"], "%" . $_POST["description"] . "%", 
			$_POST["startdate"], $_POST["enddate"]);
	
		$depts = query("SELECT department, depmask from departments");
			
		// render table
		render("templates/trainingsummary.php", ["trainingopts" => $trainingopts, "verified" => $verified, "unverified" => $unverified,
			"unconfirmed" => $unconfirmed, "trainingid" => $_POST["trainingid"], "startdate" => $_POST["startdate"], "enddate" => $_POST["enddate"], 
			"description" => $_POST["description"], "depts" => $depts, "departments" => $_POST["departments"], 
			"admin" => (isset($_POST["admin"])?$_POST["admin"]:0), "title" => "Training Summary"]);
	}
?>