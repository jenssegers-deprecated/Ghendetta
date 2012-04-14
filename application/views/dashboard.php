<?php include_once('head.tpl'); ?>
<body>
<link rel="stylesheet" href="<?php echo static_url('css/mapbox/leaflet.css'); ?>">
<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo static_url('css/mapbox/leaflet.ie.css'); ?>" /><![endif]-->

<?php include_once('navigation.tpl'); ?>

<section class="v-dashboard" role="main">
	<h1 class="logo"><img src="<?php echo static_url('img/intro.svg'); ?>" alt="Ghendetta" /></h1>

	<?php if(!$this->ghendetta->current_user()): ?>

	<p class="foursquare">
		<a href="<?php echo site_url('foursquare'); ?>">Connect &amp; Conquer</a>
		Connect with Foursquare and play.
	</p>
	<div class="tutorial">
		<h2>How To Play</h2>
		<p>Every visit to another district (check-in on Foursquare) gives you a chance to take away a district from a rivaling clan.</p>
	</div>
	<div class="message notice">
		<p><strong>Warning:</strong> play at your own risk. This is very much &beta;. Lives (or game data) might be lost. <small>But we respect your privacy, and your Foursquare data is safe!</small></p>
	</div>

	<?php else: ?>

	<p class="clan">You are with the <a href="/clan" style="background:#<?php echo $clan['color']; ?>"><?php echo $clan['name']; ?></a> clan.</p>

	<?php endif; ?>
</section>

<section class="v-dashboard-legend cf">
	<h1 class="js-toggle">Legend</h1></a>
	<dl class="legend">
		<dt><img src="<?php echo static_url('img/ico_battle.svg'); ?>" alt="" /></dt>
			<dd>My Battles</dd>
		<dt><img src="<?php echo static_url('img/ico_event.svg'); ?>" alt="" /></dt>
			<dd>Arena</dd>
		<?php foreach($clans as $clan): ?>
		<dt><img src="<?php echo $clan['shield']; ?>" alt="<?php echo $clan['name']; ?>" /></dt>
			<dd><?php echo $clan['name']; ?></dd>
		<?php endforeach; ?>
	</dl>
</section>

<div class="v-dashboard-map" id="map" ></div>

<?php include_once('footer.tpl'); ?>

<script src="<?php echo static_url('js/mapbox.min.js?v=080402'); ?>"></script>

</body>
</html>