<ul>
	<?php foreach($clanmembers as $member): ?>
		<li><a href="https://foursquare.com/user/<?php echo $member['fsqid']; ?>">
			<?php echo $member['firstname']; ?>&nbsp;<?php echo $member['lastname']; ?>
			has done <?php echo $member['points']; ?> checkins for the <?php echo $clan['name']; ?> clan, in the last week.
			<img src="<?php echo $member['picurl']; ?>" /></a>
		</li>
	<?php endforeach; ?>
</ul>