<?php
$ci = &get_instance();
$section = strtolower($ci->router->fetch_class()); ?>
<section class="siteNav">
	<ul>
		<li<?php echo $section == 'main' ? ' class="selected"' : ''; ?>>
			<a href="<?php echo site_url(); ?>" class="dashboard">Dashboard</a>
		</li>
		<li<?php echo $section == 'map' ? ' class="selected"' : ''; ?>>
			<a href="<?php echo site_url('map'); ?>" class="map">Map</a>
		</li>
		<?php if($this->ghendetta->current_user()): ?>
		<li<?php echo $section == 'clan' ? ' class="selected"' : ''; ?>>
			<a href="<?php echo site_url('clan'); ?>" class="myClan">My Clan</a>
		</li>
		<?php endif; ?>

		<li<?php echo $section == 'about' ? ' class="selected"' : ''; ?>>
			<a href="<?php echo site_url('about'); ?>" class="about">About</a>
		</li>
	</ul>
	<p class="twitter">
		<a href="https://twitter.com/Ghendetta" class="twitter-follow-button" data-show-count="false" data-lang="en" data-size="large">Follow Ghendetta</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;
			js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</p>
</section>