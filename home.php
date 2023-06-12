<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>Compass Starter by Ariona, Rian</title>

		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="style.css">
		
		<!--[if lt IE 9]>
		<script src="js/ie-support/html5.js"></script>
		<script src="js/ie-support/respond.js"></script>
		<![endif]-->

	</head>


	<body>
		
		<div class="site-content">
			<div class="site-header">
				<div class="container">
					<a href="" class="branding">
						<img src="images/logo.png" alt="" class="logo">
						<div class="logo-type">
							<h1 class="site-title">Company name</h1>
							<small class="site-description">tagline goes here</small>
						</div>
					</a>
				</div>
			</div> <!-- .site-header -->
			<div class="hero" data-bg-image="images/banner.png">
				<div class="container">
					<form action="#" class="find-location">
						<input type="text" placeholder="Find your location...">
						<input type="submit" value="Find">
					</form>

				</div>
			</div>
			<div class="forecast-table">
				<div class="container">
					<div class="forecast-container">
						<div class="today forecast">
							<div class="forecast-header">
								<div class="day"><?php echo date("l")?></div>
								<div class="date"><?php echo date("d") ." ". date("M")?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="location"><?php echo $city?></div>
								<div class="degree">
									<div class="num"><?php echo round($forecast["current_weather"]["temperature"])?><sup>o</sup>C</div>
									<div class="forecast-icon">
										<img src="images/icons/<?php echo $forecast["current_weather"]["wmo_icon"]?>" alt="" width=90>
									</div>	
								</div>
								<span><img src="images/icon-umberella.png" alt=""><?php echo $forecast["hourly"]["precipitation_probability"][0]?>%</span>
								<span><img src="images/icon-wind.png" alt=""><?php echo round($forecast["current_weather"]["windspeed"])?>km/h</span>
								<span><img src="images/icon-compass.png" alt=""><?php echo $forecast["current_weather"]["winddirection_cardinal"]?></span>
							</div>
						</div>
                        <?php for ($day = 0; $day < 6; $day++) { ?>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $forecasted_days[$day]?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src="images/icons/<?php echo $forecast["daily"]["wmo_icon"][$day]?>" alt="" width=48>
								</div>
								<div class="degree"><?php echo round($forecast["daily"]["temperature_2m_max"][$day])?><sup>o</sup>C</div>
								<small><?php echo round($forecast["daily"]["temperature_2m_min"][$day])?><sup>o</sup></small>
							</div>
						</div>
                        <?php }?>
					</div>
				</div>
			</div>
		</div>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/app.js"></script>
		
	</body>

</html>