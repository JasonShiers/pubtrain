<!DOCTYPE html>

<html lang="en">

	<head>

		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
		<link href="css/chosen.min.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet"/>
		<link href="css/jquery-ui.css" rel="stylesheet"/>

		<?php if (isset($title)): ?>
			<title>PubTrain: <?= htmlspecialchars($title) ?></title>
		<?php else: ?>
			<title>PubTrain</title>
		<?php endif ?>

		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery-ui-1.11.4.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/scripts.js"></script>
		<script src="js/chosen.jquery.min.js"></script>
		<script src="js/bootstrap-session-timeout.min.js"></script>

	</head>

	<body>
		<div class="container">

			<div id="top">
				<div id="menu">
					<div class="row menu">
						<div class="col-xs-3 menucell">
							<img src="img/logo.png" alt="logo" style="display: inline-block; vertical-align: top; width: 150px;" />
							<p style="color: dimgray; font-size: 0.70vw;"><i><b>Sygnature Publication and Training Database</b></i></p>
						</div>
						<div class="col-xs-1 menucell">
							<div><a href="index.php" class="imglink"><div class="imgdiv" id="dashbutton" title="Dashboard"></div></a></div>
						</div>
						<div class="col-xs-2 menucell">
							<a href="#">Menu1</a>
						</div>
						<div class="col-xs-2 menucell">
							<a href="#">Menu2</a>
						</div>
						<?php 
							if (isset($_SESSION["forename"]))
							{
								print("<div class=\"col-xs-2 menucell\"><p>"
								. htmlspecialchars($_SESSION["forename"] . " " . $_SESSION["surname"] . " "));
								if($_SESSION["admin"] != 0)
								{
									print("<span class=\"glyphicon glyphicon-cog\"></span>");
								}
							}
						?>
						<?php if (isset($_SESSION["forename"])): ?>
							</p></div>
							<div class="col-xs-1 menucell left">
								<a href="userinfo.php" class="imglink" title="Edit User Profile"><div class="imgdiv" id="profilebutton"></div></a>
							</div>
							<div class="col-xs-1 menucell left">
								<a href="logout.php" class="imglink" title="Logout"><div class="imgdiv" id="logoutbutton"></div></a>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>

			<div id="middle">
