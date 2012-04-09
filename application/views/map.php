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

<?php include_once('footer.tpl'); ?>

<script src="<?php echo static_url('js/mapbox.min.js?v=080402'); ?>"></script>

</body>
</html>