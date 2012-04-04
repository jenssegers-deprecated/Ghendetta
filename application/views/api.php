<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section class="container v-api" role="main">

	<article id="api-info">
		<h1>Ghendetta API</h1>
		<p>
			We like open data, so we would like to share some of our own data with other developers. Through our simple API system you can request the same data we are working with to create your own creative extensions to Ghendetta. There is no authentication required (yet), so no need to work with a complicated auth system. Please note that all our API results have a small cache to prevent server stressing.
			<br /><br />
			<em>The API system is still under heavy development, changes will happen!</em>
		</p>
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
        			<td>api/user.json</td>
        			<td>Get information about the current user</td>
        			<td>fsqid, firstname, lastname, picurl, clanid, points, battles, rank</td>
        		</tr>
        		<tr>
        			<td>api/user/battles.json</td>
        			<td>Get recent battles of the current user</td>
        			<td>checkinid, userid, venueid, date, regionid, points, lon, lat</td>
        		</tr>
        		<tr>
        			<td>api/clans.json</td>
        			<td>Get information about all clans</td>
        			<td>clanid, name, logo, color, capo, points, battles, members</td>
        		</tr>
        		<tr>
        			<td>api/clans/[id].json</td>
        			<td>Get information about a single clan</td>
        			<td>checkinid, userid, venueid, date, regionid, points, lon, lat</td>
        		</tr>
        		<tr>
        			<td>api/regions.json</td>
        			<td>Get information about all regions</td>
        			<td>regionid, name, <em><u>leader</u></em>, coords</td>
        		</tr>
        		<tr>
        			<td>api/regions/[id].json</td>
        			<td>Get information about all clans in a single region</td>
        			<td><em>list of <u>clans</u></em></td>
        		</tr>
        		<tr>
        			<td>api/stats.json</td>
        			<td>Get general information about ghendetta</td>
        			<td>users, battles, regions, clans</td>
        		</tr>
    		</tbody>
    	</table>
    	
	</article>
	
</section>

<?php include_once('footer.tpl'); ?>

</body>
</html>