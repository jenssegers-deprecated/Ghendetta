<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section class="v-about" role="main">
	<h1>About</h1>

    <h2>Ghendetta &lt;Ghent + Vendetta&gt;</h2>
	<dl>
		<dt>Ghent |g&#x25B;nt|</dt><dd>[:A city in Belgium].</p>
	    <dt>Vendetta |v&#x25B;n&#x2C8;d&#x25B;t&#x259;|</dt><dd>[:A blood feud between families or clans].</dd>
	</dl>

	<h2 id="team">Meet the Team</h2>
	<p>Hannes <a href="http://twitter.com/hannesvdvreken">@hannesvdvreken</a> (Developer)</p>
	<p>Jens <a href="http://twitter.com/jenssegers">@jenssegers</a> (Developer)</p>
	<p>Miet <a href="http://twitter.com/choisissez">@choisissez</a> (Designer)</p>
	<p>Xavier <a href="http://twitter.com/xavez">@xavez</a> (Designer)</p>

    <h2 id="howtoplay">How to Play</h2>
    <p>
        <strong>How do you play Ghendetta?</strong> Simple. You <a href="<?php echo site_url('foursquare'); ?>">log in with your foursquare account</a>. If you do not have an account, you can <a href="https://foursquare.com/signup/" rel="external" title="Sign up for a Foursquare account">sign up for one</a>.
	</p>

	<h3>The Clans</h3>

	<p>
		There are four clans in the game: the #Hawks, the #Wolves, the #Panthers and the #Snakes. You will start as an associate in the weakest clan in order to maintain the balance between the clans. Check-ins on Foursquare will be displayed as Battles on your map. Only the last seven days count. So you can lose territory quickly. Be loyal to your clan and your <a href="http://en.wikipedia.org/wiki/Caporegime" rel="external">Capo</a>. Fight as many battles as possible. Try to keep your clan&rsquo;s districts and try to take away districts of other clans by visiting battling in their area. Finally, <strong>try to conquer the entire city</strong>.
    </p>

	<h3>The Rules</h3>
    <p>
        Every Foursquare check-in counts as a battle. Every battle within a district also counts as one battle for that particular district. A clan can take over a district when the total number of battles fought by all of the clan members exceeds the number of battles fought by the former ruling clan. The <em>Capo</em> of a clan is the clan member with the highest score within that clan. Do you like some healthy competition? Then battle hard and try to become the next Capo.
	</p>
	<p>
		We encourage you to plan events or meetings with your fellow associates. It&#x27;s a great way to get to know new districts of our favourite city, make new friends, but above all: conquer new districts! Don&#x27;t forget: your battles will count <strong>for seven days</strong> only. So be sure to go back if you don&#x27;t want to lose your district.
    </p>

    <h2 id="origin">Game Origin</h2>
    <p>
		The basics of Ghendetta were entirely conceived and built in little under six hours at <a href="http://appsforghent.be" rel="external">Apps For Ghent 2012</a>. We have continued developing the game since then.
	</p>
	<p>
		The city of Ghent made a lot of data publicly available in 2011 and 2012. In order to promote the use of this data, the city helped organize Apps For Ghent for the second time in 2012. Teams of students were asked to compete in a hackathon to create a prototype of an application that uses Open Data. We entered the competition under the moniker &ldquo;Team <a href="http://irail.be" rel="external">iRail</a>&rdquo;. By the end of the day, we presented a working prototype of Ghendetta to a professional jury. We finished second, but we decided to continue developing the game because we really believed in its potential.
    </p>

    <h2 id="technical">Technical Stuff</h2>
    <p>
        Now, for the geeky part. What technologies are we using? As much Open Source Software as is possible! Ghendetta runs on a regular <abbr title="Linux, Apache, MySQL and PHP">LAMP</abbr>-stack, hosted by <a href="http://ilibris.be" rel="external">iLibris</a>, thanks to <a href="http://irail.be" rel="external">iRail</a>. The entire game is built with the <a href="http://codeigniter.com" rel="external">CodeIgniter</a> PHP framework. And of course we use the <a href="http://developers.foursquare.com" rel="external">Foursquare API</a>. Check-ins are pushed to our application by Foursquare itself, so our data and displayed maps are always up-to-date. The beautiful maps by <a href="http://mapbox.com" rel="external">MapBox</a> are created by <a href="http://www.openstreetmap.org" rel="external">OpenStreetMaps</a> and contributors under a <a href="http://creativecommons.org/licenses/by-sa/2.0" rel="license">CC-BY-SA</a> license. The front-end is entirely HTML5.
    </p>

    <h2 id="contact">Questions?</h2>
    <p>
        Just fucking ask via
        <a href="https://twitter.com/Ghendetta">Twitter</a>,
        <a href="https://www.facebook.com/pages/Ghendetta/346514788727493">Facebook</a> or by
        <a href="<?php echo site_url('email'); ?>">Email</a>.
    </p>
</section>

</body>
</html>