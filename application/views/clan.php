<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<?php
// get the capo
$capo = reset($members);
$quarter = floor($capo['points'] / 4);
?>


<section class="v-clan cf" role="main">
	<div class="bd">
		<h1>My Clan: <?php echo $clan['name']; ?> (<strong><?php echo  $clan['points']; ?></strong> <abbr title="Foursquare Checkins">battles</abbr>)</h1>
		<!-- <p>Your Capo is pleased. <a href="https://twitter.com/intent/tweet?source=webclient&amp;text=Mayhem%21+%23<?php echo $clan['name']; ?>+have+just+unlocked+the+clan+page+on+http%3A%2F%2Fghendetta.be" class="button">Tweet about this.</a></p> -->
	</div>
	<?php foreach($members as $member): ?>
	<article>
		<div class="cf">
			<h1<?php if($member['rank'] == 1 || $member['fsqid'] == $user['fsqid']) echo " class=\"capo\""; ?>>
                <a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>"><?php echo $member['firstname']; ?></a>
            </h1>
			<a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>">
				<img src="<?php echo $member['picurl']; ?>" alt="<?php echo $member['firstname']; ?>" width="72" height="72" />
			</a>
			<dl>
				<dt>Battles:</dt>
				<dd class="battles"><?php echo $member['points']; ?></dd>
				<dt>Rank:</dt>
				<dd class="rank">
					<?php
						switch (TRUE) {
						    case ($member['rank'] == 1):
    						    echo '<strong>Capo</strong>';
    						    break;
						    case ($member['points'] > $quarter*3):
						        echo 'Bruglione';
						        break;
						    case ($member['points'] > $quarter*2):
						        echo 'Assassin';
						        break;
						    case ($member['points'] > $quarter):
						        echo 'Mobster';
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

<?php include_once('footer.tpl'); ?>

</body>
</html>
