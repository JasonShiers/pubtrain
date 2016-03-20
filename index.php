<?php

	// configuration
	require("includes/config.php"); 

	// get user's conference requests
	$query = "SELECT c.id, c.confdate, c.title, c.location, c.days, r.lmendorse, "
		. "r.approved FROM conferences c, requests r WHERE r.userid=? "
		. "AND r.conf_id=c.id AND c.confdate >= ? ORDER BY c.confdate ASC";
	$requests = query($query, $_SESSION["userid"], date("Y-m-d"));

	// get user's line manager approval requests (if any)
	$query = "SELECT c.id, r.id AS reqid, c.confdate, c.title, c.location, c.days, r.userid, "
		. "u.firstname, u.lastname FROM conferences c, requests r, users u "
		. "WHERE r.linemgr=? AND r.conf_id=c.id AND u.userid = r.userid "
		. "AND r.lmendorse IS NULL AND c.confdate >= ? ORDER BY c.confdate ASC";
	$lmapprovals = query($query, $_SESSION["userid"], date("Y-m-d"));

	// get user's conference history (past 2 years)
	$query = "SELECT c.id, c.confdate, c.title, c.location, c.days, r.attended, r.id AS req_id "
		. "FROM conferences c, requests r WHERE r.userid=? AND r.conf_id=c.id "
		. "AND c.confdate BETWEEN ? AND ? ORDER BY c.confdate ASC";
	$history = query($query, $_SESSION["userid"], date("Y-m-d", strtotime("-2 years")), date("Y-m-d"));

	// render table
	render("templates/dashboard.php", ["history" => $history, "lmapprovals" => $lmapprovals, 
		"requests" => $requests, "title" => "Dashboard"]);
?>
