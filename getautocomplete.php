<?php

	// configuration
	require("includes/config.php"); 

	
	if (isset($_GET["type"]) && isset($_GET["term"]))
	{
		
		if ($_GET["type"] == "newConfName")
		{
			$results = query("	SELECT title FROM conferences WHERE title LIKE ? 
									UNION 
									SELECT title FROM suppconfrecords WHERE title LIKE ? 
									GROUP BY title", "%" . $_GET["term"] . "%", "%" . $_GET["term"] . "%");

			header("Content-type: application/json");
			print(json_encode(array_column($results, "title"), JSON_PRETTY_PRINT));
		}
		else if ($_GET["type"] == "newConfLocation")
		{
			$results = query("	SELECT location FROM conferences WHERE location LIKE ?
								UNION
								SELECT location FROM suppconfrecords WHERE location LIKE ? 
								GROUP BY location", "%" . $_GET["term"] . "%", "%" . $_GET["term"] . "%");

			header("Content-type: application/json");
			print(json_encode(array_column($results, "location"), JSON_PRETTY_PRINT));
		}
		else if ($_GET["type"] == "newTrainDesc")
		{
			$results = query("SELECT description FROM trainingrecords WHERE description LIKE ? GROUP BY description", 
				"%" . $_GET["term"] . "%");

			header("Content-type: application/json");
			print(json_encode(array_column($results, "description"), JSON_PRETTY_PRINT));
		}
		else if ($_GET["type"] == "newPubDesc")
		{
			$results = query("SELECT title FROM publicationrecords WHERE title LIKE ? GROUP BY title", 
				$_GET["term"] . "%");

			header("Content-type: application/json");
			print(json_encode(array_column($results, "title"), JSON_PRETTY_PRINT));
		}
		else if ($_GET["type"] == "newPubSource")
		{
			$results = query("SELECT source FROM publicationrecords WHERE source LIKE ? GROUP BY source", 
				$_GET["term"] . "%");

			header("Content-type: application/json");
			print(json_encode(array_column($results, "source"), JSON_PRETTY_PRINT));
		}

	}
?>
