<!doctype html>
<html>
<head>
	<title>Ghendetta</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/styles.css?v=26032012" media="screen" />
	<script src="js/css3-mediaqueries.js"></script>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<section class="main" role="main">
	<h1 class="logo"><img src="img/logo.png" alt="Ghendetta" /></h1>

		<?php if(!$this->ghendetta->current_user()): ?>
		<p class="foursquare">
			<a href="<?php echo site_url('foursquare/auth'); ?>">Connect &amp; Conquer</a>
			Connect with Foursquare and play.
		</p>
		<div class="message notice">
			<p><strong>Warning:</strong> play at your own risk. This is very much &szlig;. Lives (or game data) might be lost. <small>But we respect your privacy, and your Foursquare data is safe!</small></p>
			<p class="gamedev" title="Game Development Process"><span class="progress"></span></p>

		</div>
		<?php else: ?>
		<h2>Welcome to Ghendetta</h2>
		<p class="clan">You are with the <strong style="background:#<?php echo $clan['color']; ?>"><?php echo $clan['name']; ?></strong> clan.</p>
		<div class="tutorial">
			<h2>How To Play</h2>
			<p>Every visit to another district (check-in on Foursquare) gives you a chance to take away a district from a rivaling clan.</p>
		</div>
		<?php endif; ?>

	<ul class="leaderboard">
		<?php foreach($clans as $clan): ?>
		<li><img src="<?php echo $clan['logo']; ?>"><?php echo $clan['name']; ?></li>
		<?php endforeach; ?>
	</ul>
</section>

<section id="map_canvas"></section>

<script src="js/jquery.js"></script>
<script src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="js/application.js?v=26032012"></script>
<script src="js/battlefield.js?v=26032012"></script>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var site_url = '<?php echo site_url(); ?>';

	Battlefield.regions = <?php echo json_encode($regions); ?>;
	Battlefield.checkins = <?php echo json_encode($checkins); ?>;
	Battlefield.init('map_canvas');
</script>
</body>
</html>