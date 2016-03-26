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
					redirect("index.php");
				}
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
			redirect("index.php");
		}
	}
?>
