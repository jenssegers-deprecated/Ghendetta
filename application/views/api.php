<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section class="container v-api" role="main">

	<article id="api-info">
		<h1>Ghendetta API</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vulputate, turpis sed fringilla consectetur, eros libero iaculis lectus, at volutpat eros metus ac eros. Aenean dapibus scelerisque ullamcorper. Etiam volutpat, enim at sagittis lacinia, odio justo rutrum eros, id dictum nulla metus at orci. Praesent bibendum varius metus sit amet ornare. Nam ac turpis nulla, ullamcorper tincidunt felis. Nam venenatis venenatis mauris, ornare accumsan diam blandit vel. Duis quam dolor, facilisis et scelerisque in, suscipit et tortor. Mauris et orci purus, non pellentesque leo. Vivamus sed laoreet neque. Etiam non lectus nisl. Integer vitae eros in felis egestas tempor id et risus. Sed vitae tortor augue, id fermentum arcu.</p>
	</article>
	
	<article id="api-endpoints">
		<h2>API Endpoints</h2>
	
    	<table width="100%">
    		<thead>
    			<tr>
    				<th>URI</th>
    				<th>Description</th>
    				<th>Fields</th>
    			</tr>
    		</thead>
    		<tbody>
        		<tr>
        			<td>user.json</td>
        			<td>Get information about the current user</td>
        			<td>fsqid, firstname, lastname, picurl, clanid, points, battles, rank</td>
        		</tr>
        		<tr>
        			<td>user/battles.json</td>
        			<td>Get recent battles of the current user</td>
        			<td>checkinid, userid, venueid, date, regionid, points, lon, lat</td>
        		</tr>
        		<tr>
        			<td>clans.json</td>
        			<td>Get information about all clans</td>
        			<td>clanid, name, logo, color, capo, points, battles, members</td>
        		</tr>
        		<tr>
        			<td>clans/[id].json</td>
        			<td>Get information about a single clan</td>
        			<td>checkinid, userid, venueid, date, regionid, points, lon, lat</td>
        		</tr>
        		<tr>
        			<td>regions.json</td>
        			<td>Get information about all regions</td>
        			<td>regionid, name, <em>leader</em>, coords</td>
        		</tr>
        		<tr>
        			<td>regions/[id].json</td>
        			<td>Get information about all clans in a single region</td>
        			<td><em>clans</em></td>
        		</tr>
    		</tbody>
    	</table>
    	
	</article>
	
</section>

<?php include_once('footer.tpl'); ?>

</body>
</html>