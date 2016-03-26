<?php

	// configuration
	require("includes/config.php"); 

	
	if (isset($_GET["type"]))
	{
		if ($_GET["type"] == "newConf")
		{
			if (isset($_POST["confdate"]) && isset($_POST["title"]))
			{
				$query = "INSERT INTO suppconfrecords (userid, date, title, location, days, confirmed) VALUES (?, ?, ?, ?, ?, 1)";
				$success = query($query, $_SESSION["userid"], date("Y-m-d", strtotime($_POST["confdate"])), $_POST["title"],
					$_POST["location"], $_POST["days"]);

				if ($success === false)
				{
					apologize("Can't update database.");
				}
				else
				{
					if (isset($_POST["otherusers"]) && count($_POST["otherusers"]) > 0)
					{
						foreach ($_POST["otherusers"] as $otheruser)
						{
							$query = "	INSERT INTO suppconfrecords (userid, date, title, location, days, confirmed) 
										VALUES (?, ?, ?, ?, ?, 0) ON DUPLICATE KEY UPDATE id=id";
							$success = query($query, $otheruser, date("Y-m-d", strtotime($_POST["confdate"])), 
								$_POST["title"], $_POST["location"], $_POST["days"]);

							if ($success === false)
							{
								apologize("Added your record. Can't add for one or more of the other attendees.");
							}
						}
					}
					$url = "index.php";
					if (isset($_GET["page"]) && $_GET["page"] > 1)
					{
						$url .= "?page=" . $_GET["page"];
					}
					redirect($url);
				}
			}
		}
		else if ($_GET["type"] == "newTrain")
		{
			if (isset($_POST["traindate"]) && isset($_POST["trainingid"]) && isset($_POST["days"]))
			{
				if($_POST["internal_trainer"]==2)
				{
					$_POST["internal_trainer"]=NULL;
				}
				$query = "INSERT INTO trainingrecords (userid, date, trainingid, description, total_days, 
					internal_location, internal_trainer, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
				$success = query($query, $_SESSION["userid"], date("Y-m-d", strtotime($_POST["traindate"])), $_POST["trainingid"], 
					$_POST["description"], $_POST["days"], $_POST["internal_location"], $_POST["internal_trainer"]);

				if ($success === false)
				{
					apologize("Can't update database.");
				}
				else
				{
					if (isset($_POST["otherusers"]) && count($_POST["otherusers"]) > 0)
					{
						foreach ($_POST["otherusers"] as $otheruser)
						{
							$query = "	INSERT INTO trainingrecords (userid, date, trainingid, description, total_days, 
								internal_location, internal_trainer, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?, 0) 
								ON DUPLICATE KEY UPDATE recordid=recordid";
							$success = query($query, $otheruser, date("Y-m-d", strtotime($_POST["traindate"])), 
								$_POST["trainingid"], $_POST["description"], $_POST["days"], 
								$_POST["internal_location"], $_POST["internal_trainer"]);

							if ($success === false)
							{
								apologize("Added your record. Can't add for one or more of the other attendees.");
							}
						}
					}
				}
				$url = "index.php";
				if (isset($_GET["page"]) && $_GET["page"] > 1)
				{
					$url .= "?page=" . $_GET["page"];
				}
				redirect($url . "#collapseTrainingHistory");
			}
		}
		else if ($_GET["type"] == "delConf")
		{
			if (isset($_GET["id"]))
			{
				$success = query("DELETE FROM suppconfrecords WHERE id=? AND userid=?", $_GET["id"], $_SESSION["userid"]);
			}
			if ($success === false)
			{
				apologize("Unable to delete this record.");
			}
			$url = "index.php";
			if (isset($_GET["page"]) && $_GET["page"] > 1)
			{
				$url .= "?page=" . $_GET["page"];
			}
			redirect($url);
		}
		else if ($_GET["type"] == "delTrain")
		{
			if (isset($_GET["id"]))
			{
				$success = query("DELETE FROM trainingrecords WHERE recordid=? AND userid=?", $_GET["id"], $_SESSION["userid"]);
			}
			if ($success === false)
			{
				apologize("Unable to delete this record.");
			}
			$url = "index.php";
			if (isset($_GET["page"]) && $_GET["page"] > 1)
			{
				$url .= "?page=" . $_GET["page"];
			}
			redirect($url . "#collapseTrainingHistory");
		}
		else if ($_GET["type"] == "confirmConf")
		{
			if (isset($_GET["id"]))
			{
				$success = query("UPDATE suppconfrecords SET confirmed=1 WHERE id=? AND userid=?", $_GET["id"], $_SESSION["userid"]);
			}
			if ($success === false)
			{
				apologize("Unable to confirm this record.");
			}
			$url = "index.php";
			if (isset($_GET["page"]) && $_GET["page"] > 1)
			{
				$url .= "?page=" . $_GET["page"];
			}
			redirect($url);
		}
		else if ($_GET["type"] == "confirmTrain")
		{
			if (isset($_GET["id"]))
			{
				$success = query("UPDATE trainingrecords SET confirmed=1 WHERE recordid=? AND userid=?", $_GET["id"], $_SESSION["userid"]);
			}
			if ($success === false)
			{
				apologize("Unable to confirm this record.");
			}
			$url = "index.php";
			if (isset($_GET["page"]) && $_GET["page"] > 1)
			{
				$url .= "?page=" . $_GET["page"];
			}
			redirect($url . "#collapseTrainingHistory");
		}
	}
?>
