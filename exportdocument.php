<?php

	// configuration
	require("includes/config.php");
        
        $DB = DB::getInstance();
        $userid = Session::get("userid");
        $name = Session::get("name");
        $type = Input::get("type");
        $sections = Input::get("sections");
        $fromdate = Input::get("fromdate");
        $todate = Input::get("todate");
        
	
	if (isset($type) && isset($fromdate) && isset($todate))
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

		if ($type == "ExportConf" || $sections == "all")
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
			$confhistory = $DB->assocQuery($query, $userid, $fromdate, $todate, $userid, 
                                $fromdate, $todate)->results();

			echo		"<h2>Conference History For " . $name . "</h2>";
			echo		"<p>The following conferences were attended between: " . 
                                date("j M Y", strtotime($fromdate)) . " and " . date("j M Y", strtotime($todate)) . ":</p>";
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
				echo 				"<td>" . escapeHTML($h["confdate"]) . "</td>
									 <td>" . escapeHTML($h["title"]) . "</td>
									 <td>" . escapeHTML($h["location"]) . "</td>
									 <td>" . escapeHTML($h["days"]) . "</td>";
				echo			"</tr>";
			}
			echo 			"</tbody>";
			echo 		"</table>";
			echo 	"</p>";

		}
		if ($type == "ExportTrain" || $sections == "all")
		{
			$query = "SELECT tr.recordid AS id, DATE_FORMAT(tr.date, '%b %Y') AS date, tl.type, tl.catmask, tr.description, "
					. "tr.internal_location, tr.internal_trainer, tr.total_days, tr.confirmed, tr.verified "
					. "FROM trainingrecords tr, traininglibrary tl WHERE tr.userid = ? AND tr.trainingid = tl.trainingid "
					. "AND tr.date BETWEEN ? AND ? ORDER BY tr.date DESC";
			$trainhistory = $DB->assocQuery($query, $userid, $fromdate, $todate)->results();

			echo		"<h2>Training History For " . $name . "</h2>";
			echo		"<p>The following training was received between: " . 
                                date("j M Y", strtotime($fromdate)) . " and " . date("j M Y", strtotime($todate)) . ":</p>";
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
				echo 				"<td>" . escapeHTML($h["date"]) . "</td>
									 <td>" . escapeHTML($h["type"]);
				if ($h["description"] !== ""){
					echo 				"<br />" . escapeHTML($h["description"]);
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
				echo 				"<td>" . escapeHTML($h["total_days"]) . "</td>
								</tr>";
			}
			echo 			"</tbody>";
			echo 		"</table>";
			echo 	"</p>";			
			
		}
		if ($type == "ExportPub" || $sections == "all")
		{
			$query = "SELECT * FROM publicationrecords WHERE userid = ? AND year BETWEEN YEAR(?) AND YEAR(?) ORDER BY year DESC, journal, title";
			$pubhistory = $DB->assocQuery($query, $userid, $fromdate, $todate)->results();

			echo		"<h2>Publication History For " . $name . "</h2>";
			echo		"<p>The following publications were made between: " . 
                                date("Y", strtotime($fromdate)) . " and " . date("Y", strtotime($todate)) . ":</p>";
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
				echo 				"<td>" . escapeHTML($h["year"]) . "</td>";
				if ($h["journal"] == 1)
				{
					echo 			"<td><i>" . escapeHTML($h["title"]) . "</i>";
					if($h["volume"] !== 0)
					{
						echo	 		", <b>" . escapeHTML($h["volume"]) . "</b>";
					}
					if($h["issue"] !== 0)
					{
						echo 			"(" . escapeHTML($h["issue"]) . ")";
					}
					echo 				", " . escapeHTML($h["startpage"]);
					if($h["endpage"] !== 0)
					{
						echo "-" . escapeHTML($h["endpage"]);
					}
				}
				else
				{
					echo 			"<td>" . escapeHTML($h["title"]);
				}
				echo 				"</td>
									<td>" . escapeHTML($h["source"]) . "</td>
								</tr>";
			}
			echo 			"</tbody>";
			echo 		"</table>";
			echo 	"</p>";					
		}
		echo 	"</body>";
		echo "</html>";
	}