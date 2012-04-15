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
	<div class="message notice tutorial">
		<h2>How to Play</h2>
		<p>Connect. Check in. Watch the map.</p>
	</div>
	<div class="message">
		<p><strong>Warning:</strong> play at your own risk. This is very much &beta;. Lives (or game data) might be lost. <small>But we respect your privacy, and your Foursquare data is safe!</small></p>
	</div>

	<?php else: ?>

	<p class="clan">You are with the <a href="/clan" style="background:#<?php echo $clan['color']; ?>"><?php echo $clan['name']; ?></a> clan.</p>

	<section class="notifications">
		<article class="n-region_won s-new">
			<p><strong class="region">Oostakker</strong> is now Wolves territory. Like a boss.</p>
		</article>
		<article class="n-region_lost s-new">
			<p>The Snakes just took <strong class="region">Sint-Martens Latem</strong> from us! They can&rsquo;t get away with that!</p>
		</article>
		<article class="n-rank_won s-new">
			<p><strong>Congratulations!</strong> You just became Capo of your clan.</p>
		</article>
		<article class="n-capo_message s-new">
			<p>The Capo says: Move out. Wolves are howling in the uz area.</p>
		</article>
		<article class="n-region_won s-read">
			<p>Oostakker is now Hawks territory. Like a boss.</p>
		</article>
		<article class="n-region_lost s-read">
			<p>The snakes just took Sint-Martens Latem from us! They can&rsquo;t get away with that!</p>
		</article>
		<article class="n-rank_won s-read">
			<p>Congratulations! You became Capo of your clan.</p>
		</article>
		<article class="n-capo_message s-read">
			<p>The Capo says: Move out. Wolves are howling in the uz area.</p>
		</article>
	</section>

	<?php endif; ?>

	<h2 class="btn js-toggle-legend">Legend</h2></a>
	<div class="legend-holder cf">
		<dl class="legend">
			<?php if($this->ghendetta->current_user()): ?>
			<dt><img src="<?php echo static_url('img/ico_battle.svg'); ?>" alt="" /></dt>
				<dd>My Battles</dd>
			<dt><img src="<?php echo static_url('img/ico_arena.svg'); ?>" alt="" /></dt>
				<dd>Arena</dd>
			<?php endif; ?>
			<?php foreach($clans as $clan): ?>
			<dt><img src="<?php echo $clan['shield']; ?>" alt="<?php echo $clan['name']; ?>" /></dt>
				<dd><?php echo $clan['name']; ?></dd>
			<?php endforeach; ?>
		</dl>
	</div>


</section>

<div class="v-dashboard-map" id="map" ></div>

<?php include_once('footer.tpl'); ?>

<script src="<?php echo static_url('js/mapbox.min.js?v=080402'); ?>"></script>

</body>
</html>