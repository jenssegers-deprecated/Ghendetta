<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section class="clan" role="main">
	<h1>About</h1>
    <h2>Ghendetta &lt;Ghent + Vendetta&gt;</h2>
	<dl>
		<dt>Ghent</dt><dd>[:A city of Belgium].</p>
	    <dt>Vendetta</dt><dd>[:A feud between families or clans].</dd>
	</dl>
    <h2>Origin</h2>
    <p> The city government of Ghent opened a lot of data in 2011 and 2012.
        By opening data, developers can focus more on creativity. Together with some partners
        <a href="http://appsforghent.be" target="_blank">Apps For Ghent</a> was organised for the second time on 24th of march, 2012
        to promote this new strategy and motivate developers to use their open data.
        At this event student teams were asked to compete in a hackathon to create a prototype of an application which uses open data.
        <a href="http://irail.be/" target="_blank">iRail</a> sent three students as 'Team iRail'.
        At the end of the day Team iRail presented a prototype of Ghendetta to the city of Ghent
        and a professional jury. After that we heard a lot of positive reactions and decided to continue developing.
    </p>

    <h2>The Game</h2>
    <p>
        How to play the game? Simple. You <a href="<?php echo site_url('foursquare'); ?>">log in</a> with your foursquare account.
        If you don't have an account, you can sign up
        <a href="https://foursquare.com/signup/" target="_blank">here</a>.
        You will become an associate in the clan with the lowest score in the last week to maintain the balance between the clans.
        Either the Hawks, the Wolves, the Panthers or the Snakes. Your check-ins on foursquare,
        in the last week and in the city of Ghent, will be loaded into your map. Be loyal to your clan and your
        <a href="http://en.wikipedia.org/wiki/Caporegime" target="_blank">capo</a>.
        Score as many points as possible for your clan by making a lot of new check-ins on foursquare.
        You don't necessarily need to login or visit our site to score points.
    </p>
    <p>
        The rules are as follows: Every battle (foursquare check-in) in a zone counts as one point
        for that particular zone. The clan can overtake a zone when the total number of points, collected by all your clan associates,
        exceeds the number of points of the former ruling clan. The 'capo' of a clan is the clan member with the highest score.
        Do you like some healthy competition? Then battle hard and try to become the next capo. We don't hold you back to
        plan events or meetings with your fellow associates. It's a great way to get to know new zones of our favourite city,
        make new friends, but above all: conquer new zones! Don't forget: your points will count <strong>for one week</strong> only,
        so be sure to go back if you don't want to lose the zone.
    </p>
    <h2>Technical stuff</h2>
    <p>
        Now. Finally we get to the geeky part. Wat technologies are we using? First of all: a regular LAMP-stack.
        This is Linux, Apache, MySQL and PHP for the webserver, hosted by <a href="http://ilibris.be/">iLibris</a>,
        thanks to <a href="http://irail.be/" target="_blank">iRail</a>.
        The website is built with the CodeIgniter php-framework. And of course we use the
        <a href="http://developers.foursquare.com" target="_blank">foursquare api</a>. check-ins are pushed to our application
        by foursquare itself, so our data and displayed maps are always up-to-date. The beautiful maps by
        <a href="http://mapbox.com/" target="blank">MapBox</a> are created by Open Street Maps and contributors
        (<a href="http://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>). The website is state of the art
        as it scales according to your browser height and width using css.
    </p>
    <h2>Questions?</h2>
    <p>
        Feel free to ask anything you want on
        <a href="https://twitter.com/Ghendetta">Twitter</a>,
        <a href="https://www.facebook.com/pages/Ghendetta/346514788727493">Facebook</a> or by
        <a href="<?php echo site_url('email'); ?>">Email</a>
    </p>
</section>

</body>
</html>
