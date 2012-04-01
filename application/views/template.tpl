<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<title><?php echo $this->template->title->default('Ghendetta'); ?></title>
	
    <?php if(ENVIRONMENT == 'production'): ?>
	<link rel="stylesheet" href="<?php echo static_url('css/styles.min.css?v=010405'); ?>" media="screen" />
	<?php else: ?>
	<link rel="stylesheet" href="<?php echo static_url('css/styles.css'); ?>" media="screen" />
	<?php endif; ?>
	<?php echo $this->template->stylesheet; ?>
	
	<script src="<?php echo static_url('js/css3-mediaqueries.js'); ?>"></script>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<?php include_once('navigation.tpl'); ?>

<?php echo $this->template->content; ?>

<script>
	var base_url = '<?php echo base_url(); ?>';
	var site_url = '<?php echo site_url(); ?>';
	var static_url = '<?php echo static_url(); ?>';
</script>
<script src="<?php echo static_url('js/jquery.js'); ?>"></script>
<?php echo $this->template->javascript; ?>

</body>
</html>
