<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<?php
$progress = (time() - $start) * 1.0 / ($release - $start);

if (isset($offset)) {
    $progress = floor($offset + ($progress * (100 - $offset)));
} else {
    $progress = $progress * 100;
}
?>

<section class="clan" role="main">
	<h1>My Clan: <?php echo $clan['name']; ?></h1>
	<p>Your capo is excited. He wants you to fight <strong>666</strong> battles to reveal <?php echo $feature; ?>.</p>
    <p>You and your fellow associates need to fight <strong><?php echo  666 - $clan['points']; ?></strong> more <abbr title="Foursquare Checkins">battles</abbr>.</p>	
    <p class="pr-cont"><span class="pr" style="width:<?php echo $progress; ?>%"></span></p>
</section>

</body>
</html>
