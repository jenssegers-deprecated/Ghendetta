<!DOCTYPE html>
<html>
	<head>
		<title>Who's in your clan?</title>
	</head>
	<body>
		<ul>
			<?php foreach( $clanmembers as $member ): ?>
				<li><a href="https://foursquare.com/user/<?=$member['fsqid']?>">
					<?=$member['firstname'] ?>&nbsp;<?=$member['lastname'] ?>
					has done <?=$member['checkins'] ?> checkins for the <?=$clan['name']?> clan, in the last week.
					<img src="<?=$member['picurl'] ?>" /></a>			
				</li>
			<?php endforeach; ?>
		</ul>
	</body>
</html>
