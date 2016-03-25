<?php

	// configuration
	require("includes/config.php"); 

	// get user's conference history
	$query = "	(SELECT c.id, c.confdate AS date, DATE_FORMAT(c.confdate, '%b %Y') AS confdate, c.title, c.location, 
				c.days, r.attended, r.id AS req_id, 0 AS editable 
				FROM conferences c, requests r WHERE r.userid=? AND r.conf_id=c.id AND c.confdate <= ?) 
				UNION
				(SELECT 0 AS id, date, DATE_FORMAT(date, '%b %Y') AS confdate, title, location, days, 0 AS attended, 
				0 AS req_id, 1 AS editable 
				FROM suppconfrecords WHERE userid=?) 
				ORDER BY date DESC";
	$confhistory = query($query, $_SESSION["userid"], date("Y-m-d"), $_SESSION["userid"]);

	// get user's training history
	$query = "SELECT DATE_FORMAT(tr.date, '%b %Y') AS date, tl.type, tl.catmask, tr.description, "
		. "tr.internal_location, tr.internal_trainer, tr.total_days "
		. "FROM trainingrecords tr, traininglibrary tl WHERE tr.userid = ? AND tr.trainingid = tl.trainingid "
		. "ORDER BY tr.date DESC";
	$trainhistory = query($query, $_SESSION["userid"]);
	
	// render table
	render("templates/dashboard.php", ["trainhistory" => $trainhistory, "confhistory" => $confhistory, "title" => "Dashboard"]);
?>
