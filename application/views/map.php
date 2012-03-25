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

	<p>
		<?php if(!$this->ghendetta->current_user()): ?>
		<a href="<?php echo site_url('foursquare/auth'); ?>"><img src="img/connect-white.png" class="foursquare" /></a>
		<?php else: ?>
		Jouw clan: <strong style="color:#<?php echo $clan['color']; ?>"><?php echo $clan['name']; ?></strong>
		<?php endif; ?>
	</p>

	<div class="message warning">
		<p><strong>Warning:</strong> enter at your own risk. It is very much &szlig;. Lives or game data will be lost. <small>But we respect your privacy, and your Foursquare data is safe!</small></p>
	</div>

	<ul class="leaderboard">
		<?php foreach($clans as $clan): ?>
		<li><img src="<?php echo $clan['logo']; ?>"><?php echo $clan['name']; ?></li>
		<?php endforeach; ?>
	</ul>
</section>

<section id="map_canvas"></section>

<script src="js/jquery.js"></script>
<script src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="js/application.js"></script>
<script src="js/battlefield.js"></script>
<script>
	Battlefield.regions = <?php echo json_encode($regions); ?>;
	Battlefield.checkins = <?php echo json_encode($checkins); ?>;
	Battlefield.init('map_canvas');
</script>
</body>
</html>