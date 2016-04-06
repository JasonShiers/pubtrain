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
		// render table
		render("templates/trainingsummary.php", ["trainingopts" => $trainingopts, "title" => "Training Summary"]);
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

		// get verified users
		$verified = query("SELECT u.firstname, u.lastname, u.userid FROM users u, trainingrecords t 
			WHERE u.userid = t.userid AND verified = 1 AND trainingid = ? 
			AND date > ? AND date < ?", $_POST["trainingid"], $_POST["startdate"], $_POST["enddate"]);
		
		// get confirmed users
		$unverified = query("SELECT u.firstname, u.lastname, u.userid FROM users u, trainingrecords t 
			WHERE u.userid = t.userid AND (confirmed = 1 OR confirmed IS NULL) AND trainingid = ? 
			AND date > ? AND date < ?", $_POST["trainingid"], $_POST["startdate"], $_POST["enddate"]);

		
		// render table
		render("templates/trainingsummary.php", ["trainingopts" => $trainingopts, "verified" => $verified, "unverified" => $unverified,
			"trainingid" => $_POST["trainingid"], "startdate" => $_POST["startdate"], "enddate" => $_POST["enddate"], 
			"admin" => (isset($_POST["admin"])?$_POST["admin"]:0), "title" => "Training Summary"]);
	}
?>