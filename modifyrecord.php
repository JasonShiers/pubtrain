<?php

	// configuration
	require("includes/config.php"); 

	
	if (isset($_GET["type"]))
	{
		if ($_GET["type"] == "newConf")
		{
			if (isset($_POST["month"]) && isset($_POST["year"]) && isset($_POST["title"]))
			{
				$date = $_POST["year"] . "-" . $_POST["month"] . "-" . "01";
				
				$query = "INSERT INTO suppconfrecords (userid, date, title, location, days, confirmed) VALUES (?, ?, ?, ?, ?, 1)";
				$success = query($query, $_SESSION["userid"], date("Y-m-d", strtotime($date)), $_POST["title"],
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
							$success = query($query, $otheruser, date("Y-m-d", strtotime($date)), 
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
			if (isset($_POST["month"]) && isset($_POST["year"]) && isset($_POST["trainingid"]))
			{
				$date = $_POST["year"] . "-" . $_POST["month"] . "-" . "01";
				if($_POST["internal_trainer"]==2)
				{
					$_POST["internal_trainer"]=NULL;
				}
				$query = "INSERT INTO trainingrecords (userid, date, trainingid, description, total_days, 
					internal_location, internal_trainer, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?, NULL)";
				$success = query($query, $_SESSION["userid"], date("Y-m-d", strtotime($date)), $_POST["trainingid"], 
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
							$success = query($query, $otheruser, date("Y-m-d", strtotime($date)), 
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
		else if ($_GET["type"] == "newPub")
		{
			if (isset($_POST["year"]) && isset($_POST["title"]) && isset($_POST["journal"]))
			{
				// Set blank fields to NULL where appropriate
				if ($_POST["volume"] === "") $_POST["volume"] = NULL;
				if ($_POST["issue"] === "") $_POST["issue"] = NULL;
				if ($_POST["startpage"] === "") $_POST["startpage"] = NULL;
				if ($_POST["endpage"] === "") $_POST["endpage"] = NULL;
				
				$query = "INSERT INTO publicationrecords (userid, journal, title, year, volume, 
					issue, startpage, endpage, source, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)";
				$success = query($query, $_SESSION["userid"], $_POST["journal"], $_POST["title"], $_POST["year"], 
					$_POST["volume"], $_POST["issue"], $_POST["startpage"], $_POST["endpage"], $_POST["source"]);

				if ($success === false)
				{
					echo $query . "<br />" . $_SESSION["userid"] . "<br />" .  $_POST["journal"] . "<br />" .  $_POST["title"] . "<br />" .  $_POST["year"] . "<br />" .  
					$_POST["volume"] . "<br />" .  $_POST["issue"] . "<br />" .  $_POST["startpage"] . "<br />" .  $_POST["endpage"] . "<br />" .  $_POST["source"]  . "<br />";
					apologize("Can't update database.");
				}
				else
				{
					if (isset($_POST["otherusers"]) && count($_POST["otherusers"]) > 0)
					{
						foreach ($_POST["otherusers"] as $otheruser)
						{
							$query = "INSERT INTO publicationrecords (userid, journal, title, year, volume, 
								issue, startpage, endpage, source, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)
								ON DUPLICATE KEY UPDATE id=id";
							$success = query($query, $otheruser, $_POST["journal"], $_POST["title"], 
								$_POST["year"], $_POST["volume"], $_POST["issue"], $_POST["startpage"], 
								$_POST["endpage"], $_POST["source"]);

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
				redirect($url . "#collapsePublicationHistory");
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
		else if ($_GET["type"] == "delPub")
		{
			if (isset($_GET["id"]))
			{
				$success = query("DELETE FROM publicationrecords WHERE id=? AND userid=?", $_GET["id"], $_SESSION["userid"]);
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
			redirect($url . "#collapsePublicationHistory");
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
		else if ($_GET["type"] == "confirmPub")
		{
			if (isset($_GET["id"]))
			{
				$success = query("UPDATE publicationrecords SET confirmed=1 WHERE id=? AND userid=?", $_GET["id"], $_SESSION["userid"]);
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
			redirect($url . "#collapsePublicationHistory");
		}
	}
?>
