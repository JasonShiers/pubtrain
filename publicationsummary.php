<?php

	// configuration
	require("includes/config.php"); 
	
	if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		$args = ["title" => "Publication Summary"];
		if (isset($_GET["success"]))
		{
			$args["success"] = TRUE;
		}

		// render table
		render("templates/publicationsummary.php", $args);
	}
	else if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		// validate submission
		if (!isset($_POST["startyear"]) || $_POST["startyear"] < 1900)
		{
			apologize("Error: Invalid start year");
		}
		else if (!isset($_POST["endyear"]) || $_POST["endyear"] > date("Y"))
		{
			apologize("Error: Invalid end year");
		}
		else if (!isset($_POST["patents"]) && !isset($_POST["journals"]))
		{
			apologize("Error: Must choose at least one publication type");
		}
		else if (!isset($_POST["clients"]) && !isset($_POST["internal"]) && !isset($_POST["external"]))
		{
			apologize("Error: Must choose at least one publication source");
		}
		
		$query = array("SELECT p.title, p.year, p.volume, p.issue, p.startpage, p.endpage, GROUP_CONCAT(DISTINCT p.source) AS source, p.journal, "
			. "GROUP_CONCAT(u.firstname, ' ', u.lastname ORDER BY p.id SEPARATOR ', ') AS userlist, "
			. "GROUP_CONCAT(p.id ORDER BY p.id SEPARATOR ', ') AS idlist FROM publicationrecords p, "
			. "users u WHERE p.userid = u.userid AND p.year BETWEEN ? AND ? ");
		
		$query[] = $_POST["startyear"];
		$query[] = $_POST["endyear"];

		if (isset($_POST["title"]) && $_POST["title"] !== "")
		{
			$query[0] = $query[0] . "AND p.title LIKE ? ";
			$query[] = "%" . $_POST["title"] . "%";
		}
		
		if (!isset($_POST["patents"]))
		{
			$query[0] = $query[0] . "AND p.journal = 1 ";
		}
		else if (!isset($_POST["journals"]))
		{
			$query[0] = $query[0] . "AND p.journal = 0 ";
		}
		
		if (!isset($_POST["clients"]))
		{
			if (!isset($_POST["internal"]))
			{
				// External
				$query[0] = $query[0] . "AND p.source = 'External' ";
			}
			else if (!isset($_POST["external"]))
			{
				// Internal
				$query[0] = $query[0] . "AND p.source = 'Internal' ";
			}
			else
			{
				// Internal + External
				$query[0] = $query[0] . "AND p.source IN ('Internal', 'External') ";
			}
		}
		else
		{
			if (!isset($_POST["internal"]))
			{
				if (!isset($_POST["external"]))
				{
					// Clients
					$query[0] = $query[0] . "AND p.source NOT IN ('Internal', 'External') ";
				}
				else
				{
					// Clients + External
					$query[0] = $query[0] . "AND p.source <> 'Internal' ";
				}
			}
			else if (!isset($_POST["external"]))
			{
				// Clients + Internal
				$query[0] = $query[0] . "AND p.source <> 'External' ";
			}
		}
		
		$query[0] = $query[0] . "GROUP BY p.title, p.year, p.volume, p.issue, p.startpage, p.endpage, p.journal ORDER BY p.year DESC";
		
		// get users list for multi select
		$users = query("SELECT userid, firstname, lastname FROM users "
			. "ORDER BY lastname ASC, firstname ASC");
					
		$publications = call_user_func_array("query", $query);
		
		// render table
		render("templates/publicationsummary.php", ["publications" => $publications, "pubtitle" => (isset($_POST["title"])?$_POST["title"]:""), 
			"startyear" => $_POST["startyear"], "endyear" => $_POST["endyear"], "patents" => (isset($_POST["patents"])?TRUE:FALSE), 
			"journals" => (isset($_POST["journals"])?TRUE:FALSE), "clients" => (isset($_POST["clients"])?TRUE:FALSE), 
			"internal" => (isset($_POST["internal"])?TRUE:FALSE), "external" => (isset($_POST["external"])?TRUE:FALSE), "users" => $users, 
			"title" => "Publication Summary"]);
	}
?>
