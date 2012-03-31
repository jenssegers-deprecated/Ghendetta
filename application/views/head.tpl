<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Ghendetta</title>
	<?php if(ENVIRONMENT == 'production'): ?>
	<link rel="stylesheet" href="<?php echo static_url('css/styles.min.css?v=010401'); ?>" media="screen" />
	<?php else: ?>
	<link rel="stylesheet" href="<?php echo static_url('css/styles.css'); ?>" media="screen" />
	<?php endif; ?>
	<script src="<?php echo static_url('js/css3-mediaqueries.js'); ?>"></script>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>