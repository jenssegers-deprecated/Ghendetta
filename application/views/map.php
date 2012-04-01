<?php include_once('head.tpl'); ?>
<body>
<link rel="stylesheet" href="<?php echo static_url('css/mapbox/leaflet.css'); ?>">
<?php include_once('navigation.tpl'); ?>

<section id="map" class="v-map"></section>

<script>
	var base_url = '<?php echo base_url(); ?>';
	var site_url = '<?php echo site_url(); ?>';
	var static_url = '<?php echo static_url(); ?>';
</script>

<script src="<?php echo static_url('js/jquery.js'); ?>"></script>
<script src="<?php echo static_url('js/application.min.js?v=010402'); ?>"></script>
<script src="<?php echo static_url('js/mapbox.min.js?v=310301'); ?>"></script>

</body>
</html>