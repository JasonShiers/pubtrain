<?php

	// configuration
	require("includes/config.php"); 
	
	if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET["trainingid"]))
	{
		if(isset($_GET["admin"]) && $_GET["admin"] == 1)
		{
			$traintypes = query("SELECT trainingid, type FROM traininglibrary ORDER BY type ASC");
		}
		else
		{
			$traintypes = query("SELECT l.trainingid, l.type FROM traininglibrary l, trainingsuperusers s 
				WHERE s.userid = ? AND l.trainingid = s.trainingid ORDER BY type ASC", $_SESSION["userid"]);
		}
		
		$depts = query("SELECT department, depmask from departments");
		
		// render table
		render("templates/trainingsummary.php", ["traintypes" => $traintypes, "depts" => $depts, "title" => "Training Summary"]);
	}
	else
	{
		// validate submission
		if (isset($_POST["trainingid"]))
		{
			$trainingid = $_POST["trainingid"];
		}
		else if (isset($_GET["trainingid"]))
		{
			$trainingid = $_GET["trainingid"];
		}
		else
		{
			apologize("Error: No training type submitted");
		}
		
		if ($_SESSION["admin"] == 0 || !isset($_POST["admin"]) || $_POST["admin"] == 0)
		{
			$traintypes = query("SELECT l.trainingid, l.type FROM traininglibrary l, trainingsuperusers s 
				WHERE s.userid = ? AND l.trainingid = s.trainingid ORDER BY type ASC", $_SESSION["userid"]);

			if (array_search($trainingid, array_column($traintypes, "trainingid")) === FALSE)
			{
				apologize("Error: You are not a super user for this type of training");
			}
		}
		else
		{
			$traintypes = query("SELECT trainingid, type FROM traininglibrary ORDER BY type ASC");
		}
		
		$depmask = 0;
		if (isset($_POST["departments"]))
		{
			$departments = $_POST["departments"];
			foreach ($_POST["departments"] AS $department)
			{
				$depmask |= $department;
			}
		}
		else if (isset($_GET["depmask"]))
		{
			$depmask = $_GET["depmask"];
		}
		
		if (isset($_POST["startdate"]))
		{
			$startdate = $_POST["startdate"];
			$enddate = $_POST["enddate"];
			$description = $_POST["description"];
		}
		else if (isset($_GET["startdate"]) && isset($_GET["enddate"]))
		{
			$startdate = $_GET["startdate"];
			$enddate = $_GET["enddate"];
			$description = isset($_GET["description"])?$_GET["description"]:"";
		}

		// get verified users
		$verified = query("SELECT t.recordid, u.firstname, u.lastname, u.userid, COUNT(u.userid) AS count FROM users u, trainingrecords t, departments d 
			WHERE u.userid = t.userid AND t.verified = 1 AND t.trainingid = ? AND t.description LIKE ? 
			AND t.date > ? AND t.date < ? AND u.department = d.department AND d.depmask | ?
			GROUP BY u.userid ORDER BY u.lastname, u.firstname", $trainingid, 
			"%" . $description . "%", $startdate, $enddate, $depmask);
		
		// get users with record
		$unverified = query("SELECT t.recordid, u.firstname, u.lastname, u.userid, COUNT(u.userid) AS count,
			IF(confirmed IS NULL || confirmed=1, 1, 0) AS confirmed 
			FROM users u, trainingrecords t, departments d 
			WHERE u.userid = t.userid AND t.verified = 0 AND t.trainingid = ? AND t.description LIKE ? 
			AND t.date > ? AND t.date < ? AND u.department = d.department AND d.depmask & ? 
			GROUP BY u.userid ORDER BY u.lastname, u.firstname", $trainingid, "%" . $description . "%", 
			$startdate, $enddate, $depmask);

		// get unverified, unconfirmed users
		$unconfirmed = query("SELECT u.firstname, u.lastname, u.userid FROM users u, departments d WHERE u.department = d.department AND d.depmask & ?
		AND NOT EXISTS (
				SELECT 1 FROM trainingrecords t WHERE u.userid = t.userid 
				AND t.trainingid = ? AND t.description LIKE ? AND t.date > ? AND t.date < ?
			) ORDER BY u.lastname, u.firstname", $depmask, $trainingid, "%" . $description . "%", 
			$startdate, $enddate);
	
		$depts = query("SELECT department, depmask from departments");
		
		if (!isset($_POST["departments"]))
		{
			$departments = [];
			foreach ($depts as $dept)
			{
				if (($dept["depmask"] & $depmask) !== 0)
				{

					array_push($departments, $dept["depmask"]);
				}
			}
		}

		// render table
		render("templates/trainingsummary.php", ["traintypes" => $traintypes, "verified" => $verified, "unverified" => $unverified,
			"unconfirmed" => $unconfirmed, "trainingid" => $trainingid, "startdate" => $startdate, "enddate" => $enddate, 
			"description" => $description, "depts" => $depts, "departments" => $departments, "depmask" => $depmask, 
			"admin" => (isset($_POST["admin"])?$_POST["admin"]:0), "title" => "Training Summary"]);
	}
?>
