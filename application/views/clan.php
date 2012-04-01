<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section class="v-clan cf" role="main">
	<div class="bd">
		<h1>My Clan: <?php echo $clan['name']; ?> (<strong><?php echo  $clan['battles']; ?></strong> <abbr title="Foursquare Checkins">battles</abbr>)</h1>
		<p>Congratulations! Your collaborative battle spree made you unlock the clan page. Your Capo is pleased. <a href="https://twitter.com/intent/tweet?source=webclient&amp;text=Mayhem%21+%23<?php echo $clan['name']; ?>+have+just+unlocked+the+clan+page+on+http%3A%2F%2Fghendetta.be" class="button">Tweet about this.</a></p>
	</div>
	<?php foreach($members as $member): ?>
	<article>
		<div class="cf">
			<h1<?php if($member['rank'] == 1) {echo " class=\"capo\"";} ?>><a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>"><?php echo $member['firstname']; ?></a></h1>
			<a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>">
				<img src="<?php echo $member['picurl']; ?>" alt="<?php echo $member['firstname']; ?>" width="72" height="72" />
			</a>
			<dl>
				<dt>Battles:</dt>
				<dd class="battles"><?php echo $member['battles']; ?></dd>
				<dt>Rank:</dt>
				<dd class="rank">
					<?php
						switch ($member['rank']) {
						   case 1 :
						      echo '<strong>Capo</strong>';
						      break;
							break;
							default:
							echo 'Associate';
						}
					?>
				</dd>
			</dl>
		</div>
	</article>
	<?php endforeach; ?>
</section>

<script src="<?php echo static_url('js/jquery.js'); ?>"></script>
<script src="<?php echo static_url('js/application.min.js?v=010402'); ?>"></script>

</body>
</html>