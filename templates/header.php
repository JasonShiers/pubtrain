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
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" style="height: 48px; padding: 0;" href="index.php">
								<img src="img/logo.png" alt="logo" style="height: 48px;" />
							</a>
						</div>

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li class="active"><a href="index.php">My History <span class="sr-only">(current)</span></a></li>
								<li><a href="#">Link</a></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="#">Action</a></li>
										<li><a href="#">Another action</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="#">Separated link</a></li>
									</ul>
								</li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li>
									<div class="nav-username">
										<?php 
											if (isset($_SESSION["forename"]))
											{
												print(htmlspecialchars($_SESSION["forename"] . " " . $_SESSION["surname"] . " "));
												if($_SESSION["admin"] != 0)
												{
													print("<span class=\"glyphicon glyphicon-cog\"></span>");
												}
											}
										?>
									</div>
								</li>
								<?php if (isset($_SESSION["forename"])): ?>
								
									<li><a href="userinfo.php" class="imglink" style="padding: 5px 15px 0" title="Edit User Profile"><div class="imgdiv" id="profilebutton"></div></a></li>
									<li><a href="logout.php" class="imglink" style="padding: 5px 0;" title="Logout"><div class="imgdiv" id="logoutbutton"></div></a></li>
								<?php endif ?>
							</ul>
						</div>
					</div>
				</nav>
			</div>

			<div id="middle">
