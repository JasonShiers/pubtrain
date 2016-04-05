<?php

	// configuration
	require("includes/config.php"); 
	
	if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		if(isset($_GET["admin"]) && $_GET["admin"] == 1)
		{
			$linegroup = query("SELECT userid, firstname, lastname FROM users WHERE department=? "
			. "ORDER BY lastname ASC, firstname ASC", $_SESSION["department"]);
		}
		else
		{
			$linegroup = getLineGroupUser($_SESSION["userid"]);
		}
		// render table
		render("templates/usersummary.php", ["linegroup" => $linegroup, "title" => "User Summary"]);
	}
	else if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		// validate submission
		if (!isset($_POST["userid"]))
		{
			apologize("Error: No username submitted");
		}
		else if($_SESSION["admin"] == 0 || !isset($_POST["admin"]) || $_POST["admin"] == 0)
		{
			$linegroup = getLineGroupUser($_SESSION["userid"]);
			if (array_search($_POST["userid"], array_column($linegroup, "userid")) === FALSE)
			{
				apologize("Error: This user is not in your line group");
			}
		}
		else
		{
			$linegroup = query("SELECT userid, firstname, lastname FROM users WHERE department=? "
			. "ORDER BY lastname ASC, firstname ASC", $_SESSION["department"]);
		}

		// get user's conference history
		$query = "	(SELECT c.id, c.confdate AS date, DATE_FORMAT(c.confdate, '%b %Y') AS confdate, c.title, c.location, 
					c.days, r.attended, r.id AS req_id, 0 AS editable, 1 AS confirmed, 1 AS verified 
					FROM conferences c, requests r WHERE r.userid=? AND r.conf_id=c.id AND c.confdate <= ?) 
					UNION
					(SELECT id AS id, date, DATE_FORMAT(date, '%b %Y') AS confdate, title, location, days, 0 AS attended, 
					0 AS req_id, 1 AS editable, confirmed, verified 
					FROM suppconfrecords WHERE userid=?) 
					ORDER BY date DESC";
		$confhistory = query($query, $_POST["userid"], date("Y-m-d"), $_POST["userid"]);

		// get user's training history
		$query = "SELECT tr.recordid AS id, DATE_FORMAT(tr.date, '%b %Y') AS date, tl.type, tl.catmask, tr.description, "
			. "tr.internal_location, tr.internal_trainer, tr.total_days, tr.confirmed, tr.verified "
			. "FROM trainingrecords tr, traininglibrary tl WHERE tr.userid = ? AND tr.trainingid = tl.trainingid "
			. "ORDER BY tr.date DESC";
		$trainhistory = query($query, $_POST["userid"]);

		// get user's publication history
		$query = "SELECT * FROM publicationrecords WHERE userid = ? ORDER BY year DESC, journal, title";
		$pubhistory = query($query, $_POST["userid"]);

		// render table
		render("templates/usersummary.php", ["linegroup" => $linegroup, "pubhistory" => $pubhistory, "trainhistory" => $trainhistory,
			"confhistory" => $confhistory, "userid" => $_POST["userid"], "admin" => (isset($_POST["admin"])?$_POST["admin"]:0), "title" => "User Summary"]);
	}
?>