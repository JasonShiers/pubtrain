<?php

	// configuration
	require("includes/config.php"); 

	// get user's conference history
	$query = "SELECT c.id, DATE_FORMAT(c.confdate, '%b %Y') AS confdate, c.title, c.location, c.days, r.attended, r.id AS req_id "
		. "FROM conferences c, requests r WHERE r.userid=? AND r.conf_id=c.id "
		. "AND c.confdate <= ? ORDER BY c.confdate DESC";
	$confhistory = query($query, $_SESSION["userid"], date("Y-m-d"));

	// get user's training history
	$query = "SELECT DATE_FORMAT(tr.date, '%b %Y') AS date, tl.type, tl.catmask, tr.description, "
		. "tr.internal_location, tr.internal_trainer, tr.total_days "
		. "FROM trainingrecords tr, traininglibrary tl WHERE tr.userid = ? AND tr.trainingid = tl.trainingid "
		. "ORDER BY tr.date DESC";
	$trainhistory = query($query, $_SESSION["userid"]);
	
	// render table
	render("templates/dashboard.php", ["trainhistory" => $trainhistory, "confhistory" => $confhistory, "title" => "Dashboard"]);
?>
