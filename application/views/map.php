<!doctype html>
<html>
<head>
	<title>Ghendetta</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="<?php echo static_url('css/styles.css?v=27032012'); ?>" media="screen" />
	<script src="<?php echo static_url('js/css3-mediaqueries.js'); ?>"></script>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<section class="main" role="main">
	<h1 class="logo"><img src="<?php echo static_url('img/logo.png'); ?>" alt="Ghendetta" /></h1>

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
			<dl class="legend cf">
				<dt><img src="/img/ico_battle.png" /></dt>
				<dd>Your battles</dd>
			</dl>
			<h2>How To Play</h2>
			<p>Every visit to another district (check-in on Foursquare) gives you a chance to take away a district from a rivaling clan.</p>
		</div>
		<?php endif; ?>

		<!-- <p class="tutorial" >
			<a href="https://twitter.com/Ghendetta" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @Ghendetta</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;
						  js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
			</script> us on twitter for updates about new features.
		</p> -->

	<ul class="leaderboard">
		<?php foreach($clans as $clan): ?>
		<li><img src="<?php echo $clan['logo']; ?>"><?php echo $clan['name']; ?></li>
		<?php endforeach; ?>
	</ul>
</section>

<section id="map_canvas"></section>

<script>
	var base_url = '<?php echo base_url(); ?>';
	var site_url = '<?php echo site_url(); ?>';
	var static_url = '<?php echo static_url(); ?>';
</script>

<script src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="<?php echo static_url('js/jquery.js'); ?>"></script>
<script src="<?php echo static_url('js/battlefield.js?v=27032012'); ?>"></script>
</body>
</html>
