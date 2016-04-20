<?php

	// configuration
	require("includes/config.php"); 
	
	if (isset($_GET["type"]) && isset($_POST["fromdate"]) && isset($_POST["todate"]))
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

		if ($_GET["type"] == "ExportConf" || $_POST["sections"] == "all")
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
			$confhistory = query($query, $_SESSION["userid"], $_POST["fromdate"], $_POST["todate"], $_SESSION["userid"], $_POST["fromdate"], $_POST["todate"]);

			echo		"<h2>Conference History For " . $_SESSION["forename"] . " " . $_SESSION["surname"] . "</h2>";
			echo		"<p>The following conferences were attended between: " . date("j M Y", strtotime($_POST["fromdate"])) . " and " . date("j M Y", strtotime($_POST["todate"])) . ":</p>";
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
			echo 			"</tbody>";
			echo 		"</table>";
			echo 	"</p>";

		}
		if ($_GET["type"] == "ExportTrain" || $_POST["sections"] == "all")
		{
			$query = "SELECT tr.recordid AS id, DATE_FORMAT(tr.date, '%b %Y') AS date, tl.type, tl.catmask, tr.description, "
					. "tr.internal_location, tr.internal_trainer, tr.total_days, tr.confirmed, tr.verified "
					. "FROM trainingrecords tr, traininglibrary tl WHERE tr.userid = ? AND tr.trainingid = tl.trainingid "
					. "AND tr.date BETWEEN ? AND ? ORDER BY tr.date DESC";
			$trainhistory = query($query, $_SESSION["userid"], $_POST["fromdate"], $_POST["todate"]);

			echo		"<h2>Training History For " . $_SESSION["forename"] . " " . $_SESSION["surname"] . "</h2>";
			echo		"<p>The following training was received between: " . date("j M Y", strtotime($_POST["fromdate"])) . " and " . date("j M Y", strtotime($_POST["todate"])) . ":</p>";
			echo		"<p>";
			echo		"<table style=\"width: 100%;\">
							<thead>
								<tr>
									<th style=\"width: 6em;\">
										Start Date
									</th>
									<th>
										Title and Description
									</th>
									<th>
										Location
									</th>
									<th>
										Trainer
									</th>
									<th style=\"width: 3em;\">
										Days
									</th>
								</tr>
							</thead>
							<tbody>";
			foreach ($trainhistory AS $h)
			{
				echo			"<tr>";
				echo 				"<td>" . htmlspecialchars($h["date"]) . "</td>
									 <td>" . htmlspecialchars($h["type"]);
				if ($h["description"] !== ""){
					echo 				"<br />" . htmlspecialchars($h["description"]);
				}
				echo 				"</td>";
				if ($h["internal_location"] == 0)
				{
					echo 			"<td>External</td>";
				}
				else
				{
					echo 			"<td>Internal</td>";
				}									
				if ($h["internal_trainer"] === 0)
				{
					echo 			"<td>External</td>";
				}
				else if ($h["internal_trainer"] == 1)
				{
					echo 			"<td>Internal</td>";
				}
				else
				{
					echo 			"<td>N/A</td>";
				}
				echo 				"<td>" . htmlspecialchars($h["total_days"]) . "</td>
								</tr>";
			}
			echo 			"</tbody>";
			echo 		"</table>";
			echo 	"</p>";			
			
		}
		if ($_GET["type"] == "ExportPub" || $_POST["sections"] == "all")
		{
			$query = "SELECT * FROM publicationrecords WHERE userid = ? AND year BETWEEN YEAR(?) AND YEAR(?) ORDER BY year DESC, journal, title";
			$pubhistory = query($query, $_SESSION["userid"], $_POST["fromdate"], $_POST["todate"]);

			echo		"<h2>Publication History For " . $_SESSION["forename"] . " " . $_SESSION["surname"] . "</h2>";
			echo		"<p>The following publications were made between: " . date("Y", strtotime($_POST["fromdate"])) . " and " . date("Y", strtotime($_POST["todate"])) . ":</p>";
			echo		"<p>";
			echo		"<table style=\"width: 100%;\">
							<thead>
								<tr>
									<th style=\"width: 6em;\">
										Year
									</th>
									<th>
										Reference
									</th>
									<th>
										Source
									</th>
								</tr>
							</thead>
							<tbody>";
			foreach ($pubhistory AS $h)
			{
				echo			"<tr>";
				echo 				"<td>" . htmlspecialchars($h["year"]) . "</td>";
				if ($h["journal"] == 1)
				{
					echo 			"<td><i>" . htmlspecialchars($h["title"]) . "</i>";
					if($h["volume"] !== 0)
					{
						echo	 		", <b>" . htmlspecialchars($h["volume"]) . "</b>";
					}
					if($h["issue"] !== 0)
					{
						echo 			"(" . htmlspecialchars($h["issue"]) . ")";
					}
					echo 				", " . htmlspecialchars($h["startpage"]);
					if($h["endpage"] !== 0)
					{
						echo "-" . htmlspecialchars($h["endpage"]);
					}
				}
				else
				{
					echo 			"<td>" . htmlspecialchars($h["title"]);
				}
				echo 				"</td>
									<td>" . htmlspecialchars($h["source"]) . "</td>
								</tr>";
			}
			echo 			"</tbody>";
			echo 		"</table>";
			echo 	"</p>";					
		}
		echo 	"</body>";
		echo "</html>";
	}
?>
