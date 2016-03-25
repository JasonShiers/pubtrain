<?php

	// configuration
	require("includes/config.php"); 

	
	if (isset($_GET["type"]) && isset($_GET["term"]))
	{
		
		if ($_GET["type"] == "newConfName")
		{
			$conferences = query("SELECT title FROM conferences WHERE title LIKE ?", "%" . $_GET["term"] . "%");

			// output conferences as JSON
			header("Content-type: application/json");
			print(json_encode(array_column($conferences, "title"), JSON_PRETTY_PRINT));
		}
	}
?>
