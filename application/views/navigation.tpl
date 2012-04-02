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
</section>