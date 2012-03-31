<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<?php
$start = strtotime('31.03.2012 11:00');
$release = strtotime('31.03.2012 20:00');
$offset = 0;

$progress = (time() - $start) * 1.0 / ($release - $start);

if (isset($offset) && $offset) {
    $progress = floor($offset + ($progress * (100 - $offset)));
} else {
    $progress = $progress * 100;
}
?>

<section class="v-clan" role="main">
	<p>Your capo is excited. He has found top-secret information he will be showing to you soon. Fights more battles to prove you are worth his gift.</p>
    <p class="pr-cont"><span class="pr" style="width:<?php echo $progress; ?>%"></span></p>
</section>

</body>
</html>
