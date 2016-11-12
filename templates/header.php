<!DOCTYPE html>

<html lang="en">

    <head>

        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/chosen.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>
        <link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>

        <title>
            <?php if (isset($title)): ?>
                PubTrain: <?= htmlspecialchars($title) ?>
            <?php else: ?>
                PubTrain
            <?php endif ?>
        </title>

        <meta charset="utf-8">

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
                            <button type="button" class="navbar-toggle collapsed" 
                                    data-toggle="collapse" 
                                    data-target="#bs-example-navbar-collapse-1" 
                                    aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" style="height: 48px; 
                               padding: 0;" href="index.php">
                                <img src="img/logo.png" alt="logo" 
                                     style="height: 48px;" />
                            </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <?php if (Session::exists("forename")): ?>
                            <div class="collapse navbar-collapse" 
                                 id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li id="navMyHistory">
                                        <a href="index.php">
                                            My History 
                                            <span class="sr-only">
                                                (current)
                                            </span>
                                        </a>
                                    </li>
                                    <li id="navSummaries" class="dropdown">
                                        <a href="#" class="dropdown-toggle" 
                                           data-toggle="dropdown" role="button" 
                                           aria-haspopup="true" 
                                           aria-expanded="false">
                                            Summaries 
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="linereportsummary.php">
                                                    Line Reports
                                                </a>
                                            </li>
                                            <li>
                                                <a href="trainingsummary.php">
                                                    Super User
                                                </a>
                                            </li>
                                            <li role="separator" class="divider">
                                            </li>
                                            <li>
                                                <a href="publicationsummary.php">
                                                    Publication summary
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <?php if (Session::exists("admin") && 
                                              Session::get("admin") == 1): ?>
                                        <li id="navAdmin" class="dropdown">
                                            <a href="#" class="dropdown-toggle" 
                                               data-toggle="dropdown" 
                                               role="button" 
                                               aria-haspopup="true" 
                                               aria-expanded="false">
                                                Admin Options 
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="linereportsummary.php?admin=1">
                                                        Department Members
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="trainingsummary.php?admin=1">
                                                        Training Summary
                                                    </a>
                                                </li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                            </ul>
                                        </li>
                                    <?php endif ?>
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    <li>
                                        <div class="nav-username">
                                            <?php if (Session::get("forename")): ?>
                                                <?= escapeHTML(Session::get("name") . " ") ?>
                                                <?php if($_SESSION["admin"] != 0){ ?>
                                                    <span class="glyphicon glyphicon-cog"></span>
                                                <?php } ?>
                                            <?php endif ?>
                                        </div>
                                    </li>
                                    <?php if (Session::get("forename")): ?>
                                        <li>
                                            <a href="userinfo.php" class="imglink" 
                                               style="padding: 5px 15px 0" 
                                               title="Edit User Profile">
                                                <div class="imgdiv" 
                                                    id="profilebutton">
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="logout.php" class="imglink" 
                                               style="padding: 5px 0;" 
                                               title="Logout">
                                                <div class="imgdiv" 
                                                    id="logoutbutton">
                                                </div>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        <?php endif ?>
                    </div>
                </nav>
            </div>

            <div id="middle">
