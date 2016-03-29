<?php

	// configuration
	require("includes/config.php"); 

	if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		$userinfo = query("SELECT * FROM users WHERE userid=?", $_SESSION["userid"]);
		$linemgrs = query("SELECT userid, firstname, lastname FROM users ORDER BY lastname ASC, firstname ASC");
		$departments = query("SELECT department FROM departments");
		render("templates/modify_curr_user.php", ["departments" => $departments, "linemgrs" => $linemgrs, 
			"userinfo" => $userinfo, "title" => "Modify User Profile"]);
	}
	else if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		
		if(isset($_POST["linemgr"]) === false)
		{
			apologize("No line manager was set.");
		}
		if(isset($_POST["department"]) === false)
		{
			apologize("No department was set.");
		}
		else
		{
			// Get linemgr and department info
			$linemgr = query("SELECT userid FROM users WHERE userid=?", $_POST["linemgr"]);
			$dep = query("SELECT department, depmask FROM departments WHERE department = ?", $_POST["department"]);
				
			if(count($linemgr) == 1)
			{
				if(count($dep) == 1)
				{
					// update existing entry
					$success = query("UPDATE users SET linemgr=?, department=? WHERE userid=?", 
						$linemgr[0]["userid"], $_POST["department"], $_POST["userid"]);
				
					if ($success === false)
					{
						apologize("Can't update database.");
					}
					else 
					{
						$_SESSION["linemgr"] = $linemgr[0]["userid"];
						$_SESSION["department"] = $dep[0]["department"];
						$_SESSION["depmask"] = $dep[0]["depmask"];
						redirect("index.php");
					}
				}
				else
				{
					apologize("Department was not found in database");				
				}
			}
			else
			{
				apologize("Line manager was not found in database");
			}
		}
	}
?>
