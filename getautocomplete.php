<?php

	// configuration
	require("includes/config.php"); 

	
	if (isset($_GET["type"]) && isset($_GET["term"]))
	{
		
		if ($_GET["type"] == "newConfName")
		{
			$conferences = query("	SELECT title FROM conferences WHERE title LIKE ? 
									UNION 
									SELECT title FROM suppconfrecords WHERE title LIKE ? 
									GROUP BY title", "%" . $_GET["term"] . "%", "%" . $_GET["term"] . "%");

			// output conferences as JSON
			header("Content-type: application/json");
			print(json_encode(array_column($conferences, "title"), JSON_PRETTY_PRINT));
		}
		else if ($_GET["type"] == "newConfLocation")
		{
			$conferences = query("	SELECT location FROM conferences WHERE location LIKE ?
									UNION
									SELECT location FROM suppconfrecords WHERE location LIKE ? 
									GROUP BY location", "%" . $_GET["term"] . "%", "%" . $_GET["term"] . "%");

			// output conferences as JSON
			header("Content-type: application/json");
			print(json_encode(array_column($conferences, "location"), JSON_PRETTY_PRINT));
		}

	}
?>
