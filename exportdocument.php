<?php

	// configuration
	require("includes/config.php"); 
	
	if (isset($_GET["type"]) && isset($_GET["fromdate"]) && isset($_GET["todate"]))
	{
		header("Content-type: application/vnd.ms-word");
		header("Content-Disposition: attachment;Filename=document_name.doc");

		echo "<html>";
		echo 	"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
		echo 	"<body>";
		echo	"<style>";
		echo		"table, th, td { border: 1px solid black; border-collapse: collapse; }";
		echo		"th, td { padding: 0.25em; }";
		echo		"h2, p, td { text-align: center; }";
		echo	"</style>";

		if ($_GET["type"] == "ConfHist")
		{
			$query = "	(SELECT c.id, c.confdate AS date, DATE_FORMAT(c.confdate, '%b %Y') AS confdate, c.title, c.location, 
						c.days, r.attended, r.id AS req_id, 0 AS editable, 1 AS confirmed, 1 AS verified 
						FROM conferences c, requests r WHERE r.userid=? AND r.conf_id=c.id AND r.attended = 1 
						AND c.confdate BETWEEN ? AND ?) 
						UNION
						(SELECT id AS id, date, DATE_FORMAT(date, '%b %Y') AS confdate, title, location, days, 0 AS attended, 
						0 AS req_id, 1 AS editable, confirmed, verified 
						FROM suppconfrecords WHERE userid=? AND (confirmed IS NULL || confirmed = 1) 
						AND date BETWEEN ? AND ?) 
						ORDER BY date DESC";
			$confhistory = query($query, $_SESSION["userid"], $_GET["fromdate"], $_GET["todate"], $_SESSION["userid"], $_GET["fromdate"], $_GET["todate"]);

			echo		"<h2>Conference History For " . $_SESSION["forename"] . " " . $_SESSION["surname"] . "</h2>";
			echo		"<p>The following conferences were attended between: " . date("j M Y", strtotime($_GET["fromdate"])) . " and " . date("j M Y", strtotime($_GET["todate"])) . ":</p>";
			echo		"<p>";
			echo		"<table style=\"width: 100%;\">
							<thead>
								<tr>
									<th style=\"width: 6em;\">
										Start Date
									</th>
									<th>
										Title
									</th>
									<th>
										Location
									</th>
									<th style=\"width: 3em;\">
										Days
									</th>
								</tr>
							</thead>
							<tbody>";
			foreach ($confhistory AS $h)
			{
				echo			"<tr>";
				echo 				"<td>" . htmlspecialchars($h["confdate"]) . "</td>
									 <td>" . htmlspecialchars($h["title"]) . "</td>
									 <td>" . htmlspecialchars($h["location"]) . "</td>
									 <td>" . htmlspecialchars($h["days"]) . "</td>";
				echo			"</tr>";
			}
				echo 		"</tbody>";
				echo 	"</table>";
				echo "</p>";

		}

		echo 	"</body>";
		echo "</html>";
	}
?>
