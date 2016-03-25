<?php

	// configuration
	require("includes/config.php"); 

	
	if (isset($_GET["type"]))
	{
		if ($_GET["type"] == "newConf")
		{
			if (isset($_POST["confdate"]) && isset($_POST["title"]))
			{
				$query = "INSERT INTO suppconfrecords (userid, date, title, location, days) VALUES (?, ?, ?, ?, ?)";
				$success = query($query, $_SESSION["userid"], date("Y-m-d", strtotime($_POST["confdate"])), $_POST["title"],
					$_POST["location"], $_POST["days"]);

				if ($success === false)
				{
					apologize("Can't update database.");
				}
				else
				{
					redirect("index.php");
				}
			}
		}
	}
?>
