<!doctype html>
<html>
<head>
	<title>Ghendetta</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/styles.css" media="screen" />
	<script src="js/css3-mediaqueries.js"></script>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<section class="main" role="main">
	<h1><img src="img/logo.png" class="logo" alt="Ghendetta" /></h1>
	<h2>Welcome!</h2>

	<p class="message warning">Warning: enter the game at your own risk. It is very much &szlig;. Lives or data will be lost.</p>

	<p>
		<?php if(!$this->ghendetta->current_user()): ?>
		<a href="<?php echo site_url('foursquare/auth'); ?>"><img src="img/connect-white.png" class="foursquare" /></a>
		<?php else: ?>
		<a href="#" class="button">Manage your Clan</a>
		<?php endif; ?>
	</p>

	<ul class="leaderboard">
		<li><img src="img/wapenschild4.png" />Turtles</li>
		<li><img src="img/wapenschild2.png" />iRail</li>
		<li><img src="img/wapenschild3.png" />GhentMob</li>
		<li><img src="img/wapenschild1.png" />DriveBy</li>
	</ul>
</section>

<section id="map_canvas"></section>

<script src="js/jquery.js"></script>
<script src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="js/application.js"></script>
<script src="js/battlefield.js"></script>
<script>
	var battlefield = <?php echo json_encode($battlefield); ?>;
	$(document).ready(function()
	{
	    visualize(battlefield);
	});
</script>
</body>
</html>