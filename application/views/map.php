<!doctype html>
<html>
<head>
	<title>Ghendetta</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" href="css/1140.css" media="screen" />
	<link rel="stylesheet" href="css/styles.css" media="screen" />
	<script src="js/css3-mediaqueries.js"></script>

</head>
<body>

<div id="sidebar">
	<img src="img/logo.png" id="logo">
	
	<div id="login">
		<p id="welcome">Welcome, Godfather!</p>
	</div>
	
	<?php if(!$this->ghendetta->current_user()): ?>
		<a href="<?php echo site_url('foursquare/auth'); ?>"><img src="img/connect-white.png" id="foursquare"></a>
	<?php else: ?>
		Jouw clan: <strong style="color:#<?php echo $clan['color']; ?>"><?php echo $clan['name']; ?></strong>
	<?php endif; ?>
	
	<div id="leadingclans">
		<ul>
			<?php foreach($clans as $clan): ?>
			<li><img src="<?php echo $clan['logo']; ?>"><?php echo $clan['name']; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<div id="map_canvas"></div>

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