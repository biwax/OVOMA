<?php

$start = microtime(true);

// global variables
$weekEndText = 'Pas de menu du jour le week-end';
$websiteNotAvailable = '(site inaccessible)';
$menuFormat = '<a href="%s" target="_blank">Menu de la semaine</a>';
$menuDayFormat = '<a href="%s" target="_blank">Menu du jour</a>';

$testMode = false;
$testDate = new DateTime();
$testDate->setDate(2015, 12, 8);

include('code/utils.php');
include('code/class.BaseParser.php');
include('code/class.HTMLParser.php');
include('code/class.PDFParser.php');
include('code/class.Restaurants.php');

?>
<!DOCTYPE HTML>
<!--
	Phase Shift by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>OVOMA - Où Va-t-On Manger Aujourd'hui?</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="user-scalable=0, initial-scale=1.0">
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>

		<!-- Wrapper -->
			<div class="wrapper style1">
			
				<!-- Banner -->
					<div id="banner" class="container">
						<section>
							<header class="major">
								<?php
									if($testMode) {
										echo '<h2>Test mode!</h2>';
										echo '<h3>' . $testDate->format('d/m/Y') . '</h3>';
									} else {
										echo '<img src="images/titre_beta.png" width="280" height="95" alt="" />';
									}
								?>
								<div class="subtitle"><strong>O</strong>ù <strong>v</strong>a-t-<strong>o</strong>n <strong>m</strong>anger <strong>a</strong>ujourd'hui</div>
							</header>
						</section>
					</div>

				<!-- Extra -->
					<div id="extra">
						<div class="container">
							<div class="row no-collapse-1">
								<section class="4u">
									<div class="lined">
										<a href="http://www.sports-cafe.ch/" target="_blank" class="image featured"><img src="images/sports_cafe_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new SportsCafeParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu 1 à 17.50 CHF, Menu 2 à 15.50 CHF<br />
												<i class="fa fa-book"></i> <a href="http://sports-cafe.ch/menu.png" target="_blank">Carte</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216162869">021 616 28 69</a>
											</div>
										</div>
									</div>
								</section>
								<section class="4u">
									<div class="lined">
										<a href="http://www.piazza-san-marco.ch" target="_blank" class="image featured"><img src="images/san_marco_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new SanMarcoParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu 1 à 16.00 CHF, Menu 2 à 17.00 CHF, Suggestion à 21.50 CHF<br />
												<i class="fa fa-tag"></i> Réduction de 10% ELCA<br />
												<i class="fa fa-book"></i> <a href="http://www.piazza-san-marco.ch/carte.php" target="_blank">Carte</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216012720">021 601 27 20</a>
											</div>
										</div>
									</div>
								</section>
								<section class="4u">
									<div class="lined">
										<a href="http://www.le-pinocchio.ch" target="_blank" class="image featured"><img src="images/pinocchio_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new LePinocchioParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu 1 à 19.00 CHF, Hit de la semaine à 24.50 CHF, Menu 3 à 18.00 CHF, Menu 4 à 17.50 CHF<br />
												<i class="fa fa-tag"></i> Réduction de 10% ELCA<br />
												<i class="fa fa-book"></i> <a href="http://www.le-pinocchio.ch/fr/carte.htm" target="_blank">Carte</a><br />
												<i class="fa fa-sun-o"></i> <a href="http://www.le-pinocchio.ch/view/data/3070/Chasse%202015.pdf" target="_blank">Carte de saison</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216164037">021 616 40 37</a>
											</div>
										</div>
									</div>
								</section>
							</div>
							<div class="row no-collapse-1">
								<section class="4u"> 
									<div class="lined">
										<a href="http://www.boccalino.ch" target="_blank" class="image featured"><img src="images/boccalino_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new LeBoccalinoParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu à 18.00 CHF<br />
												<i class="fa fa-book"></i> <a href="http://www.boccalino.ch/fr/notre-carte" target="_blank">Carte</a><br />
												<i class="fa fa-pie-chart"></i> <a href="https://s3-eu-west-1.amazonaws.com/sc-files.pjms.fr/p/localch/000/050/009/044/48473c9bd7af4d79abc083e383237d64.pdf" target="_blank">Carte des pizzas</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216163539">021 616 35 39</a>
											</div>
										</div>
									</div>
								</section>
								<section class="4u">
									<div class="lined">
										<a href="http://www.aulac.ch/restaurant-le-pirate-lausanne-fr84.html" target="_blank" class="image featured"><img src="images/pirate_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new LePirateParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu viande et poisson à 19.00 CHF<br />
												<i class="fa fa-book"></i> <a href="http://www.aulac.ch/files/1447924236-2015-2016-hiver-5242.pdf" target="_blank">Carte</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216131500">021 613 15 00</a>
											</div>
										</div>
									</div>
								</section>							
								<section class="4u">
									<div class="lined">
										<a href="http://www.le-milan.ch" target="_blank" class="image featured"><img src="images/milan_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new LeMilanParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Assiette du jour à 18.50 CHF<br />
												<i class="fa fa-phone"></i> <a href="tel:+41216165343">021 616 53 43</a>
											</div>
										</div>
									</div>
								</section>
							</div>
							<div class="row no-collapse-1">
								<section class="4u">
									<div class="lined">
										<a href="http://www.cafedeleurope.ch" target="_blank" class="image featured"><img src="images/europe_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new CafeEuropeParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu 1 à 17.50 CHF, Menu 2 à 19.50 CHF<br />
												<i class="fa fa-book"></i> <a href="http://www.cafedeleurope.ch/menu.html" target="_blank">Carte</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216165585">021 616 55 85</a>
											</div>
										</div>
									</div>
								</section>
								<section class="4u">
									<div class="lined">
										<a href="http://www.elblanco.ch" target="_blank" class="image featured"><img src="images/white_horse_logo.png" alt=""></a>
										<div class="box">
											<div>
												<?php	$parser = new WhiteHorseParser;
														echo $parser->getContent(); ?>
											</div>
											<hr />
											<div class="infos">
												<i class="fa fa-money"></i> Menu 1 à 18.50 CHF, Menu 2 à 17.50 CHF<br />
												<i class="fa fa-book"></i> <a href="http://www.elblanco.ch/wa_files/141120_CARTE_MENU_WH_DEF_pt.pdf" target="_blank">Carte</a><br />
												<i class="fa fa-phone"></i> <a href="tel:+41216167575">021 616 75 75</a>
											</div>
										</div>
									</div>
								</section>							
							</div>
						</div>
					</div>
		</div>
		
	<!-- Footer -->
		<div id="footer" class="wrapper style2">
			<div class="container">
				<section>
					<header class="major">
						<span class="byline">Pas d'inspiration? Laisse OVOMA choisir pour toi!</span>
					</header>
					
					<div class="row half">
						<div class="12u">
							<input type="button" id="random" value="Choisis pour moi !" class="button alt" />
						</div>
					</div>
					
					<div class="row half">
						<div class="12u" id="randomChoice">
						</div>
					</div>
					
				</section>
			</div>
		</div>


	<!-- Copyright -->
		<div id="copyright">
			<div class="container">
				<div class="copyright">
					<p>Powered by Omnichannel 360° vertical engine <i class="fa fa-copyright"></i><br />
					<span class="generation">
						<?php	$end = microtime(true);
								$creationtime = ($end - $start);
								printf("Page created in %.2f seconds", $creationtime);
						?>
					</span>
					</p>
				</div>
			</div>
		</div>
	<script src="js/randomizer.js"></script>
	</body>
</html>