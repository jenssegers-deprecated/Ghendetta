<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>
<section class="clan" role="main">
	<h1>My Clan: <?php echo $clan['name']; ?></h1>
	<p>Congratulations. You and your fellow associates have fought <strong><?php echo  $clan['points']; ?></strong> <abbr title="Foursquare Checkins">battles</abbr>. Your Capo is pleased.</p>
	<?php foreach($members as $member): ?>
	<article>
		<div class="cf">
			<h1><a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>"><?php echo $member['firstname']; ?></a></h1>
			<a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>">
				<img src="<?php echo $member['picurl']; ?>" alt="<?php echo $member['firstname']; ?>" width="110" height="110" />
			</a>
			<dl>
				<dt>Battles:</dt>
				<dd class="battles"><?php echo $member['points']; ?></dd>
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
</body>
</html>