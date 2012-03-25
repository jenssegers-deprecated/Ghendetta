<?php

?>
<!DOCTYPE html>
<html>
	<head>
		<title>I &#9829; turtles</title>
		<style type="text/css">
			body, html{
				margin:0px;
				padding:0px;
				font-family:Geneva,Helvetica,Arial,Verdana;
				color:#AAA;
			}

			#wrapper{
				width:700px;
				margin-left:auto;
				margin-right:auto;
				margin-top:100px;
				margin-bottom:100px;
			}
			
			#content{
				width:100%;
				padding:50px;
				border: 2px dotted #AAA ;
			}
			
			h1{
				font-size:50px;
			}

			b{
				color:#F5208E;
			}
		
			a{
				cursor:pointer;
				color:inherit;
				text-decoration:inherit;
			}

			a.person{
				display:block;
				border: 1px dotted #AAA;
				padding: 15px;
				height:48px;
				margin: 0px 0px 10px 0px;
				width:350px;
			}

			a.person:hover{
				color:#F5208E;
				border: 1px dotted #F5208E;
			}

			a.person h4{
				float:right;
				margin:0px;
				padding:0px;
				line-height:48px;
			}
		</style>
	</head>
	<body>
		<section id="wrapper">
			<section id="content">
				<h1>We're building something special...</h1>
				<h3>In the meantime, meet 
					<b>team <a href="http://irail.be" target="_blank">iRail</a></b>:
				</h3>
				<a class="person" href="https://twitter.com/#!/Choisissez" target="_blank">
					<img src="http://api.twitter.com/1/users/profile_image/Choisissez.format" />
					<h4>Miet Claes</h4>
				</a>
				<a class="person" href="https://twitter.com/#!/jenssegers" target="_blank">
					<img src="http://api.twitter.com/1/users/profile_image/jenssegers.format" />
					<h4>Jens Segers</h4>
				</a>
				<a class="person" href="https://twitter.com/#!/hannesvdvreken" target="_blank">						
					<img src="http://api.twitter.com/1/users/profile_image/hannesvdvreken.format" />
					<h4>Hannes Van De Vreken</h4>
				</a>
				<a class="person" href="https://twitter.com/#!/GeertVanGampela" target="_blank">						
					<img src="http://api.twitter.com/1/users/profile_image/GeertVanGampela.format" />
					<h4>Geert Van Gampelaere</h4>
				</a>
			</section>
		</section>
	</body>
</html>
